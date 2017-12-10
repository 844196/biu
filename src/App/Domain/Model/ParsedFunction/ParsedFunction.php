<?php

/**
 * @author  Masaya Takeda <844196@gmail.com>
 * @license MIT
 */

declare(strict_types=1);

namespace App\Domain\Model\ParsedFunction;

/**
 * ParsedFunction
 */
class ParsedFunction
{
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var Parameter[]
	 */
	private $params;

	/**
	 * @var ValueType
	 */
	private $returnValueType;

	/**
	 * @param string      $name
	 * @param Parameter[] $params
	 * @param ValueType   $returnValueType
	 */
	public function __construct(
		string $name,
		array $params,
		ValueType $returnValueType
	) {
		$this->name = $name;
		$this->params = $params;
		$this->returnValueType = $returnValueType;
	}

	/**
	 * @return string
	 */
	public function name(): string
	{
		return $this->name;
	}

	/**
	 * @return Parameter[]
	 */
	public function params(): array
	{
		return $this->params;
	}

	/**
	 * @return ValueType
	 */
	public function returnValueType(): ValueType
	{
		return $this->returnValueType;
	}
}
