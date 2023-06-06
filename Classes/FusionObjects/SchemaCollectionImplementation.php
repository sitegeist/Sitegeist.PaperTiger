<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\FusionObjects;

use Neos\Error\Messages\Result;
use Neos\Fusion\Form\Runtime\Domain\SchemaInterface;
use Neos\Fusion\FusionObjects\AbstractFusionObject;

class SchemaCollectionImplementation extends AbstractFusionObject implements SchemaInterface
{
    /**
     * @return SchemaInterface[]
     */
    protected function getSchemaMap(): array
    {
        $map = $this->fusionValue('schemaMap') ?: [];
        return array_filter(
            $map,
            function ($item) {
                return $item instanceof SchemaInterface;
            }
        );
    }

    public function evaluate()
    {
        return $this;
    }

    public function validate($data): Result
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('The nested schema can only handle arrays');
        }

        $result = new Result();

        /**
         * @var SchemaInterface[] $subschemas
         */
        $subschemas = $this->getSchemaMap();

        foreach ($subschemas as $fieldName => $fieldSchema) {
            if ($fieldSchema instanceof SchemaInterface) {
                $fieldValue = $data[$fieldName] ?? null;
                $result->forProperty($fieldName)->merge($fieldSchema->validate($fieldValue));
            }
        }

        return $result;
    }

    public function convert($data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('The nested schema can only handle arrays');
        }
        $result = [];

        /**
         * @var SchemaInterface[] $subschemas
         */
        $subschemas = $this->getSchemaMap();

        foreach ($subschemas as $fieldName => $fieldSchema) {
            if ($fieldSchema instanceof SchemaInterface) {
                $fieldValue = $data[$fieldName] ?? null;
                $result[$fieldName] = $fieldSchema->convert($fieldValue);
            }
        }


        return $result;
    }
}
