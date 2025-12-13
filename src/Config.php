<?php

namespace Aysnc\WordPressPHPCSFixer;

use Aysnc\WordPressPHPCSFixer\Fixers\InlineCommentPunctuationFixer;
use Aysnc\WordPressPHPCSFixer\Fixers\OpenTagSpacingFixer;
use Aysnc\WordPressPHPCSFixer\Fixers\PhpdocParamPunctuationFixer;
use Aysnc\WordPressPHPCSFixer\Fixers\SpacesInsideArrayBracketsFixer;
use PhpCsFixer\Config as BaseConfig;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

/**
 * Helper class to easily create a PHP-CS-Fixer config with WordPress custom fixers.
 */
class Config {
	/**
	 * Create a new PHP-CS-Fixer config with WordPress custom fixers registered.
	 *
	 * @return BaseConfig
	 */
	public static function create(): BaseConfig {
		$config = new BaseConfig();
		$config->registerCustomFixers( self::getCustomFixers() );
		$config->setRiskyAllowed( true );
		$config->setIndent( "\t" );
		$config->setLineEnding( "\n" );
		$config->setParallelConfig( ParallelConfigFactory::detect() );
		$config->setRules( self::getRules() );

		return $config;
	}

	/**
	 * Get all custom fixers.
	 *
	 * @return array
	 */
	public static function getCustomFixers(): array {
		return [
			new InlineCommentPunctuationFixer(),
			new OpenTagSpacingFixer(),
			new PhpdocParamPunctuationFixer(),
			new SpacesInsideArrayBracketsFixer(),
		];
	}

	/**
	 * Get the default ruleset for WordPress coding standards.
	 *
	 * @return array
	 */
	public static function getRules(): array {
		return [
			// PSR-12 preset as base.
			'@PSR12' => true,

			// Override indentation to use tabs.
			'indentation_type' => true,

			// Declare statements with spaces around equals.
			'declare_equal_normalize' => [ 'space' => 'single' ],

			// No blank line after opening PHP tag.
			'blank_line_after_opening_tag' => false,

			// Custom rule: inline comment punctuation.
			'Aysnc/inline_comment_punctuation' => true,

			// Custom rule: open tag spacing (no blank line before docblock, one blank line before other code).
			'Aysnc/open_tag_spacing' => true,

			// Custom rule: PHPDoc param/return/throws/var punctuation.
			'Aysnc/phpdoc_param_punctuation' => true,

			// Custom rule: spaces inside array brackets.
			'Aysnc/spaces_inside_array_brackets' => true,

			// Import ordering.
			'ordered_imports' => [
				'sort_algorithm' => 'alpha',
				'imports_order'  => [ 'class', 'function', 'const' ],
			],

			// Force use statements instead of fully qualified names.
			'fully_qualified_strict_types' => [
				'import_symbols' => true,
			],
			'no_unused_imports'       => true,
			'global_namespace_import' => [
				'import_classes'   => true,
				'import_constants' => true,
				'import_functions' => true,
			],

			// Spaces inside parentheses.
			'spaces_inside_parentheses' => [ 'space' => 'single' ],

			// Class attributes separation.
			'class_attributes_separation' => [
				'elements' => [
					'method'   => 'one',
					'property' => 'one',
					'const'    => 'one',
				],
			],

			// Single line comment spacing.
			'single_line_comment_spacing' => true,

			// Nullable type declaration.
			'nullable_type_declaration_for_default_null_value' => true,

			// Add void return type to functions that don't return anything.
			'void_return' => true,

			// Array syntax.
			'array_syntax' => [
				'syntax' => 'short',
			],

			// Array indentation.
			'array_indentation' => true,

			// Binary operator spaces with alignment.
			'binary_operator_spaces' => [
				'default'   => 'single_space',
				'operators' => [
					'='  => 'align_single_space_minimal',
					'=>' => 'align_single_space_minimal',
				],
			],

			// Operators on new lines go at the beginning (left side).
			'operator_linebreak' => [
				'only_booleans' => false,
				'position'      => 'beginning',
			],

			// Trailing comma in multiline.
			'trailing_comma_in_multiline' => [
				'elements' => [ 'arrays', 'arguments', 'parameters' ],
			],

			// Concat space.
			'concat_space' => [
				'spacing' => 'one',
			],

			// PHPDoc line span.
			'phpdoc_line_span' => [
				'const'    => 'single',
				'property' => 'single',
				'method'   => 'multi',
			],

			// PHPDoc separation.
			'phpdoc_separation' => true,

			// PHPDoc alignment.
			'phpdoc_align' => [ 'align' => 'vertical' ],

			// PHPDoc indentation.
			'phpdoc_indent' => true,

			// No extra blank lines.
			'no_extra_blank_lines' => [
				'tokens' => [
					'extra',
					'use',
					'square_brace_block',
					'curly_brace_block',
					'return',
					'throw',
					'break',
					'continue',
				],
			],

			// No trailing whitespace.
			'no_trailing_whitespace'                     => true,
			'no_whitespace_before_comma_in_array'        => true,
			'no_space_around_double_colon'               => true,
			'no_singleline_whitespace_before_semicolons' => true,
			'whitespace_after_comma_in_array'            => [ 'ensure_single_space' => true ],

			// Enforce Yoda conditions.
			'yoda_style' => [
				'equal'            => true,
				'identical'        => true,
				'less_and_greater' => true,
			],

			// Use single quotes for strings.
			'single_quote' => true,

			// Enforce strict comparison operators.
			'strict_comparison' => true,

			// Enforce strict parameter typing.
			'strict_param' => true,

			// Use pre-increment/decrement operators.
			'increment_style' => [ 'style' => 'pre' ],

			// Method argument spacing - ensure fully multiline when there are newlines.
			'method_argument_space' => [
				'on_multiline'                     => 'ensure_fully_multiline',
				'keep_multiple_spaces_after_comma' => false,
			],

			// Disable statement indentation to allow proper indentation in template files.
			'statement_indentation' => false,

			// The @PSR12 preset already handles this correctly, but we ensure switch_case_space is enabled.
			'switch_case_space' => true,

			// WordPress style braces (same line).
			'curly_braces_position' => [
				'functions_opening_brace'           => 'same_line',
				'classes_opening_brace'             => 'same_line',
				'control_structures_opening_brace'  => 'same_line',
				'anonymous_functions_opening_brace' => 'same_line',
				'anonymous_classes_opening_brace'   => 'same_line',
			],
		];
	}
}
