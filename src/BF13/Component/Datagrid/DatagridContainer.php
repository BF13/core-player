<?php
namespace BF13\Component\Datagrid;

class DatagridContainer
{
    protected $source;

    protected $ref;

    protected $condition;

    protected $columns;

    public function __construct($defaultConfig)
    {
        $conf = $defaultConfig['settings'];

        if(is_null($this->source))
        {
            $this->source = $conf['source'];
        }

        if(is_null($this->ref))
        {
            $this->ref = $conf['ref'];
        }

        if(is_null($this->condition))
        {
            $this->condition = $conf['condition'];
        }

        if(is_null($this->columns))
        {
            $this->columns = $conf['columns'];
        }
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource($source)
    {
        $this->source = $source;
    }

    public function getRef()
    {
        return $this->ref;
    }

    public function setRef($ref)
    {
        $this->ref = $ref;
    }

    public function getCondition()
    {
        return $this->condition;
    }

    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

}
