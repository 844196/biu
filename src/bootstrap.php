<?php

/**
 * @author  Masaya Takeda <844196@gmail.com>
 * @license MIT
 */

declare(strict_types=1);

use DI\ContainerBuilder;

const APPROOT = __DIR__ . '/..';

require APPROOT . '/vendor/autoload.php';

return (new ContainerBuilder())
	->addDefinitions(__DIR__ . '/dependency.php')
	->build();
