<?php
namespace BF13\Component\DomainConnect\Doctrine;

use BF13\Component\DomainConnect\DomainQueryInterface;

/**
 * Assistant requête
 *
 * @author FYAMANI
 *
 */
class DomainQuery implements DomainQueryInterface
{
    protected $_params = array('conditions' => array(), 'select' => array());

    protected $_repository;

    public function __construct($repository)
    {
        $this->_repository = $repository;
    }

    public function conditions($arg)
    {
        $this->_params['conditions'] = $arg;

        return $this;
    }

    public function sort($orderBy = array())
    {
        $this->_params['order_by'] = $orderBy;

        return $this;
    }

    public function pager($offset = 0, $max_result = 5)
    {
        $this->_params['pager'] = array('offset' => $offset * $max_result, 'max_result' => $max_result);

        return $this;
    }

    public function datafields($fields = array())
    {
        $this->_params['select'] = $fields;

        return $this;
    }

    public function groupBy($group_by = '')
    {
        $this->_params['group_by'] = $group_by;

        return $this;
    }

    public function result()
    {
        $query_builder = $this->_getQueryBuilder();

        $query_builder->setMaxResults(1);

        $results = $query_builder->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return $results[0];
    }

    public function results($mode = \Doctrine\ORM\Query::HYDRATE_ARRAY)
    {
        return $this->_getQueryBuilder()->getQuery()->getResult($mode);
    }

    public function resultsWithPager($offset = 0, $max_result = 5)
    {
        $query_builder = $this->_getQueryBuilder();

        //pager
        $this->_repository->pager($query_builder, array('offset' => $offset * $max_result, 'max_result' => $max_result));

        //result
        $results = $query_builder->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        //calcul du total
        $total = intval($this->_repository->total($query_builder));

        $max_page = ceil($total / $max_result);

        $current_page = $offset + 1;

        return array('total' => $total, 'rows' => $results, 'offset' => $offset, 'max_result' => $max_result, 'max_page' => $max_page, 'current_page' => $current_page,);
    }

    protected function _getQueryBuilder()
    {
        if (!$this->_repository instanceOf DomainEntityRepository) {

            throw new \Exception('Le dépôt doit être une instance de DomainEntityRepository');
        }

        $query_builder = $this->_repository->initializeQuery();

        $this->_repository->selectQuery($query_builder, $this->_params['select']);

        $this->_repository->conditionQuery($query_builder, $this->_params['conditions']);

        if (array_key_exists('group_by', $this->_params)) {

            $query_builder->groupBy($this->_params['group_by']);
        }

        if (array_key_exists('order_by', $this->_params)) {

            $this->_repository->orderBy($query_builder, $this->_params['order_by']);
        }

        $this->_repository->joinQuery($query_builder);

        return $query_builder;
    }
}
