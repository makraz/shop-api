<?php

declare(strict_types=1);

namespace App\Command\Traits;

use App\Entity\EntityInterface;

trait HydrateCommandToEntityTrait
{
    public function hydrateToEntity(?EntityInterface $entity = null, bool $skipNullField = false): EntityInterface
    {
        $reflectionClass = new \ReflectionClass($this);
        $entityClass = $this->getEntityClass();
        $entity = $entity ?? new $entityClass();

        foreach ($reflectionClass->getProperties() as $property) {
            $propertyName = $property->getName();

            $propertyValue = $property->getValue($this);
            if (null === $propertyValue && $skipNullField) {
                continue;
            }

            if (property_exists($this->getEntityClass(), $propertyName)) {
                $userSetter = 'set'.ucfirst($propertyName);
                if (method_exists($this->getEntityClass(), $userSetter)) {
                    $entity->$userSetter($propertyValue);
                } else {
                    $entity->$propertyName = $propertyValue;
                }
            }
        }

        return $entity;
    }
}
