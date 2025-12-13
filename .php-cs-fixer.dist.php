<?php

use Aysnc\WordPressPHPCSFixer\Config;
use PhpCsFixer\Finder;

require_once __DIR__ . '/vendor/autoload.php';

$finder = Finder::create()
	->in( dirname( __DIR__ ) )
	->name( '*.php' )
	->ignoreVCS( true )
	->exclude( 'vendor' );

return Config::create()
	->setFinder( $finder )
	->setRiskyAllowed( true )
	->setIndent( "\t" )
	->setLineEnding( "\n" )
	->setParallelConfig( PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect() )
	->setRules( Config::getRules() );
