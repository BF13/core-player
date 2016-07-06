<?php
namespace BF13\Component\Datagrid\Model;

use Doctrine\ORM\Tools\Pagination\Paginator;
use BF13\Component\Datagrid\Pager\Pager;

/**
 *
 * @author FYAMANI
 *
 */
class DatagridEntity extends DatagridObject
{

    public $column_headers = array();

    public $column_values = array();

    public $ref;

    public $raw_columns = array();

    public $total_pages = 0;

    public $current_page = 0;

    public $offset = 0;

    public function __construct($DatagridSettings, $kernel)
    {
        $this->config = $DatagridSettings;

        $this->DomainRepository = $kernel->getContainer()->get('bf13.dom.repository');

        $this->setColumnHeaders($DatagridSettings->getColumns());
    }

    protected function setColumnHeaders($columns)
    {
        $raw_columns = array();
        $labels = array();

        foreach ($columns as $pos => $opt) {

            // if(!array_key_exists('hidden', $opt) || true !== $opt['hidden'])
            // {

            $refname = $opt['ref'];
            if(is_array($refname))
            {
                $refname = $refname['name'];
            }

            if (isset($opt['label']) && '' != trim($opt['label'])) {
                $label = $opt['label'];
            } else {

                $label = isset($refname) && '' != trim($refname) ? $refname : $pos;
            }

            $type = isset($opt['ref']['type']) ? $opt['ref']['type'] : 'default';
            switch ($type) {
                case 'MetaRelation':
                case 'MetaObject':
                    $key = $refname . '__' . $opt['ref']['attribute'];
                    break;

                default:
                    if (isset($refname)) {
                        $key = $refname;
                    } else {

                        // retro compatibilité
                        $key = $pos;
                    }
            }

            $labels[$key] = $label;

            $raw_columns[$key] = $opt;
            // }
        }

        $this->raw_columns = $raw_columns;
        $this->column_headers = $labels;
    }

    public function loadData($data, $pager = null)
    {
        $fields = array();

        $haveDerivedFields = false;
        foreach ($this->raw_columns as $pos => $col) {

            if (isset($col['is_derived']) && $col['is_derived'] == true) {
                $haveDerivedFields = true;
                continue;
            }
            $refname = $col['ref'];
            if(is_array($refname))
            {
                $refname = $refname['name'];
            }

            $type = isset($col['ref']['type']) ? $col['ref']['type'] : 'default';
            switch ($type) {
                case 'MetaRelation':
                case 'MetaObject':
                    $fields[] = $refname . '__' . $col['ref']['attribute'];
                    break;
                default:
                    if (isset($refname)) {
                        $fields[] = $refname;
                    } else {

                        // retro compatibilité
                        $fields[] = $pos;
                    }
            }
        }

        $query = $this->DomainRepository->getQuerizer($this->config->getSource());

//         if ($haveDerivedFields) {

//             $query->datafields();
//         } else {

//             $query->datafields(array_unique($fields));
//         }
        $query->datafields();
        if ($data && $condition = $this->config->getCondition()) {
            $query->conditions(array(
                $condition => $data
            ));
        }

        if (! is_null($pager)) {
            $this->offset = ($pager['page'] - 1) * $pager['max_items'];

                $this->bindEntityResult($query->resultsWithPager($this->offset, $pager['max_items']));
//             if ($haveDerivedFields) {
//                 $this->bindEntityResult($query->resultsWithPager($this->offset, $pager['max_items']));
//             } else {

//                 $this->bind($query->resultsWithPager($this->offset, $pager['max_items']), true);
//             }

            $totalitems = $query->totalResults();

            $total = isset($totalitems['total']) ? $totalitems['total'] : 0;

            $this->total_items = $total;

            $this->total_pages = ceil($total / $pager['max_items']);

            $this->current_page = ($this->offset / $pager['max_items']) + 1;

            $this->pager = new Pager(array(
                'count_values' => count($this->column_values),
                'offset' => ($pager['page'] - 1) * $pager['max_items'],
                'total_items' => (int) $total,
                'max_per_page' => (int) $pager['max_items'],
                'total_pages' => (int) ceil($total / $pager['max_items']),
                'current_page' => ($this->offset / $pager['max_items']) + 1,
                'query_string' => isset($pager['query_string']) ? $pager['query_string'] : null
            ));

        } else {

                $this->bindEntityResult($query->results());
//             if ($haveDerivedFields) {
//                 $this->bindEntityResult($query->results());
//             } else {

//                 $this->bind($query->results(), true);
//             }
        }
    }

