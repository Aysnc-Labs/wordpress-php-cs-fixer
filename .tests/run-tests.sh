#!/bin/bash
set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"
FIXTURES_DIR="$SCRIPT_DIR/fixtures"

echo "Running PHP CS Fixer tests..."

# Create a temp file from before.php
cp "$FIXTURES_DIR/before.php" "$FIXTURES_DIR/test-output.php"

# Run the fixer on it
"$PROJECT_DIR/vendor/bin/php-cs-fixer" fix "$FIXTURES_DIR/test-output.php" --config="$PROJECT_DIR/.php-cs-fixer.dist.php" --quiet

# Compare with expected output
if diff -q "$FIXTURES_DIR/test-output.php" "$FIXTURES_DIR/after.php" > /dev/null 2>&1; then
	echo "✓ All tests passed!"
	rm "$FIXTURES_DIR/test-output.php"
	exit 0
else
	echo "✗ Test failed! Differences found:"
	diff "$FIXTURES_DIR/test-output.php" "$FIXTURES_DIR/after.php" || true
	rm "$FIXTURES_DIR/test-output.php"
	exit 1
fi
