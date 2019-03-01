<?php
/**
 * Created by PhpStorm.
 * User: srhoney
 * Date: 2/28/2019
 * Time: 12:09 PM
 */

namespace Circle\DoctrineRestDriver\Annotations;


use Circle\DoctrineRestDriver\Types\MaybeString;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class NamedRoute
 * @package Circle\DoctrineRestDriver\Annotations
 *
 * @Annotation
 */
final class NamedRoute implements DataSource
{
    use Route {
        Route::__construct as createRoute;
    }

    /**
     * @var string|null
     * @Required
     */
    private $name;

    /**
     * @inheritDoc
     */
    public function __construct(array $values)
    {
        $settings = new ArrayCollection($values);

        $this->createRoute($values);
        $this->name = MaybeString::assert($settings->get('name'),'name');
    }

    /**
     * Returns name
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

}