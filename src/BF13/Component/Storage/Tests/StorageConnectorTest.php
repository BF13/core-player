<?php
namespace BF13\Component\Storage\Tests;

use BF13\Component\Storage\ArrayStorage\ArrayStorageConnector;
use BF13\Component\Storage\StorageConnector;

class StorageConnectorTest extends \PHPUnit_Framework_TestCase
{
    protected $storage;

    protected function setUp()
    {
        $storage = $this->getMock('BF13\Component\Storage\StorageConnectorInterface', array('getHandler', 'getQuerizer'));

        $repository = $this->getMock('BF13\Component\Storage\StorageHandlerInterface');
        
        $querizer = $this->getMock('BF13\Component\Storage\StorageQuerizerInterface');
        
        $storage->expects($this->any())
        ->method('getHandler')
        ->will($this->returnValue($repository))
        ;
        
        $storage->expects($this->any())
        ->method('getQuerizer')
        ->will($this->returnValue($querizer))
        ;
        
        $this->storage = new StorageConnector($storage);
    }

    /**
     * Test the connection to the Handler
     * 
     */
    public function testGetHandler()
    {
        $repository = $this->storage->connect()->getHandler('My\Dom');

        $this->assertInstanceOf('BF13\Component\Storage\StorageHandlerInterface', $repository);
    }

    /**
     * Test the connection to the Querizer
     * 
     */
    public function testGetQuerizer()
    {
        $querizer = $this->storage->connect()->getQuerizer('My\Dom');

        $this->assertInstanceOf('BF13\Component\Storage\StorageQuerizerInterface', $querizer);
    }
}
