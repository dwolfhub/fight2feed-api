<?php

namespace App\Filter;

use App\Annotation\ActiveAware;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * Class ActiveFilter
 * @package App\Filter
 */
final class ActiveFilter extends SQLFilter
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * Gets the SQL query part to add to a query.
     *
     * @param ClassMetaData $targetEntity
     * @param string $targetTableAlias
     *
     * @return string The constraint SQL if there is available, empty string otherwise.
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (!$this->reader) {
            throw new \RuntimeException(
                sprintf(
                    'An annotation reader must be provided. Be sure to call "%s::setAnnotationReader()".',
                    __CLASS__
                )
            );
        }

        /** @var ActiveAware $activeAware */
        $activeAware = $this->reader->getClassAnnotation($targetEntity->getReflectionClass(), ActiveAware::class);
        if (!$activeAware) {
            return '';
        }

        $fieldName = $activeAware->activeFieldName;

        return sprintf('%s.%s = 1', $targetTableAlias, $fieldName);
    }

    /**
     * @param Reader $reader
     */
    public function setAnnotationReader(Reader $reader): void
    {
        $this->reader = $reader;
    }
}