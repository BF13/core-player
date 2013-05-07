<?php
namespace BF13\Component\Storage\ArrayUnit;

use BF13\Component\Storage\StorageHandlerInterface;
use BF13\Component\Storage\Exception\StorageException;

/**
 * @author FYAMANI
 *
 */
class Handler implements StorageHandlerInterface
{
    const INDEX = 'id';
    
    protected $fqcn;

    public $datasource;

    public function __construct($fqcn, $datasource)
    {
        $this->fqcn = $fqcn;
        
        $this->datasource = $datasource;
    }

    /*
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageRepositoryInterface::retrieve()
     */
    public function retrieve($index)
    {
        $source = $this->datasource[$this->fqcn]['rows'];

        if (!array_key_exists($index, $source)) {

            $msg = sprintf('Unknow item with "id=%s"', $index);

            throw new StorageException($msg);
        }

        return $source[$index];
    }

    public function retrieveBy($field, $value)
    {
        $source = $this->datasource[$this->fqcn]['rows'];

        $index = array();

        while (list($key, $val) = each($source)) {

            $index[$key] = $val[$field];
        }

        if (!$key = array_search($value, $index)) {

            $msg = sprintf('Unknow item with "%s=%s"', $field, $value);

            throw new StorageException($msg);
        }

        return $source[$key];
    }

    /*
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageRepositoryInterface::retrieveNew()
     */
    public function create(\Closure $fn = null)
    {
        $source = $this->datasource[$this->fqcn]['structure']['columns'];

        $new = array();

        foreach ($source as $column) {

            $new[$column] = '';
        }

        return $new;
    }

    /*
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageRepositoryInterface::delete()
     */
    public function delete($index)
    {
        unset($this->datasource[$this->fqcn]['rows'][$index]);
    }

    /*
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageRepositoryInterface::store()
     */
    public function store($data)
    {
        foreach ($data as $this->fqcn => $inputs) {

            foreach ($inputs as $input) {

                if (array_key_exists('id', $input) && 0 < $input['id']) {

                    $this->datasource[$this->fqcn]['rows'][$input['id']] = $input;

                } else {

                    $index = max(array_keys($this->datasource[$this->fqcn]['rows'])) + 1;

                    $input = array_merge($input, array('id' => $index));

                    $this->datasource[$this->fqcn]['rows'][$index] = $input;
                }
            }
        }

    }
}
