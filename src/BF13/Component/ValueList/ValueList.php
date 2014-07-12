<?php
namespace BF13\Component\ValueList;

use BF13\Component\Storage\StorageConnectorInterface;

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

    public function __construct(StorageConnectorInterface $repository = null)
    {
        $this->repository = $repository;
    }

    protected function load()
    {
        $value_list = $this->repository
            ->getQuerizer('BF13BusinessApplicationBundle:ValueList')
            ->datafields(array('id', 'vlkey', 'data'))
            ->results();

        $this->value_list = array();

        foreach($value_list as $values){

            list($key, $key_value) = explode('.', $values['vlkey']);

            $this->value_list[$key][$values['vlkey']] = $values['data']['fr'];
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