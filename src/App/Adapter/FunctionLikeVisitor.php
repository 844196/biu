<?php

/**
 * @author  Masaya Takeda <844196@gmail.com>
 * @license MIT
 */

declare(strict_types=1);

namespace App\Adapter;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\FunctionLike;

/**
 * FunctionLikeVisitor
 */
class FunctionLikeVisitor extends NodeVisitorAbstract
{
	/**
	 * @var FunctionLike[]
	 */
	private $functions = [];

	/**
	 * {@inheritdoc}
	 */
	public function leaveNode(Node $node): void
	{
		if ($node instanceof FunctionLike) {
			$this->functions[] = $node;
		}
	}

	/**
	 * @return FunctionLike[]
	 */
	public function functions(): array
	{
		return $this->functions;
	}
}
