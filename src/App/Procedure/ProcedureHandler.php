<?php

/**
 * @author  Masaya Takeda <844196@gmail.com>
 * @license MIT
 */

declare(strict_types=1);

namespace App\Procedure;

use DI\Container;
use Datto\JsonRpc\Evaluator;
use Datto\JsonRpc\Exception\Argument;
use Datto\JsonRpc\Exception\Method;

/**
 * ProcedureHandler
 */
class ProcedureHandler implements Evaluator
{
	/**
	 * @var Container
	 */
	private $container;

	/**
	 * @var array [methodName => procedureName, ...]
	 */
	private $procedures = [];

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * {@inheritdoc}
	 */
	public function evaluate($methodName, $givenParams)
	{
		$mappedProcedure = $this->procedures[$methodName] ?? null;
		if (is_null($mappedProcedure)) {
			throw new Method();
		}

		$procedure = $mappedProcedure;
		if (is_string($procedure)) {
			$procedure = $this
				->container
				->get($procedure);
		}

		$resolvedParams = $this->resolveParam(
			$givenParams,
			new \ReflectionMethod($procedure, '__invoke')
		);

		return $procedure->__invoke(...$resolvedParams);
	}

	/**
	 * @param string                 $methodName
	 * @param string|\Closure|object $procedure
	 * @return self
	 */
	public function with(string $methodName, $procedure): self
	{
		$this->procedures[$methodName] = $procedure;
		return $this;
	}

	/**
	 * @param array             $givenParams
	 * @param \ReflectionMethod $procedure
	 * @return string[]
	 */
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
