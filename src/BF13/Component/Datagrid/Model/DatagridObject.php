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
        $this->raw_columns = $columns;

        $labels = array();

        foreach ($columns as $key => $opt) {

            if (! array_key_exists('hidden', $opt) || true !== $opt['hidden']) {

                if (isset($opt['label']) && '' != trim($opt['label'])) {
                    $label = $opt['label'];
                } else {

                    $label = isset($opt['ref']) && '' != trim($opt['ref']) ? $opt['ref'] : $key;
                }

                $key = isset($opt['ref']) ? $opt['ref'] : $key;

                $labels[$key] = $label;
            }
        }

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
    }

    public function bind($values, $format = false)
    {
        $this->column_values = $values;

        if ($format && $this->formatter) {
            $this->formatter->format($this);
        }
    }

    public function setFormatter($formatter)
    {
        $this->formatter = $formatter;
    }
}
