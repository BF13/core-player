<?php
namespace BF13\Component\Storage\ArrayUnit;

use BF13\Component\Storage\StorageQuerizerInterface;

/**
 * @author FYAMANI
 *
 */
class Querizer implements StorageQuerizerInterface
{
    protected $datasource;

    protected $query;

    public function __construct(Handler $handler)
    {
        $this->datasource = $handler->datasource;
    }

    public function from($from)
    {
        $this->query['from'] = $from;

        return $this;
    }

    public function datafields($fields = array())
    {
        $this->query['fields'] = $fields;

        return $this;
    }

    public function conditions($arg)
    {
        $this->query['conditions'] = $arg;

        return $this;
    }

    public function sort($orderBy = array())
    {
        $this->query['sort'] = $orderBy;

        return $this;
    }

    public function group($groups = array())
    {
        $this->query['group'] = $groups;

        return $this;
    }

    public function result()
    {
        $res = $this->exec();

        return current($res);
    }

    public function results()
    {
        $res = $this->exec();

        return $res;
    }

    protected function exec()
    {
        $res = array();

        if(isset($this->query['from']))
        {
            $res = $this->datasource[$this->query['from']]['rows'];
        }

        return $res;
    }
}