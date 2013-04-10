<?php

namespace BF13\Bundle\BusinessApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DataValueList
 */
class DataValueList
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $value_key;

    /**
     * @var string
     */
    private $value;

    /**
     * @var \BF13\Bundle\BusinessApplicationBundle\Entity\ValueList
     */
    private $ValueList;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value_key
     *
     * @param string $valueKey
     * @return DataValueList
     */
    public function setValueKey($valueKey)
    {
        $this->value_key = $valueKey;
    
        return $this;
    }

    /**
     * Get value_key
     *
     * @return string 
     */
    public function getValueKey()
    {
        return $this->value_key;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return DataValueList
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set ValueList
     *
     * @param \BF13\Bundle\BusinessApplicationBundle\Entity\ValueList $valueList
     * @return DataValueList
     */
    public function setValueList(\BF13\Bundle\BusinessApplicationBundle\Entity\ValueList $valueList = null)
    {
        $this->ValueList = $valueList;
    
        return $this;
    }

    /**
     * Get ValueList
     *
     * @return \BF13\Bundle\BusinessApplicationBundle\Entity\ValueList 
     */
    public function getValueList()
    {
        return $this->ValueList;
    }
}