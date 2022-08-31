# Y1 Magento2 Manage PageBuilder Status

This module allows an admin to turn the pagebuilder on/off for specific CMS blocks and pages. When turned off, TinyMCE is used.

## Requirements

- PHP 7.4, 8.1
- Magento >= 2.4.4

Generally the last full release -1 version is tested and made to work. Older versions are not actively supported.

# Installation
```
composer require y1/magento2-module-manage-pagebuilder-status
bin/magento module:enable Y1_ManagePagebuilderStatus
bin/magento setup:upgrade
```

## How to use

Set the option "Is Pagebuilder Enabled" and save, reloading the page. The new editor will be loaded.

## License

[Open Software Licence 3.0 (OSL-3.0)](https://opensource.org/licenses/osl-3.0.php)