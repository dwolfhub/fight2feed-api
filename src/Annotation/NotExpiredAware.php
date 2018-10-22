<?php

namespace App\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class NotExpiredAware
 * @package App\Annotation
 *
 * @Annotation
 * @Target("CLASS")
 */
class NotExpiredAware
{
    /**
     * @var string
     */
    public $expirationDateFieldName = 'expiration_date';
}