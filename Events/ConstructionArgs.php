<?php
/**
 * Created by PhpStorm.
 * User: srhoney
 * Date: 2/25/2019
 * Time: 4:14 PM
 */

namespace Circle\DoctrineRestDriver\Events;


use Doctrine\Common\EventArgs;

class ConstructionArgs extends EventArgs
{
    private $class;

    private $args;

    public function __construct($class, array $args)
    {
        $this->class = $class;
        $this->args = $args;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @param array $args
     */
    public function setArgs(array $args)
    {
        $this->args = $args;
    }

    public function getObject(){
        $class = $this->getClass();
        return new $class(... $this->getArgs());
    }
}