<?php
namespace BF13\Component\LabelList;

/**
 * Gestion des libellés
 *
 * @author FYAMANI
 *
 */
class LabelValue
{
    protected $_em;

    protected $label_list;

    public function __construct(\Doctrine\ORM\EntityManager $em = null)
    {
        $this->_em = $em;

        $this->load();
    }

    protected function load()
    {
        $label_list = $query_builder = $this->_em->createQueryBuilder()
            ->select('v.id, v.label_key, v.label, l.list_key')
            ->from('BF13BusinessApplicationBundle:LabelValue', 'v')
            ->leftJoin('v.LabelList', 'l')

        ->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $this->label_list = array();

        foreach($label_list as $data){

            $this->label_list[$data['list_key']][$data['label_key']] = $data['label'];
        }
    }

    public function getLabelValues($list)
    {
        if(! array_key_exists($list, $this->label_list)) {

            return array();
        }

        return $this->label_list[$list];
    }
}