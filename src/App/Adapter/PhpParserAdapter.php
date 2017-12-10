<?php

/**
 * @author  Masaya Takeda <844196@gmail.com>
 * @license MIT
 */

declare(strict_types=1);

namespace App\Adapter;

use PhpParser\NodeTraverser;
use PhpParser\Node\FunctionLike;
use PhpParser\Parser as PhpParser;

/**
 * PhpParserAdapter
 */
class PhpParserAdapter
{
	/**
	 * @var PhpParser
	 */
	private $phpParser;

	/**
	 * @param PhpParser $phpParser
	 */
	public function __construct(PhpParser $phpParser)
	{
		$this->phpParser = $phpParser;
	}

	/**
	 * @param string $fileName
	 * @param int    $lineNo
	 * @throws \RuntimeException
	 * @return FunctionLike
	 */
	public function parseFunctionByFileNameAndLineNo(string $fileName, int $lineNo): FunctionLike
	{
		$stmts = $this
			->phpParser
			->parse(file_get_contents($fileName));

		$visitor = new FunctionLikeVisitor();
		$traverser = new NodeTraverser();
		$traverser->addVisitor($visitor);
		$traverser->traverse($stmts);

		$parsed = null;
		foreach ($visitor->functions() as $function) {
			if ($lineNo >= $function->getAttribute('startLine')
				and $lineNo <= $function->getAttribute('endLine')
			) {
				$parsed = $function;
				break;
			}
		}

		if (is_null($parsed)) {
			throw new \RuntimeException('function parse failed');
		}

		return $parsed;
	}
}
