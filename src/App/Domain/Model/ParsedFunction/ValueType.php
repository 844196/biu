<?php

/**
 * @author  Masaya Takeda <844196@gmail.com>
 * @license MIT
 */

declare(strict_types=1);

namespace App\Domain\Model\ParsedFunction;

use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;

/**
 * ValueType
 */
class ValueType
{
	/**
	 * @var string
	 */
	private $typeStr;

	/**
	 * @var bool
	 */
	private $nullable;

	/**
	 * @param string $typeStr
	 * @param bool   $nullable
	 */
	private function __construct(string $typeStr, bool $nullable)
	{
		$this->typeStr = $typeStr;
		$this->nullable = $nullable;
	}

	/**
	 * @param string|Nmae|NullableType|null $type
	 * @return self
	 */
	public static function of($type): self
	{
		$typeStr = function ($type) use (&$typeStr): string {
			switch (true) {
				case is_string($type):
					return $type;
				case $type instanceof Name:
					return ($type instanceof FullyQualified ? '\\' : '') . $type->toString();
				case $type instanceof NullableType:
					return $typeStr->__invoke($type->type);
				default:
					return 'mixed';
			}
		};

		return new self($typeStr->__invoke($type), $type instanceof NullableType);
	}

	/**
	 * @return string
	 */
	public function typeStr(): string
	{
		return $this->typeStr;
	}

	/**
	 * @return bool
	 */
	public function nullable(): bool
	{
		return $this->nullable;
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->typeStr . ($this->nullable ? '|null' : '');
	}
}
