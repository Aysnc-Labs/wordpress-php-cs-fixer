<?php
/**
 * Description goes here.
 *
 * @package somepackage
 */

namespace Test;

use SomeNamespace\SomeClass;
use SomeNamespace\SomeClass2;

class ExampleClass {
	private $items = [];

	public function __construct( $items ) {
		$this->items = $items;
	}

	public function getItems() {
		return $this->items;
	}

	public function processData( $data ) {
		// Array without spaces inside brackets.
		$array = [ 'foo', 'bar', 'baz' ];

		// Array access examples.
		$value       = $data['key'];
		$dynamic     = $data[ $variable ];
		$class_value = new SomeClass();

		// Non-Yoda condition.
		if ( 'test' === $value ) {
			return true;
		}

		// Double quotes.
		$string = 'hello world';

		// Multiline array without trailing comma.
		$config = [
			'key1'     => 'value1',
			'key2'     => 'value2',
			'longkey3' => 'value2',
		];

		// Function call with multiple args.
		$result = some_function( $arg1, $arg2, $arg3 );

		foreach ( $array as $item ) {
			echo $item;
		}

		return $result;
	}
}

/**
 * Standalone function.
 *
 * @param SomeClass2 $param1 Param 1
 * @param int        $param2 Param 2
 *
 * @return int
 */
function standalone_function( $param1, $param2 ) {
	if ( null !== $param1 ) {
		return $param1;
	}

	if (
		$param1 instanceof SomeClass2
		|| 10 < $param2
	) {
		return 0;
	}

	return $param2;
}
