<?php
namespace BF13\Component\DomainConnect;

interface DomainQueryInterface
{
    /**
     * tableau des conditions
     *
     *     nom_condition => [param1 => value1, ...]
     *
     *
     * @param unknown_type $arg
     * @return \Rff\DomainBundle\Service\Shared\EntityQuerizer
     */
    public function conditions($arg);
    
    /**
     * Sélection de la colonne de trie
     *
     *
     * @param unknown_type $orderBy
     */
    public function sort($orderBy = array());
    
    /**
     * Pagination du résultat
     *
     * @param unknown_type $offset
     * @param unknown_type $max_result
     * @return \Rff\DomainBundle\Service\Shared\EntityQuerizer
     */
    public function pager($offset = 0, $max_result = 5);
    
    /**
     * Liste des colonnes retournées
     *
     * @param unknown_type $fields
     * @return \Rff\DomainBundle\Service\Shared\EntityQuerizer
     */
    public function datafields($fields = array());
    
    /**
     * Grouper les résultats
     *
     * @param unknown_type $group_by
     * @return \Rff\DomainBundle\Service\Shared\EntityQuerizer
     */
    public function groupBy($group_by = '');
    
    /**
     * Retourne un résultat
     *
     * @return unknown
     */
    public function result();
    
    /**
     * Retourne un tableau de résultats
     *
     */
    public function results($mode = \Doctrine\ORM\Query::HYDRATE_ARRAY);
    
    /**
     * Retourne le résultat paginé
     *
     * @param unknown_type $offset
     * @param unknown_type $max_result
     * @return multitype:unknown number
     */
    public function resultsWithPager($offset = 0, $max_result = 5);
}