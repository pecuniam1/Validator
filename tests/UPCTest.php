<?php
include(dirname(__FILE__). "/../classes/UPC.php");
use PHPUnit\Framework\TestCase;
use classes\UPC as TestClass;

class UPCTest extends TestCase
{
	/**
	 * @dataProvider validUPCProvider
	 */
	public function testvalidUPC($valid_upc, $number_system, $manufacturer_code, $product_code, $checksum)
	{
		$class = new TestClass();
		$class->setUPC($valid_upc);
		$this->assertSame($class->getUPC(), $valid_upc);
		$this->assertSame($class->getNumberSystem(), $number_system);
		$this->assertSame($class->getManufacturerCode(), $manufacturer_code);
		$this->assertSame($class->getProductCode(), $product_code);
		$this->assertSame(substr($valid_upc, -1), $checksum);
	}
	
	/**
	 * @dataProvider validProductType
	 */
	public function testProductType($product_code, $description)
	{
		$class = new TestClass();
		$this->assertSame($class->getProductType($product_code), $description);
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

	public function validProductType()
	{
		return [
			[0, "Product"],
			[1, "Product"],
			[2, "Variable weight item"],
			[3, "Pharmaceutical"],
			[4, "Local Use"],
			[5, "Coupon"],
			[6, "Product"],
			[7, "Product"],
			[8, "Product"],
			[9, "Product"],
		];
	}

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
