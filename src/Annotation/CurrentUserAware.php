<?php

namespace App\Annotation;

/**
 * Class CurrentUserAware
 * @package App\Annotation
 *
 * @Annotation
 * @Target("CLASS")
 */
class CurrentUserAware
{
    /**
     * @var string
     */
    public $userIdField = 'user_id';
}