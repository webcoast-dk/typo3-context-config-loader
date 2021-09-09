# TYPO3 configuration loader

A loader to make it easy to include context specific additional configuration files
inside `typo3conf/AdditionalConfiguration.php`.

This loader can
* load additional configuration from the current root and sub context
* load local configuration files containing credentials
* load a default configuration with non-standard settings (which would be removed from `LocalConfiguration.php` on each write)
* read database credentials from a `.my.cnf` file.

## Installation
Require via composer
```bash
composer req webcoast/typo3-config-loader
```

## Usage
Put this inside your `typo3conf/AdditionalConfiguration.php`
```php
(new \WEBcoast\Typo3ConfigLoader\Loader())->load();
```
This will automatically include configuration with standard settings (see below).

To adjust the behavior call the according setter function on the loader instance, e.g.
```php
(new \WEBcoast\Typo3ConfigLoader\Loader())
    ->setIncludeDefaultConfiguration(true)
    ->setUseFolderPerContext(true)
    ->setIncludeMyCnf(true)
    ->setConfigFileSuffix('AdditionalConfiguration')
    ->load();
```

This loader does not parse different configuration formats like PHP, XML or YAML. It just `require`s the PHP files
for the different context. The only file that is actually parsed is the `.my.cnf` file, if enabled. This gives
you the freedom to do whatever you want inside those PHP files.

### Order of inclusion
1. `Default.php` (if enabled)
2. `.my.cnf` (if enabled; parent context is applied first, if inheritance is enabled)
3. Context specific PHP files (parent context is applied first, if inheritance is enabled)
4. Local context specific PHP files (if enabled; parent context is applied first, if inheritance is enabled)

## Settings
| Setter method | Type | Default | Description |
| ------------- | ---- | ------- | ----------- |
| setInheritFromParentContext | boolean | true | If enabled the configuration for the root context (e.g. `Production`) would be included before the configuration from the current sub context (e.g. `Production/Staging`). If there is no sub context, the root context configuration will be always included. |
| setIncludeDefaultConfiguration | boolean | false | If enabled the file `{configRootPath}/Default.php` will be included before any other configuration. This could be helpful to have non-standard settngs, which should be included in each context. Non-standard settings will be removed from `LocalConfiguration.php`, meaning they need to live either in your `typo3conf/AdditionalConfiguration.php` or on this `Default` configuration file. |
| setIncludeLocalConfigurationFiles | boolean | false | If enabled files with a `Local` suffix will be included. The should be used to include files that only exist on the target server and are not part of your repository, because they contain credentials (database, SMTP, external API). This allows for versioned and unversioned setting for each context. |
| setIncludeMyCnf | boolean | false | If enabled a `.my.cnf` style file (INI) is parsed to get the database credentials. Some sys-admins prefer those `.my.cnf` files to store the database credentials. They are also used, when accessing the database through the command line MySQL client. |
| setSubContextSeparator | string: `.`, `-`, `_` or empty | `.` | Adjust this to match your naming of your configuration files, e.g. `Production/Staging` would become `Production.Staging.php` or `Production_Staging.php` depending on this setting. |
| setConfigFileSuffix | string | `empty` | If you want your configuration files look like `ProductionConfig.php`, set this to `Config`. This is required, when you use folders per context. |
| setUseFolderPerContext | bool | false | Instead of putting all configuration files in the configuration root folder, folder are used for the different contexts, e.g. `Production` would be `{configRoot}/Production` and `Production/Staging` woudl be `{configRoot}/Production/Staging`. The config file suffix is used as the file name, e.g. `Production/Staging/Config.php`. If usage of local configuration files is enabled, they would look like `Production/Staging/LocalConfig.php`. |

## License
See the [license file](LICENSE).

## Contribution
Open an issue or fork and provide a pull request.
