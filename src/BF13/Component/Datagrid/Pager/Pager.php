<?php
namespace BF13\Component\Datagrid\Pager;

class Pager
{
    public $total_pages;

    public $current_page;

    public $offset;

    public $total_items;

    public $max_per_page;

    public $count_values;

    public function __construct($data)
    {
        foreach($data as $key => $value)
        {
            $this->$key = $value;
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
