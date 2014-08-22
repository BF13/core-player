<?php
namespace BF13\Component\Storage\DoctrineUnit\Tools;

/**
 * @author FYAMANI
 *
 */
class Inspector implements InspectorInterface
{
    public function inspect($classname)
    {
        return new \ReflectionClass($classname);
    }
}
