<?php

namespace App\Filter;

use App\Annotation\CurrentUserAware;
use App\Entity\User;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Symfony\Component\Security\Core\Security;

/**
 * Class CurrentUserFilter
 * @package App\Filter
 */
class CurrentUserFilter extends SQLFilter
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param ClassMetadata $targetEntity
     * @param string $targetTableAlias
     *
     * @return string
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

        if (!$this->security) {
            throw new \RuntimeException(
                sprintf(
                    'The security context must be provided. Be sure to call "%s::setSecurity()".',
                    __CLASS__
                )
            );
        }

        /** @var CurrentUserAware $currentUserAware */
        $currentUserAware = $this->reader->getClassAnnotation(
            $targetEntity->getReflectionClass(),
            CurrentUserAware::class
        );
        if (!$currentUserAware) {
            return '';
        }

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return '';
        }

        $fieldName = $currentUserAware->userIdField;

        return sprintf('%s.%s = %d', $targetTableAlias, $fieldName, $user->getId());
    }

    /**
     * @param Reader $reader
     */
    public function setAnnotationReader(Reader $reader) {
        $this->reader = $reader;
    }

    /**
     * @param Security $security
     */
    public function setSecurity(Security $security) {
        $this->security = $security;
    }
}