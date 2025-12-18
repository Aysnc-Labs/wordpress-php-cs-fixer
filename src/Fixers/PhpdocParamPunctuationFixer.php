<?php

namespace Aysnc\WordPress\PHPCSFixer\Fixers;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

/**
 * Fixer for PHPDoc parameter comment punctuation.
 *
 * Ensures that @param, @return, @throws, and @var descriptions end with proper punctuation.
 */
final class PhpdocParamPunctuationFixer extends AbstractFixer {
	public function getDefinition(): FixerDefinitionInterface {
		return new FixerDefinition(
			'Ensures PHPDoc tag descriptions (@param, @return, @throws, @var) end with proper punctuation.',
			[
				new CodeSample(
					"<?php\n/**\n * @param string \$name The name\n */\nfunction foo(\$name) {}\n",
				),
			],
		);
	}

	public function isCandidate( Tokens $tokens ): bool {
		return $tokens->isTokenKindFound( T_DOC_COMMENT );
	}

	public function getPriority(): int {
		// Run after other PHPDoc fixers.
		return -10;
	}

	public function getName(): string {
		return 'Aysnc/phpdoc_param_punctuation';
	}

	protected function applyFix( SplFileInfo $file, Tokens $tokens ): void {
		for ( $i = 0; $i < $tokens->count(); ++$i ) {
			if ( ! $tokens[ $i ]->isGivenKind( T_DOC_COMMENT ) ) {
				continue;
			}

			$content = $tokens[ $i ]->getContent();
			$lines   = explode( "\n", $content );
			$updated = false;

			foreach ( $lines as $lineIndex => $line ) {
				// Match @param, @return, @throws, @var tags.
				if ( ! preg_match( '/^(\s*\*\s*@(param|return|throws|var)\s+)(.+)$/u', $line, $matches ) ) {
					continue;
				}

				$prefix = $matches[1];
				$tag    = $matches[2];
				$rest   = rtrim( $matches[3] );

				// For @param and @var: expect "Type $var Description" or "Type Description".
				// For @return and @throws: expect "Type Description".
				// We need to extract the description part only.
				$desc       = null;
				$typeAndVar = null;

				if ( 'param' === $tag || 'var' === $tag ) {
					// Pattern: Type $variable Description.
					// or just: Type $variable (no description - skip).
					if ( preg_match( '/^(\S+\s+\$\S+)\s+(.+)$/u', $rest, $descMatches ) ) {
						$typeAndVar = $descMatches[1];
						$desc       = $descMatches[2];
					} else {
						// No description, just type and variable - skip.
						continue;
					}
				} elseif ( 'return' === $tag || 'throws' === $tag ) {
					// Pattern: Type Description.
					// or just: Type (no description - skip).
					if ( preg_match( '/^(\S+)\s+(.+)$/u', $rest, $descMatches ) ) {
						$typeAndVar = $descMatches[1];
						$desc       = $descMatches[2];
					} else {
						// No description, just type - skip.
						continue;
					}
				}

				// Skip if no description found.
				if ( null === $desc || '' === trim( $desc ) ) {
					continue;
				}

				// Trim the description.
				$desc = rtrim( $desc );

				// Get last character.
				$lastChar = substr( $desc, -1 );

				// Check if already ends with punctuation.
				if ( in_array( $lastChar, [ '.', '!', '?', ':', ';' ], true ) ) {
					continue;
				}

				// Add period.
				$newLine             = $prefix . $typeAndVar . ' ' . $desc . '.';
				$lines[ $lineIndex ] = $newLine;
				$updated             = true;
			}

			if ( $updated ) {
				$newContent   = implode( "\n", $lines );
				$tokens[ $i ] = new Token( [ T_DOC_COMMENT, $newContent ] );
			}
		}
	}
}
