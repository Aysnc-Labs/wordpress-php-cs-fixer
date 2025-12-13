<?php


/**
 * Description goes here
 * @package somepackage
 */
namespace Test;
use SomeNamespace\SomeMissingClass;
use const SomeNamespace\MISSING_CONST;
use const SomeNamespace\CONST_1;
use function SomeNamespace\function_2;
use SomeNamespace\SomeClass3;
use const SomeNamespace\CONST_2;
use function SomeNamespace\missing_function;
use function SomeNamespace\function_1;

class ExampleClass
{
    private $items = [];

	public function __construct($items)
	{
		$this->items = $items;
	}

	public function getItems()
	{
		return $this->items;
	}

	public function processData($data   )
	{
        // Array without spaces inside brackets.
		$array = [   'foo', 'bar','baz'];

		// Array access examples.
		$value = $data['key'];
		$dynamic = $data[$variable ];
		$class_value= new \SomeNamespace\SomeClass( );

		// Non-Yoda condition.
		if ($value == 'test') {
			return true;
		}

		// Double quotes.
		$string  = "hello world" ;

		// Multiline array without trailing comma.
		$config = [
			'key1' => 'value1',
			'key2' => 'value2',
			'longkey3' => 'value2'
		];

		// Function call with multiple args.
		$result = some_function($arg1, $arg2, $arg3);

		foreach ($array as $item ) {
			echo $item;
		}

		// Use statements.
		function_1();
		function_2();
		new \SomeNamespace\SomeClass3();

		return $result;
	}
}

/**
 * Standalone function
 * @param \SomeNamespace\SomeClass2 $param1 Param 1
 * @param int $param2 Param 2
 * @return int
 */
function standalone_function($param1, $param2)
{
	if ($param1 != null) {
		return $param1;
	}

	if (
		$param1 instanceof \SomeNamespace\SomeClass2 ||
		$param2 > 10
	) {
		return 0;
	}

	return $param2;
}
