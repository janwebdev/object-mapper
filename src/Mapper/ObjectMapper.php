<?php

declare(strict_types=1);

namespace Janwebdev\ObjectMapper\Mapper;

use Janwebdev\ObjectMapper\Parser\ParserInterface;
use Janwebdev\ObjectMapper\Converter\ConverterInterface;
use Laminas\Hydrator\ReflectionHydrator;

class ObjectMapper implements MapperInterface
{
	private ConverterInterface $converter;
	private ParserInterface $parser;
	private ReflectionHydrator $reflectionHydrator;

	public function __construct(ConverterInterface $converter, ParserInterface $parser, ReflectionHydrator $reflectionHydrator)
	{
		$this->converter = $converter;
		$this->parser = $parser;
		$this->reflectionHydrator = $reflectionHydrator;
	}

	/**
     * @throws \ReflectionException
     */
    public function __invoke(array $data, string $className)
    {
        $refClass = new \ReflectionClass($className);

        return $this->reflectionHydrator->hydrate(($this->converter)($data, ($this->parser)($className)), $refClass->newInstanceWithoutConstructor());
    }
}
