<?php
namespace BF13\Component\Datagrid\Formatter;

use  BF13\Component\Datagrid\ModelDatagridObject;

/**
 *
 * @author FYAMANI
 *
 */
interface FormatterInterface
{
    public function format(DatagridObject $DataGrid);
}
