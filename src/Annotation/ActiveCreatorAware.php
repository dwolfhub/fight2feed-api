<?php

namespace App\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ActiveCreatorAware
 * @package App\Annotation
 *
 * @Annotation
 * @Target("CLASS")
 */
class ActiveCreatorAware
{
    /**
     * @var string
     */
    public $creatorIdFieldName = 'creator_id';
}