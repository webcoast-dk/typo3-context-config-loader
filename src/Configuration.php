<?php

declare(strict_types=1);

namespace WEBcoast\Typo3ConfigLoader;

class Configuration
{
    /**
     * Whether or not to inherit from parent context or only use the current subcontext
     *
     * @var bool
     */
    protected bool $inheritFromParentContext = true;

    /**
     * Whether or not to include configuration from `{configFolder}/Default.php'
     *
     * @var bool
     */
    protected bool $includeDefaultConfiguration = false;

    /**
     * Whether or not to include configuration files with `.Local` suffix
     *
     * @var bool
     */
    protected bool $includeLocalConfigurationFiles = false;

    /**
     * Whether or not to include `.my.cnf` files
     *
     * @var bool
     */
    protected bool $includeMyCnf = false;

    /**
     * Separator for sub context, e.g. `.`, `-`, `_` or ``
     *
     * @var string
     */
    protected string $subContextSeparator = '.';

    /**
     * Suffix for config file, e.g. `Config` or `AdditionalConfiguration`. The `.php` extension is automatically added.
     *
     * @var string
     */
    protected string $configFileSuffix = '';

    /**
     * TRUE: Use a sub folder per context
     * FALSE: Keep all files in one directory
     *
     * @var bool
     */
    protected bool $useFolderPerContext = false;

    /**
     * @return bool
     */
    public function getInheritFromParentContext(): bool
    {
        return $this->inheritFromParentContext;
    }

    /**
     * @param bool $inheritFromParentContext
     *
     * @return Configuration
     */
    public function setInheritFromParentContext(bool $inheritFromParentContext): self
    {
        $this->inheritFromParentContext = $inheritFromParentContext;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIncludeDefaultConfiguration(): bool
    {
        return $this->includeDefaultConfiguration;
    }

    /**
     * @param bool $includeDefaultConfiguration
     *
     * @return Configuration
     */
    public function setIncludeDefaultConfiguration(bool $includeDefaultConfiguration): self
    {
        $this->includeDefaultConfiguration = $includeDefaultConfiguration;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIncludeLocalConfigurationFiles(): bool
    {
        return $this->includeLocalConfigurationFiles;
    }

    /**
     * @param bool $includeLocalConfigurationFiles
     *
     * @return Configuration
     */
    public function setIncludeLocalConfigurationFiles(bool $includeLocalConfigurationFiles): self
    {
        $this->includeLocalConfigurationFiles = $includeLocalConfigurationFiles;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIncludeMyCnf(): bool
    {
        return $this->includeMyCnf;
    }

    /**
     * @param bool $includeMyCnf
     *
     * @return Configuration
     */
    public function setIncludeMyCnf(bool $includeMyCnf): self
    {
        $this->includeMyCnf = $includeMyCnf;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubContextSeparator(): string
    {
        return $this->subContextSeparator;
    }

    /**
     * @param string $subContextSeparator
     *
     * @return Configuration
     */
    public function setSubContextSeparator(string $subContextSeparator): self
    {
        $this->subContextSeparator = $subContextSeparator;

        return $this;
    }

    /**
     * @return string
     */
    public function getConfigFileSuffix(): string
    {
        return $this->configFileSuffix;
    }

    /**
     * @param string $configFileSuffix
     *
     * @return Configuration
     */
    public function setConfigFileSuffix(string $configFileSuffix): self
    {
        $this->configFileSuffix = $configFileSuffix;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUseFolderPerContext(): bool
    {
        return $this->useFolderPerContext;
    }

    /**
     * @param bool $useFolderPerContext
     *
     * @return Configuration
     */
    public function setUseFolderPerContext(bool $useFolderPerContext): self
    {
        $this->useFolderPerContext = $useFolderPerContext;

        return $this;
    }

    public function validate()
    {
        if (!empty($this->subContextSeparator) && !in_array($this->subContextSeparator, ['.', '-', '_'])) {
            throw new \InvalidArgumentException('Sub context separator must be `.`, `-`, `_` or empty.');
        }

        if (empty($this->configFileSuffix) && $this->useFolderPerContext) {
            throw new \InvalidArgumentException('Config file suffix must be non-empty when `useFolderPerContext` is disabled.');
        }
    }
}
