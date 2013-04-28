<?php
namespace BF13\Component\Storage\Tests\Doctrine;

use BF13\Component\Storage\DoctrineUnit\Handler;

/**
 * @author FYAMANI
 *
 */
class HandlerTest extends \PHPUnit_Framework_TestCase
{
    protected $handler;

    protected $stub_em;

    protected $stub_repository;

    protected function setUp()
    {
        $this->stub_em = $this->getMock('Doctrine\ORM\EntityManager', array('getRepository', 'persist', 'remove', 'flush'), array(), '', false);

        $this->stub_repository = $this->getMock('BF13\Component\Storage\StorageRepositoryInterface', array('find', 'findOneBy', 'getClassName', 'getDefaultSchema'), array(), '', false);

        $this->stub_em->expects($this->any())->method('getRepository')->will($this->returnValue($this->stub_repository));

        $this->handler = new Handler($this->stub_repository);
    }

    public function testRetrieveOne()
    {
        $this->stub_repository->expects($this->any())->method('find')->will($this->returnValue(array('name' => 'Bob', 'id' => 1)));

        $item = $this->handler->retrieve(1);

        $this->assertEquals('Bob', $item['name']);
    }

    public function testRetrieveOneByName()
    {
        $this->stub_repository->expects($this->any())->method('findOneBy')->will($this->returnValue(array('name' => 'Bob', 'id' => 1)));

        $item = $this->handler->retrieve(array('name' => 'Bob'));

        $this->assertEquals('Bob', $item['name']);

        $this->assertEquals(1, $item['id']);
    }

    public function testRetrieveNew()
    {
        $class = 'BF13\Component\Storage\Tests\Doctrine\Mock\DoctrineEntity';

        $this->stub_repository->expects($this->any())->method('getClassName')->will($this->returnValue($class));

        $item = $this->handler->create();

        $this->assertInstanceOf($class, $item);
    }

//     public function testDelete()
//     {
//         $this->stub_repository->expects($this->once())->method('find')->will($this->returnValue(array('name' => 'Bob', 'id' => 1)));
        
//         $this->stub_em->expects($this->once())->method('remove');
        
//         $this->stub_em->expects($this->once())->method('flush');

//         $this->handler->delete(1);
//     }

//     public function testStore()
//     {
//         $this->stub_em->expects($this->once())->method('persist');
        
//         $this->stub_em->expects($this->once())->method('flush');
        
//         $class = 'BF13\Component\Storage\Tests\Doctrine\Mock\DoctrineEntity';
        
//         $item = new $class;
        
//         $this->handler->store($item);
//     }
}
