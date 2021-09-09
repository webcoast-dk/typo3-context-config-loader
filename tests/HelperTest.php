<?php

use WEBcoast\Typo3ConfigLoader\Configuration;
use WEBcoast\Typo3ConfigLoader\Helper;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    const CONFIG_ROOT_PATH = '/var/www/html/config';

    /**
     * @param $rootContext
     * @param $subContext
     * @param $configuration
     * @param $expected
     *
     * @test
     * @dataProvider getContextConfigFilePathTestProvider
     */
    public function getContextConfigFilePathTest($rootContext, $subContext, $configuration, $expected)
    {
        $helper = new Helper($configuration, self::CONFIG_ROOT_PATH);

        $this->assertEquals($expected, $helper->getContextConfigFilePath($rootContext, $subContext));
    }

    public function getContextConfigFilePathTestProvider(): array
    {
        return [
            'Default settings (root context)' => [
                'Production',
                null,
                (new Configuration()),
                self::CONFIG_ROOT_PATH . '/Production.php'
            ],
            'Default settings (sub context)' => [
                'Production',
                'Staging',
                (new Configuration()),
                self::CONFIG_ROOT_PATH . '/Production.Staging.php'
            ],
            'Underscore context separator' => [
                'Production',
                'Staging',
                (new Configuration())
                    ->setSubContextSeparator('_'),
                self::CONFIG_ROOT_PATH . '/Production_Staging.php'
            ],
            'Config file suffix (root context)' => [
                'Production',
                null,
                (new Configuration())
                    ->setConfigFileSuffix('Config'),
                self::CONFIG_ROOT_PATH . '/ProductionConfig.php'
            ],
            'Config file suffix (sub context)' => [
                'Production',
                'Staging',
                (new Configuration())
                    ->setConfigFileSuffix('Config'),
                self::CONFIG_ROOT_PATH . '/Production.StagingConfig.php'
            ],
            'Folder per context (root context)' => [
                'Production',
                null,
                (new Configuration())
                    ->setConfigFileSuffix('Config')
                    ->setUseFolderPerContext(true),
                self::CONFIG_ROOT_PATH . '/Production/Config.php'
            ],
            'Folder per context (sub context)' => [
                'Production',
                'Staging',
                (new Configuration())
                    ->setConfigFileSuffix('Config')
                    ->setUseFolderPerContext(true),
                self::CONFIG_ROOT_PATH . '/Production/Staging/Config.php'
            ],
        ];
    }

    /**
     * @param $rootContext
     * @param $subContext
     * @param $configuration
     * @param $expected
     *
     * @test
     * @dataProvider getLocalContextConfigFilePathTestProvider
     */
    public function getLocalContextConfigFilePathTest($rootContext, $subContext, $configuration, $expected)
    {
        $helper = new Helper($configuration, self::CONFIG_ROOT_PATH);

        $this->assertEquals($expected, $helper->getLocalContextConfigFilePath($rootContext, $subContext));
    }

    public function getLocalContextConfigFilePathTestProvider(): array
    {
        return [
            'Default settings (root context)' => [
                'Production',
                null,
                (new Configuration()),
                self::CONFIG_ROOT_PATH . '/Production.Local.php'
            ],
            'Default settings (sub context)' => [
                'Production',
                'Staging',
                (new Configuration()),
                self::CONFIG_ROOT_PATH . '/Production.Staging.Local.php'
            ],
            'Underscore context separator (root context)' => [
                'Production',
                null,
                (new Configuration())
                    ->setSubContextSeparator('_'),
                self::CONFIG_ROOT_PATH . '/Production_Local.php'
            ],
            'Underscore context separator (sub context)' => [
                'Production',
                'Staging',
                (new Configuration())
                    ->setSubContextSeparator('_'),
                self::CONFIG_ROOT_PATH . '/Production_Staging_Local.php'
            ],
            'Config file suffix (root context)' => [
                'Production',
                null,
                (new Configuration())
                    ->setConfigFileSuffix('Config'),
                self::CONFIG_ROOT_PATH . '/Production.LocalConfig.php'
            ],
            'Config file suffix (sub context)' => [
                'Production',
                'Staging',
                (new Configuration())
                    ->setConfigFileSuffix('Config'),
                self::CONFIG_ROOT_PATH . '/Production.Staging.LocalConfig.php'
            ],
            'Folder per context (root context)' => [
                'Production',
                null,
                (new Configuration())
                    ->setConfigFileSuffix('Config')
                    ->setUseFolderPerContext(true),
                self::CONFIG_ROOT_PATH . '/Production/LocalConfig.php'
            ],
            'Folder per context (sub context)' => [
                'Production',
                'Staging',
                (new Configuration())
                    ->setConfigFileSuffix('Config')
                    ->setUseFolderPerContext(true),
                self::CONFIG_ROOT_PATH . '/Production/Staging/LocalConfig.php'
            ],
        ];
    }

    /**
     * @param $rootContext
     * @param $subContext
     * @param $configuration
     * @param $expected
     *
     * @test
     * @dataProvider getMyCnfPathTestProvider
     */
    public function getMyCnfPathTest($rootContext, $subContext, $configuration, $expected)
    {
        $helper = new Helper($configuration, self::CONFIG_ROOT_PATH);

        $this->assertEquals($expected, $helper->getMyCnfPath($rootContext, $subContext));
    }

    public function getMyCnfPathTestProvider(): array
    {
        return [
            'Default settings (root context)' => [
                'Production',
                null,
                (new Configuration()),
                self::CONFIG_ROOT_PATH . '/Production.my.cnf'
            ],
            'Default settings (sub context)' => [
                'Production',
                'Staging',
                (new Configuration()),
                self::CONFIG_ROOT_PATH . '/Production.Staging.my.cnf'
            ],
            'Underscore context separator' => [
                'Production',
                'Staging',
                (new Configuration())
                    ->setSubContextSeparator('_'),
                self::CONFIG_ROOT_PATH . '/Production_Staging.my.cnf'
            ],
            'Folder per context (root context)' => [
                'Production',
                null,
                (new Configuration())
                    ->setConfigFileSuffix('Config')
                    ->setUseFolderPerContext(true),
                self::CONFIG_ROOT_PATH . '/Production/.my.cnf'
            ],
            'Folder per context (sub context)' => [
                'Production',
                'Staging',
                (new Configuration())
                    ->setConfigFileSuffix('Config')
                    ->setUseFolderPerContext(true),
                self::CONFIG_ROOT_PATH . '/Production/Staging/.my.cnf'
            ],
        ];
    }
}
