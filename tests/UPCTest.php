<?php
include(dirname(__FILE__). "/../classes/UPC.php");
use PHPUnit\Framework\TestCase;
use classes\UPC as TestClass;

class UPCTest extends TestCase
{
	/**
	 * @dataProvider validUPCProvider
	 */
	public function testvalidUPC($valid_upc, $number_system, $manufacturer_code, $product_code)
	{
		$class = new TestClass();
		$class->setUPC($valid_upc);
		$this->assertSame($class->getUPC(), $valid_upc);
		$this->assertSame($class->getNumberSystem(), $number_system);
		$this->assertSame($class->getManufacturerCode(), $manufacturer_code);
		$this->assertSame($class->getProductCode(), $product_code);
	}

	/**
	 *
	 */
	public function atestInvalidUPC()
	{
		$class = new TestClass();
		$class->validateUPC('A');
		$this->expectException(\InvalidArgumentException::class);
	}


	/** providers **/

	public function invalidTypeProvider()
	{
		return [
			['A'],
			[new \stdClass()],
			['12544Afd566'],
		];
	}

	public function validUPCProvider()
	{
		return [
			['042100005264', '0', '42100', '00526', '4'],
			['819921013521', '8', '19921', '01352', '1'],
			['811196011769', '8', '11196', '01176', '9'],
		];
	}
}
