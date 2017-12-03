<?php

/**
 * @author  Masaya Takeda <844196@gmail.com>
 * @license MIT
 */

declare(strict_types=1);

namespace App\Procedure;

/**
 * AddProcedure
 */
class AddProcedure
{
	/**
	 * @param int $a
	 * @param int $b
	 * @return int
	 */
	public function __invoke(int $a, int $b): int
	{
		return $a + $b;
	}
}
