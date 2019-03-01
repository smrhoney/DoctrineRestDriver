<?php
/**
 * Created by PhpStorm.
 * User: srhoney
 * Date: 2/28/2019
 * Time: 12:11 PM
 */

namespace Circle\DoctrineRestDriver\Annotations;

/**
 * Class NamedRoutes
 * @package Annotations
 *
 * @Annotation
 * @Target("CLASS")
 */
final class NamedRoutes
{
    /**
     * @var array<\Circle\DoctrineRestDriver\Annotations\NamedRoute>
     */
    public $value;
}