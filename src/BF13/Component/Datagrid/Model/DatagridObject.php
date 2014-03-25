<?php
namespace BF13\Component\Datagrid\Model;
/**
 *
 * @author FYAMANI
 *
 */

class DatagridObject
{
    public $column_headers = array();

    public $column_values = array();

    public $ref;

    public $raw_columns = array();

    public function __construct($DatagridSettings)
    {
        $this->config = $DatagridSettings;

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

    public function bind($values)
    {
        $this->column_values = $values;
    }
}
