<?php
namespace BF13\Component\Storage;

/**
 * @author FYAMANI
 *
 */
interface StorageQuerizerInterface
{
    /**
     * Liste des colonnes retournées
     *
     * @param string $fields
     * @return \Rff\DomainBundle\Service\Shared\EntityQuerizer
     */
    public function datafields($fields = array());

    /**
     * tableau des conditions
     *
     *     nom_condition => [param1 => value1, ...]
     *
     *
     * @param array $arg
     * @return \Rff\DomainBundle\Service\Shared\EntityQuerizer
     */
    public function conditions($arg);

    /**
     * Sélection de la colonne de trie
     *
     *
     * @param array $orderBy
     */
    public function sort($orderBy = array());

    /**
     * Grouper les résultats
     *
     * @param unknown_type $group_by
     * @return \Rff\DomainBundle\Service\Shared\EntityQuerizer
     */
    public function group($groups = array());

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
    public function results();
}
