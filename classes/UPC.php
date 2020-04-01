<?php
namespace classes;

$upc = new UPC();
$upc->setUPC('042100005264');

/**
 * A model class for UPC (Universal Product Code).
 */
class UPC
{
	/** @var array[string]int UPC_LENGTHS Valid UPC types and their respective sizes. */
	const UPC_LENGTHS = array(
		"UPC-A" => 12, // This number includes the checksum
		"UPC-E" => 6,
	);

	/** @var string A universal product code. */
	private $upc;

	/**
	 * Sets the UPC after testing validity.
	 *
	 * @param string $upc
	 * @return void
	 */
	public function setUPC(string $upc) : void
	{
		$this->upc = trim($upc);
		try {
			$this->validateUPC($upc);
		} catch (\Exception $ex) {
			echo $ex->getMessage();
			return;
		} catch (\Error $er) {
			echo $er->getMessage();
			return;
		}
		$this->upc = $upc;
	}

	/**
	 * Returns the UPC.
	 *
	 * @return string
	 */
	public function getUPC() : string
	{
		return $this->upc;
	}

	/**
	 * This function will validate the UPC.
	 * $this->upc will not be set unless it passes this validation.
	 *
	 * @param string $upc_code
	 * @return void
	 */
	public function validateUPC(string $upc) : void
	{
		if (!ctype_digit($upc)) {
			throw new \InvalidArgumentException("UPC can only contain the numbers 0-9.");
		}
		if (!array_keys(self::UPC_LENGTHS, strlen($upc))) {
			throw new \LengthException("UPC length is not valid.");
		}
		// only for UPC-A
		if (strlen($upc) === self::UPC_LENGTHS['UPC-A']) {
			if ($this->calculateChecksum($upc) != substr($upc, -1, 1)) {
				throw new \InvalidArgumentException("Either the UPC is not valid, or the checksum is incorrect.");
			}
		}
	}

	/**
	 * Calculates and returns the checksum.
	 * Checksums are only present on UPC-A.
	 *
	 * @return int
	 */
	public function calculateChecksum($upc) : int
	{
		$upc_array = str_split(substr($upc, 0, strlen($upc) - 1));
		$odd_numbers = array_sum(array_filter($upc_array, function ($key) {
			return ($key & 1);
		}, ARRAY_FILTER_USE_KEY));
		$even_numbers = 3 * array_sum(array_filter($upc_array, function ($key) {
			return !($key & 1);
		}, ARRAY_FILTER_USE_KEY));
		return (10 - (($odd_numbers + $even_numbers) % 10));
	}
	
	/**
	 * This function will return the type of product given the number system digit
	 * which is the first number of the UPC code.
	 *
	 * @param integer $nsc
	 * @return string The description of the product.
	 */
	public function getProductType(int $nsc) : string
	{
		switch ($nsc) {
			case 2:
				return "Variable weight item";
			break;
			case 3:
				return "Pharmaceutical";
			break;
			case 4:
				return "Local Use";
			break;
			case 5:
				return "Coupon";
			break;
			default:
				return "Product";
		}
	}
	
	/**
	 * Returns the number system digit, which is digit 0.
	 * The number system digit indicates what type of product it is.
	 *
	 * @return string
	 */
	public function getNumberSystem() : string
	{
		return substr($this->upc, 0, 1);
	}
	
	/**
	 * Returns the manufacturer code which are digits 1-5.
	 * The manufacturer code indicates the manufacturer of the product.
	 *
	 * @return string
	 */
	public function getManufacturerCode() : string
	{
		return substr($this->upc, 1, 5);
	}
	
	/**
	 * Returns the product code, which are digits 6-10.
	 * The product code is unique for each manufacturer code.
	 *
	 * @return string
	 */
	public function getProductCode() : string
	{
		return substr($this->upc, 6, 5);
	}
}
