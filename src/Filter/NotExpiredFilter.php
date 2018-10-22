<?php

namespace App\Filter;

use App\Annotation\NotExpiredAware;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Orm\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

final class NotExpiredFilter extends SQLFilter
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

        /** @var NotExpiredAware $notExpiredAware */
        $notExpiredAware = $this->reader->getClassAnnotation($targetEntity->getReflectionClass(), NotExpiredAware::class);
        if (!$notExpiredAware) {
            return '';
        }

        $fieldName = $notExpiredAware->expirationDateFieldName;

        return sprintf('%s.%s > "%s"', $targetTableAlias, $fieldName, date('Y-m-d H:i:s'));
    }

    /**
     * @param Reader $reader
     */
    public function setAnnotationReader(Reader $reader): void
    {
        $this->reader = $reader;
    }
}