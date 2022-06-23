<?php

declare(strict_types=1);

namespace Janwebdev\ObjectMapper\Mapper;

use Janwebdev\ObjectMapper\Parser\Parser;
use Janwebdev\ObjectMapper\Parser\ParserInterface;
use Janwebdev\ObjectMapper\Converter\Converter;
use Janwebdev\ObjectMapper\Resolver\Resolver;
use Janwebdev\ObjectMapper\Converter\TypeCaster\ArrayTypeCaster;
use Janwebdev\ObjectMapper\Converter\TypeCaster\ClassTypeCaster;
use Janwebdev\ObjectMapper\Converter\TypeCaster\DateTimeCaster;
use Janwebdev\ObjectMapper\Converter\TypeCaster\ScalarTypeCaster;
use Laminas\Hydrator\ReflectionHydrator;

class ObjectMapperFactory
{
    private $typeCasters = [
        ScalarTypeCaster::class,
        DateTimeCaster::class,
        ArrayTypeCaster::class,
    ];

    public function __invoke(?ParserInterface $parser = null): ObjectMapper
    {
        if (null === $parser) {
            $parser = new Parser();
        }

        $resolver = new Resolver();
        $hydrator = new ReflectionHydrator();

        foreach ($this->typeCasters as $typeCaster) {
            $resolver->addTypeCaster(new $typeCaster());
        }

        $resolver->addTypeCaster(new ClassTypeCaster($resolver, $parser, $hydrator));
        $converter = new Converter($resolver);
        return new ObjectMapper($converter, $parser, $hydrator);
    }
}
