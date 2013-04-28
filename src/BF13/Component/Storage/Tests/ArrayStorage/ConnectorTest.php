<?php
namespace BF13\Component\Storage\Tests\ArrayUnit;

use BF13\Component\Storage\ArrayUnit\Connector;

/**
 * @author FYAMANI
 *
 */
class ConnectorTest extends \PHPUnit_Framework_TestCase
{
    protected $storage;

    protected function setUp()
    {
        $data = array();

        $this->storage = new Connector($data);
    }

    /**
     * Test the connection to the Handler
     * 
     */
    public function testGetHandler()
    {
        $handler = $this->storage->getHandler('My\Dom');

        $this->assertInstanceOf('BF13\Component\Storage\ArrayUnit\Handler', $handler);
    }

    /**
     * Test the connection to the Querizer
     * 
     */
    public function testGetQuerizer()
    {
        $querizer = $this->storage->getQuerizer('My:Dom');

        $this->assertInstanceOf('BF13\Component\Storage\ArrayUnit\Querizer', $querizer);
    }
}
