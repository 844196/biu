<?php

/**
 * @author  Masaya Takeda <844196@gmail.com>
 * @license MIT
 */

declare(strict_types=1);

use App\Procedure\ProcedureHandler;
use App\Procedure\AddProcedure;
use Datto\JsonRpc\Server;
use Streamer\Stream;

$container = require __DIR__ . '/../src/bootstrap.php';
$procedureHandler = (new ProcedureHandler($container))
	->with('add', AddProcedure::class);

$stdin = new Stream(STDIN);
$stdout = new Stream(STDOUT);

$reply = (new Server($procedureHandler))->reply($stdin->read());
$stdout->write($reply);
