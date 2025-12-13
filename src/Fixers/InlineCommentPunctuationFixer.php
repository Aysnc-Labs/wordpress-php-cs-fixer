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
 * Fixer for inline comment punctuation.
 *
 * Ensures that inline comments (// style) end with proper punctuation.
 */
final class InlineCommentPunctuationFixer extends AbstractFixer {
	public function getDefinition(): FixerDefinitionInterface {
		return new FixerDefinition(
			'Ensures inline comments end with proper punctuation (period, question mark, or exclamation mark).',
			[
				new CodeSample(
					"<?php\n// This is a comment\n\$var = 1;\n",
				),
			],
		);
	}

	public function isCandidate( Tokens $tokens ): bool {
		return $tokens->isTokenKindFound( T_COMMENT );
	}

	public function getPriority(): int {
		// Run after other comment-related fixers.
		return 0;
	}

	public function getName(): string {
		return 'Aysnc/inline_comment_punctuation';
	}

	protected function applyFix( SplFileInfo $file, Tokens $tokens ): void {
		for ( $i = 0; $i < $tokens->count(); ++$i ) {
			if ( ! $tokens[ $i ]->isGivenKind( T_COMMENT ) ) {
				continue;
			}

			$content = $tokens[ $i ]->getContent();

			// Only process inline comments (// style), not block comments (/* style).
			if ( ! str_starts_with( $content, '//' ) ) {
				continue;
			}

			// Get the comment text without the // prefix and trim whitespace.
			$commentText = trim( substr( $content, 2 ) );

			// Skip empty comments.
			if ( '' === $commentText ) {
				continue;
			}

			// Get the last character of the comment.
			$lastChar = substr( $commentText, -1 );

			// Check if it already ends with proper punctuation.
			if ( in_array( $lastChar, [ '.', '!', '?', ':', ';' ], true ) ) {
				continue;
			}

			// Add a period at the end.
			$newContent   = rtrim( $content );
			$tokens[ $i ] = new Token( [ T_COMMENT, $newContent . '.' ] );
		}
	}
}
