<?php

/**
 * @author  Masaya Takeda <844196@gmail.com>
 * @license MIT
 */

declare(strict_types=1);

namespace App\Procedure;

use Datto\JsonRpc\Evaluator;
use Datto\JsonRpc\Exception\Argument;
use Datto\JsonRpc\Exception\Method;

/**
 * ProcedureHandler
 */
class ProcedureHandler implements Evaluator
{
	/**
	 * @var array [methodName => procedureName, ...]
	 */
	private $procedures = [];

	/**
	 * {@inheritdoc}
	 */
	public function evaluate($methodName, $givenParams)
	{
		$procedureName = $this->procedures[$methodName] ?? null;
		if (is_null($procedureName)) {
			throw new Method();
		}

		// TODO m-takeda: support DI container
		$procedure = new $procedureName;
		$resolvedParams = $this->resolveParam(
			$givenParams,
			new \ReflectionMethod($procedure, '__invoke')
		);

		return $procedure->__invoke(...$resolvedParams);
	}

	/**
	 * @param string $methodName
	 * @param string $procedureName
	 * @return self
	 */
	public function with(string $methodName, string $procedureName): self
	{
		// TODO m-takeda: support closure and anonymous class
		$this->procedures[$methodName] = $procedureName;
		return $this;
	}

	private function resolveParam(array $givenParams, \ReflectionMethod $procedure): array
	{
		if (empty($procedure->getParameters())) {
			return [];
		}

		if ($procedure->getNumberOfRequiredParameters() <= count($givenParams)
			and $givenParams === array_values($givenParams)
		) {
			return $givenParams;
		}

		$resolvedParams = [];
		foreach ($procedure->getParameters() as $procedureParam) {
			// TODO m-takeda: support named default value
			if (false === array_key_exists($procedureParam->name, $givenParams)) {
				throw new Argument();
			}
			$resolvedParams[] = $givenParams[$procedureParam->name];
		}

		return $resolvedParams;
	}
}
