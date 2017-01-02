# APCu Utils

A set of utility functions for the APCu PHP extension.

### Installation

Using Composer, just add it to your `composer.json` by running:

```
composer require mjaschen/php-apcu-utils
```

### Compatibility

The APCu Utils require PHP 7.0.

### Usage/Examples

```php
<?php

use MarcusJaschen\APCu;

printf('APCu fragmentation is %.1f%%', getFragmentationRatio());
```
