<?php

namespace BF13\Bundle\BusinessApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LabelValue
 */
class LabelValue
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $label_key;

    /**
     * @var string
     */
    private $label;

    /**
     * @var \BF13\Bundle\BusinessApplicationBundle\Entity\LabelList
     */
    private $LabelList;


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
     * Set label_key
     *
     * @param string $labelKey
     * @return LabelValue
     */
    public function setLabelKey($labelKey)
    {
        $this->label_key = $labelKey;
    
        return $this;
    }

    /**
     * Get label_key
     *
     * @return string 
     */
    public function getLabelKey()
    {
        return $this->label_key;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return LabelValue
     */
    public function setLabel($label)
    {
        $this->label = $label;
    
        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set LabelList
     *
     * @param \BF13\Bundle\BusinessApplicationBundle\Entity\LabelList $labelList
     * @return LabelValue
     */
    public function setLabelList(\BF13\Bundle\BusinessApplicationBundle\Entity\LabelList $labelList = null)
    {
        $this->LabelList = $labelList;
    
        return $this;
    }

    /**
     * Get LabelList
     *
     * @return \BF13\Bundle\BusinessApplicationBundle\Entity\LabelList 
     */
    public function getLabelList()
    {
        return $this->LabelList;
    }
}