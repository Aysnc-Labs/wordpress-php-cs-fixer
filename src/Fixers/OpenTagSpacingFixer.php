<?php

namespace Aysnc\WordPressPHPCSFixer\Fixers;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

/**
 * Fixer for spacing after opening PHP tag.
 *
 * Rules:
 * - If docblock follows <?php, no blank line (single newline).
 * - If other code follows <?php, one blank line (two newlines).
 * - Normalizes multiple blank lines to the correct amount.
 */
final class OpenTagSpacingFixer extends AbstractFixer {
	public function getDefinition(): FixerDefinitionInterface {
		return new FixerDefinition(
			'Controls blank lines after opening PHP tag. No blank line before docblocks, one blank line before other code.',
			[
				new CodeSample(
					"<?php\n\n\n/**\n * File docblock.\n */\nnamespace Test;\n",
				),
				new CodeSample(
					"<?php\nnamespace Test;\n",
				),
			],
		);
	}

	public function isCandidate( Tokens $tokens ): bool {
		return $tokens->isTokenKindFound( T_OPEN_TAG );
	}

	public function getPriority(): int {
		// Run early, before other formatting rules.
		return 10;
	}

	public function getName(): string {
		return 'Aysnc/open_tag_spacing';
	}

	protected function applyFix( SplFileInfo $file, Tokens $tokens ): void {
		// Find the opening PHP tag (should be at index 0 for most files).
		$openTagIndex = null;

		for ( $i = 0; $i < $tokens->count(); ++$i ) {
			if ( $tokens[ $i ]->isGivenKind( T_OPEN_TAG ) ) {
				$openTagIndex = $i;
				break;
			}
		}

		if ( null === $openTagIndex ) {
			return;
		}

		$this->fixOpenTagSpacing( $tokens, $openTagIndex );
	}

	private function fixOpenTagSpacing( Tokens $tokens, int $openTagIndex ): void {
		$openTagContent = $tokens[ $openTagIndex ]->getContent();

		// Check if open tag already has a newline (<?php\n vs <?php ).
		$openTagHasNewline = str_ends_with( $openTagContent, "\n" );

		// Find the next non-whitespace token.
		$nextIndex = $openTagIndex + 1;

		if ( ! isset( $tokens[ $nextIndex ] ) ) {
			return; // Empty file after open tag.
		}

		// Get the next meaningful token (skip whitespace).
		$nextMeaningfulIndex = $tokens->getNextNonWhitespace( $openTagIndex );

		if ( null === $nextMeaningfulIndex ) {
			return; // Only whitespace after open tag.
		}

		$nextToken = $tokens[ $nextMeaningfulIndex ];

		// Determine if next token is a docblock.
		$isDocblock = $nextToken->isGivenKind( T_DOC_COMMENT );

		// Determine desired spacing.
		// - Docblock: single newline (no blank line).
		// - Other code: double newline (one blank line).
		$desiredWhitespace = $isDocblock ? "\n" : "\n\n";

		// If open tag already has newline, adjust desired whitespace.
		if ( $openTagHasNewline ) {
			// Open tag is "<?php\n", so we need to set whitespace after it.
			if ( $tokens[ $nextIndex ]->isWhitespace() ) {
				// There's whitespace after open tag, fix it.
				$currentWhitespace = $tokens[ $nextIndex ]->getContent();

				// For docblock: we want just the open tag newline, no extra whitespace.
				// For other code: we want one additional newline.
				if ( $isDocblock ) {
					// Remove any whitespace between open tag and docblock.
					if ( '' !== $currentWhitespace && "\n" !== $currentWhitespace ) {
						$tokens->clearAt( $nextIndex );
					}
				} else {
					// Ensure exactly one blank line (open tag has \n, we add \n for blank line).
					$newlineCount = substr_count( $currentWhitespace, "\n" );

					if ( 1 !== $newlineCount ) {
						$tokens[ $nextIndex ] = new Token( [ T_WHITESPACE, "\n" ] );
					}
				}
			} else {
				// No whitespace after open tag, but open tag has newline.
				if ( ! $isDocblock ) {
					// Insert a blank line before non-docblock code.
					$tokens->insertAt( $nextIndex, new Token( [ T_WHITESPACE, "\n" ] ) );
				}
				// For docblock, no action needed - open tag newline is sufficient.
			}
		} else {
			// Open tag is "<?php " (with space or no newline).
			// Normalize to have proper newlines.
			if ( $tokens[ $nextIndex ]->isWhitespace() ) {
				$tokens[ $nextIndex ] = new Token( [ T_WHITESPACE, $desiredWhitespace ] );
			} else {
				// Insert whitespace.
				$tokens->insertAt( $nextIndex, new Token( [ T_WHITESPACE, $desiredWhitespace ] ) );
			}

			// Also fix the open tag to have newline.
			$tokens[ $openTagIndex ] = new Token( [ T_OPEN_TAG, "<?php\n" ] );
		}
	}
}
