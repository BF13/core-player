<?php
namespace BF13\Component\Storage\DoctrineUnit;

class Schema
{
    protected $name;

    protected $alias;

    protected $properties;

    protected $conditions;

    public function __construct()
    {

    }

    /**
     * @return the unknown_type
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param unknown_type $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return the unknown_type
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param unknown_type $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * @return the unknown_type
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param unknown_type $properties
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * @return the unknown_type
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param unknown_type $conditions
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
        return $this;
    }

}
