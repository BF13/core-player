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

    public $global_actions = array();

    public $row_actions = array();

    protected $formatter = null;

    public function __construct($DatagridSettings)
    {
        $this->config = $DatagridSettings;

        $this->setColumnHeaders($DatagridSettings->getColumns());
    }

    protected function setColumnHeaders($columns)
    {
        $raw_columns = array();

        $labels = array();

        foreach ($columns as $key => $opt) {

            $refname = $opt['ref'];
            if(is_array($refname))
            {
                $refname = $refname['name'];
            }

            if (! array_key_exists('hidden', $opt) || true !== $opt['hidden']) {

                if (isset($opt['label']) && '' != trim($opt['label'])) {
                    $label = $opt['label'];
                } else {

                    $label = '' != trim($refname) ? $refname : $key;
                }

                $key = isset($refname) ? $refname : $key;

                $labels[$key] = $label;
            }

            $raw_columns[$refname] = $opt;
        }

        $this->raw_columns = $raw_columns;
        $this->column_headers = $labels;
    }

    public function loadData($values)
    {
        return $this->bind($values, true);
    }

    public function updateConfig($config)
    {
        if (isset($config['formatter'])) {
            $this->setFormatter($config['formatter']);
        }
        if (isset($config['row_actions'])) {
            $this->setRowActions($config['row_actions']);
        }
    }

    public function bind($values, $format = false)
    {
        $this->column_values = $values;

        if ($format && $this->formatter) {
            $this->formatter->format($this);
        }
    }

    protected function setRowActions($rowActions)
    {
        $this->row_actions = $rowActions;
    }

    public function getRowActions()
    {
        return $this->row_actions;
    }

    public function setFormatter($formatter)
    {
        $this->formatter = $formatter;
    }
}
