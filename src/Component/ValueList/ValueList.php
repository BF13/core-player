<?php
namespace BF13\Component\ValueList;

/**
 * Gestion des listes de valeurs
 *
 * @author FYAMANI
 *
 */
class ValueList
{
    protected $_em;

    protected $value_list;

    public function __construct(\Doctrine\ORM\EntityManager $em = null)
    {
        $this->_em = $em;

        $this->load();
    }

    protected function load()
    {
        $value_list = $query_builder = $this->_em->createQueryBuilder()
            ->select('v.id, v.value_key, v.value, l.list_key')
            ->from('BF13BusinessApplicationBundle:DataValueList', 'v')
            ->leftJoin('v.ValueList', 'l')

        ->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $this->value_list = array();

        foreach($value_list as $data){

            $this->value_list[$data['list_key']][$data['value_key']] = $data['value'];
        }
    }

    public function getListValues($list)
    {
        if(! array_key_exists($list, $this->value_list)) {

            return array();
        }

        return $this->value_list[$list];
    }
}