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
    private $vlkey;

    /**
     * @var array
     */
    private $data;


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
     * Set vlkey
     *
     * @param string $vlkey
     * @return ValueList
     */
    public function setVlkey($vlkey)
    {
        $this->vlkey = $vlkey;

        return $this;
    }

    /**
     * Get vlkey
     *
     * @return string 
     */
    public function getVlkey()
    {
        return $this->vlkey;
    }

    /**
     * Set data
     *
     * @param array $data
     * @return ValueList
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return array 
     */
    public function getData()
    {
        return $this->data;
    }
}
