# WordPress PHP CS Fixer

![GitHub Actions](https://github.com/Aysnc-Labs/wordpress-php-cs-fixer/actions/workflows/test.yml/badge.svg)
![Maintenance](https://img.shields.io/badge/Actively%20Maintained-yes-green.svg)

A modern PHP CS Fixer configuration for WordPress development. Maintains WordPress coding standards (tabs, Yoda conditions, spacing) while modernizing with short arrays, trailing commas, and strict comparisons.

## Installation

```bash
composer require --dev aysnc/wordpress-php-cs-fixer
```

## Usage

Create `.php-cs-fixer.dist.php` in your project root:

```php
<?php

use Aysnc\WordPress\PHPCSFixer\Config;
use PhpCsFixer\Finder;

require_once __DIR__ . '/vendor/autoload.php';

$finder = Finder::create()
	->in( __DIR__ )
	// Or specify multiple: ->in( [ __DIR__ . '/src', __DIR__ . '/tests' ] )
	->name( '*.php' )
	->ignoreVCS( true )
	->exclude( 'vendor' );

return Config::create()
	->setFinder( $finder );
```

Run the fixer:

```bash
# Check without fixing (dry-run)
vendor/bin/php-cs-fixer fix --dry-run --diff

# Fix files
vendor/bin/php-cs-fixer fix
```

## Customizing Rules

Override rules by spreading `Config::getRules()`:

```php
return Config::create()
	->setFinder( $finder )
	->setRules(
		[
			...Config::getRules(),
			'array_syntax' => [
				'syntax' => 'long',
			],
		],
	);
```

## WPCS Compatibility

If using alongside PHPCS with WordPress standards, and you want to override short array restrictions:

```xml
<!-- phpcs.xml.dist -->
<rule ref="WordPress">
	<exclude name="Universal.Arrays.DisallowShortArraySyntax"/>
	<exclude name="Generic.Arrays.DisallowShortArraySyntax"/>
</rule>
```
