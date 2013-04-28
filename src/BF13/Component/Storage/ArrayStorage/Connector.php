<?php
namespace BF13\Component\Storage\ArrayStorage;

use BF13\Component\Storage\StorageConnectorInterface;

/**
 * @author FYAMANI
 *
 */
class Connector implements StorageConnectorInterface
{
    protected $datasource;

    public function __construct($datasource)
    {
        $this->datasource = $datasource;
    }

    /* (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageConnectorInterface::getRepository()
     */
    public function getHandler($fqcn)
    {
        $handler = new Handler($fqcn, $this->datasource);

        return $handler;
    }

    /* (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageConnectorInterface::getQuerizer()
     */
    public function getQuerizer($serialname)
    {
        $querizer = new Querizer($this->getHandler($serialname));

        return $querizer;
    }
}
