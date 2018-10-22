<?php

namespace App\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class ActiveAware
 * @package App\Annotation
 *
 * @Annotation
 * @Target("CLASS")
 */
class ActiveAware
{
    /**
     * @var string
     */
    public $activeFieldName = 'active';
}