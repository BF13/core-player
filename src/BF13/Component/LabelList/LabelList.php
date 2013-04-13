<?php
namespace BF13\Component\LabelList;

use BF13\Component\DomainConnect\DomainRepository;

/**
 * Gestion des libellés
 *
 * @author FYAMANI
 *
 */
class LabelList
{
    protected $repository;

    protected $label_list;

    public function __construct(DomainRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function load()
    {
        $label_list = $query_builder = $this->repository
        ->getQuerizer('BF13BusinessApplicationBundle:LabelValue')
        ->datafields(array('id', 'label_key', 'label', 'list_key'))
        ->results();

        $this->label_list = array();

        foreach($label_list as $data){

            $this->label_list[$data['list_key']][$data['label_key']] = $data['label'];
        }
    }

    public function getLabelValues($list)
    {
        if(is_null($this->label_list))
        {
            $this->load();
        }
        
        if(! array_key_exists($list, $this->label_list)) {

            return array();
        }

        return $this->label_list[$list];
    }
}