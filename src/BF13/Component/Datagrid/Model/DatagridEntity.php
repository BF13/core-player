<?php
namespace BF13\Component\Datagrid\Model;
/**
 *
 * @author FYAMANI
 *
 */
use BF13\Component\DomainConnect\DomainConnector;
use BF13\Component\Storage\DoctrineUnit\Connector;

class DatagridEntity extends DatagridObject
{
    public $column_headers = array();

    public $column_values = array();

    public $ref;

    protected $raw_columns = array();

    public function __construct($DatagridSettings, Connector $DomainRepository)
    {
        $this->config = $DatagridSettings;

        $this->DomainRepository = $DomainRepository;

        $this->setColumnHeaders($DatagridSettings->getColumns());
    }

    protected function setColumnHeaders($columns)
    {
        $this->raw_columns = $columns;

        $labels = array();

        foreach($columns as $key => $opt) {

            if(!array_key_exists('hidden', $opt) || true !== $opt['hidden'])
            {
                $label = '' != array_key_exists('label', $opt) && trim($opt['label']) ? $opt['label'] : $key;

                $labels[$key] = $label;
            }
        }

        $this->column_headers = $labels;
    }

    public function loadData($data)
    {
        $fields = array_keys($this->raw_columns);

        $query = $this->DomainRepository
        ->getQuerizer($this->config->getSource())
            ->datafields($fields);

        if($data && $condition = $this->config->getCondition() ) {

            $query->conditions(array($condition => $data));
        }

        $this->bind($query->results());
    }
}
