<?php
namespace BF13\Component\Storage\DoctrineUnit;

use Doctrine\ORM\EntityRepository;

use BF13\Component\Storage\StorageRepositoryInterface;

class Repository extends EntityRepository implements StorageRepositoryInterface
{
    protected $from;

    protected $columns = array();

    protected $conditions = array('_self' => array('items' => array('id' => 'id = :id')));

    protected $joins = array();

    /**
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageRepositoryInterface::getDefaultSchema()
     */
    public function getDefaultSchema()
    {
        if (is_null($this->from)) {

            $alias = substr(substr(strtolower($this->_entityName), strrpos($this->_entityName, '\\') + 1), 0, 1);

            $this->from = array($this->_entityName, $alias);
        }

        return array('from' => $this->from, 'columns' => $this->columns, 'conditions' => $this->conditions, 'joins' => $this->joins);
    }
}
