<?php

use Aysnc\WordPress\PHPCSFixer\Config;
use PhpCsFixer\Finder;

require_once __DIR__ . '/vendor/autoload.php';

$finder = Finder::create()
	->in( __DIR__ )
	->name( '*.php' )
	->ignoreVCS( true )
	->exclude( 'vendor' );

return Config::create()
	->setFinder( $finder );
