<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\PropertyInfo\Type;

class ArticleQueryFilter extends AbstractFilter
{

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        if ($property !== 'query') {
            return;
        }

        $alias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->andWhere(sprintf('%s.title LIKE :query OR %s.description LIKE :query', $alias, $alias))
            ->setParameter('query', '%'.$value.'%');
    }

    public function getDescription(string $resourceClass): array
    {
        return [
            'query' => [
                'property' => 'query',
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => false,
                'description' => 'Filter by title or description',
                'openapi' => [
                    'allowReserved' => false,
                    'allowEmptyValue' => true,
                    'explode' => false,
                ]
            ]
        ];
    }
}