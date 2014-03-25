<?php

namespace BF13\Bundle\BusinessApplicationBundle\Entity;

/**
 * LabelList
 */
class LabelList
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
    private $LabelValues;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->LabelValues = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return LabelList
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
     * @return LabelList
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
     * Add LabelValues
     *
     * @param \BF13\Bundle\BusinessApplicationBundle\Entity\LabelValue $labelValues
     * @return LabelList
     */
    public function addLabelValue(\BF13\Bundle\BusinessApplicationBundle\Entity\LabelValue $labelValues)
    {
        $this->LabelValues[] = $labelValues;

        return $this;
    }

    /**
     * Remove LabelValues
     *
     * @param \BF13\Bundle\BusinessApplicationBundle\Entity\LabelValue $labelValues
     */
    public function removeLabelValue(\BF13\Bundle\BusinessApplicationBundle\Entity\LabelValue $labelValues)
    {
        $this->LabelValues->removeElement($labelValues);
    }

    /**
     * Get LabelValues
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLabelValues()
    {
        return $this->LabelValues;
    }
}