<?php

declare(strict_types=1);

namespace Janwebdev\ObjectMapper\Parser;

interface ParserInterface
{
    /**
     * @throws ParserException
     */
    public function __invoke(string $className);
}
