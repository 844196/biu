<?php

/**
 * @author  Masaya Takeda <844196@gmail.com>
 * @license MIT
 */

declare(strict_types=1);

return [
	\PhpParser\Parser::class => function () {
		return (new \PhpParser\ParserFactory())->create(\PhpParser\PhpParserFactory::PREFER_PHP7);
	},
];
