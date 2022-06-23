<?php

declare(strict_types=1);

namespace Janwebdev\ObjectMapper\Converter\TypeCaster;

use Janwebdev\ObjectMapper\Parser\Parser;
use Janwebdev\ObjectMapper\Converter\Exception\TypeCasterException;
use Janwebdev\ObjectMapper\Resolver\Resolver;
use Laminas\Hydrator\ReflectionHydrator;

class ClassTypeCaster implements TypeCasterInterface
{
    private Resolver $resolver;
    private Parser $parser;
    private ReflectionHydrator $reflectionHydrator;

    public function __construct(Resolver $resolver, Parser $parser, ReflectionHydrator $reflectionHydrator)
    {
        $this->resolver = $resolver;
        $this->parser = $parser;
        $this->reflectionHydrator = $reflectionHydrator;
    }

    public function convert($value, string $type): object
    {
        if (!\is_array($value)) {
            throw new TypeCasterException('ClassTypeCaster is need for array value');
        }

        $refClass = new \ReflectionClass($type);
        $schemas = ($this->parser)($type);

        $results = [];

        foreach ($value as $property => $v) {
            $innerType = $schemas[$property]['type'];
            $results[$property] = $this->resolver->resolve($innerType)->convert($v, $innerType);
        }

        return $this->reflectionHydrator->hydrate($results, $refClass->newInstanceWithoutConstructor());
    }

    public function supports(string $type): bool
    {
        try {
	        return (new \ReflectionClass($type))->isUserDefined();
        } catch (\ReflectionException $e) {
            return false;
        }
    }
}
