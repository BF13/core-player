<?php
namespace BF13\Component\Storage;

/**
 * @author FYAMANI
 *
 */
interface StorageConnectorInterface
{
    public function getHandler($fqcn);
    
    public function getQuerizer($serialname);
}
