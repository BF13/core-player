<?php

namespace BF13\Bundle\BusinessApplicationBundle\Entity;

/**
 * ValueList
 */
class ValueList
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $list_key;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $DataListValues;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->DataListValues = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return ValueList
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set list_key
     *
     * @param string $listKey
     * @return ValueList
     */
    public function setListKey($listKey)
    {
        $this->list_key = $listKey;

        return $this;
    }

    /**
     * Get list_key
     *
     * @return string
     */
    public function getListKey()
    {
        return $this->list_key;
    }

    /**
     * Add DataListValues
     *
     * @param \BF13\Bundle\BusinessApplicationBundle\Entity\DataValueList $dataListValues
     * @return ValueList
     */
    public function addDataListValue(\BF13\Bundle\BusinessApplicationBundle\Entity\DataValueList $dataListValues)
    {
        $this->DataListValues[] = $dataListValues;

        return $this;
    }

    /**
     * Remove DataListValues
     *
     * @param \BF13\Bundle\BusinessApplicationBundle\Entity\DataValueList $dataListValues
     */
    public function removeDataListValue(\BF13\Bundle\BusinessApplicationBundle\Entity\DataValueList $dataListValues)
    {
        $this->DataListValues->removeElement($dataListValues);
    }

    /**
     * Get DataListValues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDataListValues()
    {
        return $this->DataListValues;
    }
}