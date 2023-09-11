<?php

declare(strict_types=1);

namespace WEBcoast\Typo3ConfigLoader;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\ArrayUtility;

class Loader
{
    protected Configuration $configuration;

    protected Helper $helper;

    public function __construct()
    {
        $this->configuration = new Configuration();
        $this->helper = new Helper($this->configuration, Environment::getConfigPath());
    }

    /**
     * @param bool $inheritFromParentContext
     *
     * @return $this
     */
    public function setInheritFromParentContext(bool $inheritFromParentContext): self
    {
        $this->configuration->setInheritFromParentContext($inheritFromParentContext);

        return $this;
    }

    /**
     * @param bool $includeDefaultConfiguration
     *
     * @return Loader
     */
    public function setIncludeDefaultConfiguration(bool $includeDefaultConfiguration): self
    {
        $this->configuration->setIncludeDefaultConfiguration($includeDefaultConfiguration);

        return $this;
    }

    /**
     * @param $includeLocalConfigurationFiles
     *
     * @return Loader
     */
    public function setIncludeLocalConfigurationFiles($includeLocalConfigurationFiles): self
    {
        $this->configuration->setIncludeLocalConfigurationFiles($includeLocalConfigurationFiles);

        return $this;
    }

    /**
     * @param bool $includeMyCnf
     *
     * @return Loader
     */
    public function setIncludeMyCnf(bool $includeMyCnf): self
    {
        $this->configuration->setIncludeMyCnf($includeMyCnf);

        return $this;
    }

    /**
     * @param string $subContextSeparator
     *
     * @return Loader
     */
    public function setSubContextSeparator(string $subContextSeparator): self
    {
        $this->configuration->setSubContextSeparator($subContextSeparator);

        return $this;
    }

    /**
     * @param string $configFileSuffix
     *
     * @return Loader
     */
    public function setConfigFileSuffix(string $configFileSuffix): self
    {
        $this->configuration->setConfigFileSuffix($configFileSuffix);

        return $this;
    }

    /**
     * @param bool $useFolderPerContext
     *
     * @return Loader
     */
    public function setUseFolderPerContext(bool $useFolderPerContext): self
    {
        $this->configuration->setUseFolderPerContext($useFolderPerContext);

        return $this;
    }

    public function load()
    {
        // Validate configuration before beginning loading the configuration
        $this->configuration->validate();

        // Include the default configuration, if enabled
        if ($this->configuration->getIncludeDefaultConfiguration()) {
            $defaultConfigPath = Environment::getConfigPath() . DIRECTORY_SEPARATOR . 'ConfigDefault.php';
            if (file_exists($defaultConfigPath)) {
                require $defaultConfigPath;
            }
        }

        // Determine root and sub context
        $context = (string)Environment::getContext();
        if (strpos($context, '/') !== false) {
            [$rootContext, $subContext] = explode('/', $context);
        } else {
            $rootContext = $context;
            $subContext = null;
        }

        // Include database credentials from `.my.cnf` file
        if ($this->configuration->getIncludeMyCnf()) {
            if ($subContext === null || $this->configuration->getInheritFromParentContext()) {
                $rootContextMyCnf = $this->helper->getMyCnfPath($rootContext);
                if (file_exists($rootContextMyCnf)) {
                    $this->applyMyCnf($rootContextMyCnf);
                }
            }

            if (!empty($subContext)) {
                $subContextMyCnf = $this->helper->getMyCnfPath($rootContext, $subContext);

                if (file_exists($subContextMyCnf)) {
                    $this->applyMyCnf($subContextMyCnf);
                }
            }
        }

        // Include root context configuration
        if ($subContext === null || $this->configuration->getInheritFromParentContext()) {
            $rootContextConfigurationFile = $this->helper->getContextConfigFilePath($rootContext);
            if (file_exists($rootContextConfigurationFile)) {
                require $rootContextConfigurationFile;
            }
        }

        // Include sub context configuration
        if (!empty($subContext)) {
            $subContextConfigurationFile = $this->helper->getContextConfigFilePath($rootContext, $subContext);
            if (file_exists($subContextConfigurationFile)) {
                require $subContextConfigurationFile;
            }
        }

        if ($this->configuration->getIncludeLocalConfigurationFiles()) {
            // Include local root context configuration
            if ($subContext === null || $this->configuration->getInheritFromParentContext()) {
                $rootContextConfigurationFile = $this->helper->getLocalContextConfigFilePath($rootContext);
                if (file_exists($rootContextConfigurationFile)) {
                    require $rootContextConfigurationFile;
                }
            }

            // Include local sub context configuration
            if (!empty($subContext)) {
                $subContextConfigurationFile = $this->helper->getLocalContextConfigFilePath($rootContext, $subContext);
                if (file_exists($subContextConfigurationFile)) {
                    require $subContextConfigurationFile;
                }
            }
        }
    }

    protected function applyMyCnf($myCnfPath)
    {
        // Security tip for production: putting the database password outside the document root!
        $dbConfig = [
            'host' => null,
            'database' => null,
            'user' => null,
            'password' => null
        ];

        if ($myCnfPath) {
            $myCnf = parse_ini_file($myCnfPath, true);
            foreach ($myCnf as $section => $config) {
                if (in_array($section, ['client', 'mysql'])) {
                    foreach ($config as $name => $value) {
                        if (in_array($name, ['host', 'database', 'user', 'password'])) {
                            $dbConfig[$name] = $value;
                        }
                    }
                }
            }
        }

        ArrayUtility::mergeRecursiveWithOverrule(
            $GLOBALS['TYPO3_CONF_VARS'],
            [
                'DB' => [
                    'Connections' => [
                        'Default' => [
                            'host' => $dbConfig['host'] ?? $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] ?? 'localhost',
                            'dbname' => $dbConfig['database'] ?? $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] ?? '',
                            'user' => $dbConfig['user'] ?? $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] ?? '',
                            'password' => $dbConfig['password'] ?? $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] ?? ''
                        ],
                    ],
                ],
            ]
        );
    }
}
