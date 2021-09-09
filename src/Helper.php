<?php

declare(strict_types=1);

namespace WEBcoast\Typo3ConfigLoader;

class Helper
{
    protected Configuration $configuration;

    protected string $configRootPath;

    /**
     * @param Configuration $configuration
     * @param string        $configRootPath
     */
    public function __construct(Configuration $configuration, string $configRootPath)
    {
        $this->configuration = $configuration;
        $this->configRootPath = rtrim($configRootPath, '/' . DIRECTORY_SEPARATOR);
    }

    public function getContextConfigFolder(string $rootContext, ?string $subContext = null)
    {
        $configPath = $this->configRootPath;

        if ($this->configuration->getUseFolderPerContext()) {
            $configPath .= DIRECTORY_SEPARATOR . $rootContext;

            if (!empty($subContext)) {
                $configPath .= DIRECTORY_SEPARATOR . $subContext;
            }
        }

        return $configPath;
    }

    public function getMyCnfPath(string $rootContext, ?string $subContext = null)
    {
        $configPath = $this->getContextConfigFolder($rootContext, $subContext);

        if (!$this->configuration->getUseFolderPerContext()) {
            $configPath .= DIRECTORY_SEPARATOR . $rootContext;

            if (!empty($subContext)) {
                $configPath .= $this->configuration->getSubContextSeparator() . $subContext;
            }
        } else {
            $configPath .= '/';
        }

        return $configPath . '.my.cnf';
    }

    public function getContextConfigFilePath(string $rootContext, ?string $subContext = null)
    {
        $configPath = $this->getContextConfigFolder($rootContext, $subContext);

        if (!$this->configuration->getUseFolderPerContext()) {
            $configPath .= DIRECTORY_SEPARATOR . $rootContext;

            if (!empty($subContext)) {
                $configPath .= $this->configuration->getSubContextSeparator() . $subContext;
            }
        } else {
            $configPath .= '/';
        }

        if (!empty($this->configuration->getConfigFileSuffix())) {
            $configPath .= $this->configuration->getConfigFileSuffix();
        }

        return $configPath . '.php';
    }

    public function getLocalContextConfigFilePath(string $rootContext, ?string $subContext = null)
    {
        $configPath = $this->getContextConfigFolder($rootContext, $subContext);

        if (!$this->configuration->getUseFolderPerContext()) {
            $configPath .= DIRECTORY_SEPARATOR . $rootContext;

            if (!empty($subContext)) {
                $configPath .= $this->configuration->getSubContextSeparator() . $subContext;
            }

            $configPath .= $this->configuration->getSubContextSeparator();
        } else {
            $configPath .= '/';
        }


        $configPath .= 'Local';

        if (!empty($this->configuration->getConfigFileSuffix())) {
            $configPath .= $this->configuration->getConfigFileSuffix();
        }

        return $configPath . '.php';
    }
}
