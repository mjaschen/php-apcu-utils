# APCu Utils

A set of utility functions for the APCu PHP extension.

### Installation

Using Composer, just add it to your `composer.json` by running:

```
composer require mjaschen/php-apcu-utils
```

### Compatibility

The APCu Utils require PHP >= 7.3.

### Usage/Examples

```php
<?php

use MarcusJaschen\APCu\ApcuUtils;

printf('APCu fragmentation is %.1f%%', ApcuUtils::getFragmentationRatio());
```