    protected function bindEntityResult($entityResult)
    {
        $values = array();

        foreach ($entityResult as $dateEntity) {
            $row = array();
            foreach ($this->raw_columns as $pos => $col) {

                $refname = $col['ref'];
                if(is_array($refname))
                {
                    $refname = $refname['name'];
                }

                $type = isset($col['ref']['type']) ? $col['ref']['type'] : 'default';
                switch ($type) {
                    case 'MetaRelation':
                        $ref = $refname . '__' . $col['ref']['attribute'];
                        $action1 = sprintf('get%s', $refname);
                        $action2 = sprintf('get%s', $this->makeAttributeName($col['ref']['attribute']));

                        if(is_null($dateEntity))
                        {
                            $row[$ref] = '<error entity not found !>';

                        } else if(is_null($dateEntity->$action1()))
                        {
                            $row[$ref] = '<error entity not found !>';
                        } else {

                            $row[$ref] = $dateEntity->$action1()->$action2();
                        }
                        break;

                    case 'MetaObject':
                        $ref = $refname . '__' . $col['ref']['attribute'];
                        $action1 = sprintf('get%s', $refname);
                        $action2 = sprintf('%s', $col['ref']['attribute']);


                        if(is_null($dateEntity))
                        {
                            $row[$ref] = '<error object not found !>';

                        } else if(is_null($dateEntity->$action1()))
                        {
                            $row[$ref] = '<error object not found !>';
                        } else {

                            $row[$ref] = $dateEntity->$action1()->$action2();
                        }
                        break;

                    default:
                        if (isset($refname)) {
                            $ref = $refname;
                        } else {

                            // retro compatibilité
                            $ref = $pos;
                        }

                        $action = sprintf('get%s', $this->makeAttributeName($ref));
                        $row[$ref] = $dateEntity->$action();
                }
            }

            $values[] = $row;
        }

        return $this->bind($values, true);
    }

    protected function makeAttributeName($var_data)
    {
        if (strpos($var_data, '__') > 0) {
            return $var_data;
        }

        if (false === strpos($var_data, '_')) {
            return $var_data;
        }

        $var_data = array_map(function ($item)
        {
            return ucfirst(strtolower($item));
        }, explode('_', $var_data));

        return implode('', $var_data);
    }

    public function updateConfig($config)
    {
        parent::updateConfig($config);

        if (isset($config['source'])) {
            $this->config->setSource($config['source']);
        }
        if (isset($config['condition'])) {
            $this->config->setCondition($config['condition']);
        }
    }

    public function totalPages()
    {
        return $this->total_pages;
    }

    public function currentPage()
    {
        return $this->current_page;
    }

    public function previousPages($range = 3)
    {
        $offset = array();

        $limit = $this->current_page - $range;

        for ($i = $limit; $i < $this->current_page; $i ++) {
            if (0 >= $i) {
                continue;
            }

            $offset[] = $i;
        }
        return $offset;
    }

    public function nextPages($range = 3)
    {
        $offset = array();

        $limit = $this->current_page + $range;

        for ($i = $this->current_page + 1; $i <= $this->total_pages; $i ++) {
            $offset[] = $i;

            if ($limit == $i) {
                break;
            }
        }
        return $offset;
    }
}
