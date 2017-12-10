<?php

/**
 * @author  Masaya Takeda <844196@gmail.com>
 * @license MIT
 */

declare(strict_types=1);

namespace App\Domain\Model\ParsedFunction;

/**
 * Parameter
 *
 * TODO m-takeda: support default value type
 */
class Parameter
{
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var ValueType
	 */
	private $valueType;

	/**
	 * @var bool
	 */
	private $variadic;

	/**
	 * @param string    $name
	 * @param ValueType $valueType
	 * @param bool      $variadic
	 */
	public function __construct(
		string $name,
		ValueType $valueType,
		bool $variadic
	) {
		$this->name = $name;
		$this->valueType = $valueType;
		$this->variadic = $variadic;
	}

	/**
	 * @return string
	 */
	public function name(): string
	{
		return $this->name;
	}

	/**
	 * @return ValueType
	 */
	public function valueType(): ValueType
	{
		return $this->valueType;
	}

	/**
	 * @return bool
	 */
	public function variadic(): bool
	{
		return $this->variadic;
	}

	/**
	 * @return string
	 */
	public function typeAsString(): string
	{
		$typeStr = $this->valueType->toString();

		$isMultiple = false !== strpos($typeStr, '|');
		if ($this->variadic) {
			$typeStr = $isMultiple ? "({$typeStr})[]" : "{$typeStr}[]";
		}

		return $typeStr;
	}
}
