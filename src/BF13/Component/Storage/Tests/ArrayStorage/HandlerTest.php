<?php
namespace BF13\Component\Storage\Tests\ArrayUnit;

use BF13\Component\Storage\ArrayUnit\Handler;

/**
 * @author FYAMANI
 *
 */
class HandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $handler;

    protected function setUp()
    {
        $datasource = array(
            'My\Dom\Identity' => array(
                'structure' => array('columns' => array('id', 'name')),
                'rows' => array(
                    1 => array('id' => '1', 'name' => 'Bob'),
                    3 => array('id' => '3', 'name' => 'Jack'),
                ),
            )
        );

        $this->handler = new Handler('My\Dom\Identity', $datasource);
    }

    public function testRetrieveOne()
    {
        $item = $this->handler->retrieve(1);
        
        $this->assertEquals('Bob', $item['name']);
    }

    public function testRetrieveOneByName()
    {
        $item = $this->handler->retrieveBy('name', 'Bob');
        
        $this->assertEquals('Bob', $item['name']);
    }

    public function testRetrieveNew()
    {
        $item = $this->handler->create(array());
        
        $this->assertTrue(array_key_exists('name', $item));
        
        $this->assertTrue(array_key_exists('id', $item));
    }
    
//     public function testDelete()
//     {
//         $this->setExpectedException('BF13\Component\Storage\Exception\StorageException');
        
//         $this->handler->delete('identity', 1);
        
//         $this->handler->retrieve('identity', '1');
//     }
    
//     public function testStore()
//     {
//         $items = array(
//                 'identity' => array(
//                     array('id' => '1', 'name' => 'Bob Duboit'),
//                     array('name' => 'John'),
//                 )
//         );
        
//         $this->handler->store($items);
        
//         $item1 = $this->handler->retrieve('identity', '1');
        
//         $this->assertEquals('Bob Duboit', $item1['name']);
        
//         $item2 = $this->handler->retrieve('identity', '1');
        
//         $this->assertEquals('Bob Duboit', $item1['name']);
//     }
}
