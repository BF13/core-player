<?php
namespace BF13\Component\ValueList;

use BF13\Component\DomainConnect\DomainConnector;

/**
 * Gestion des listes de valeurs
 *
 * @author FYAMANI
 *
 */
class ValueList
{
    protected $repository;

    protected $value_list;

    public function __construct(DomainConnector $repository = null)
    {
        $this->repository = $repository;
    }

    protected function load()
    {
        $value_list = $query_builder = $this->repository
            ->getQuerizer('BF13BusinessApplicationBundle:ValueList')
            ->datafields(array('id', 'value_key', 'value', 'list_key'))
            ->results();
        
        $this->value_list = array();

        foreach($value_list as $data){

            $this->value_list[$data['list_key']][$data['value_key']] = $data['value'];
        }
    }

    public function getListValues($list = "")
    {
        if(is_null($this->value_list))
        {
            $this->load();
        }
        
        if(! array_key_exists($list, $this->value_list)) {

            return array();
        }

        return $this->value_list[$list];
    }
}