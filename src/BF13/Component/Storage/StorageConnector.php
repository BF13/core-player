<?php
namespace BF13\Component\Storage;

class StorageConnector
{
    protected $storage;
    
    public function __construct(StorageConnectorInterface $storage)
    {
        $this->storage = $storage;
    }
    
    public function connect()
    {
        return $this->storage;
    }
}
