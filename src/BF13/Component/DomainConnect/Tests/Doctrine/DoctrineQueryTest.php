<?php
namespace BF13\Component\DomainConnect\Tests\Doctrine;

use BF13\Component\DomainConnect\Doctrine\DomainQuery;
use BF13\Component\DomainConnect\DomainQueryInterface;

class DoctrineQueryTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $items = array(
            0 => array('name' => 'Bob'),
            1 => array('name' => 'Joe')
        );
        
        $repository = $this
        ->getMockBuilder('BF13\Component\DomainConnect\Doctrine\DomainEntityRepository')
        ->disableOriginalConstructor()
        ->setMethods(array('initializeQuery', 'selectQuery', 'joinQuery', 'conditionQuery', 'getQuery', 'setMaxResults', 'getResult'))
        ->getMock()
        ;
        
        $repository->expects($this->any())->method('initializeQuery')->will($this->returnSelf());
        $repository->expects($this->any())->method('selectQuery')->will($this->returnSelf());
//         $repository->expects($this->any())->method('conditionQuery')->will($this->returnSelf());
//         $repository->expects($this->any())->method('groupBy')->will($this->returnSelf());
//         $repository->expects($this->any())->method('orderBy')->will($this->returnSelf());
        $repository->expects($this->any())->method('joinQuery')->will($this->returnSelf());
        $repository->expects($this->any())->method('getQuery')->will($this->returnSelf());
        $repository->expects($this->any())->method('setMaxResults')->will($this->returnSelf());
        $repository->expects($this->any())->method('getResult')->will($this->returnValue($items));
        
        $repository->initDomainScheme(array('My\Entity' => array(
            'alias' => 'e',
            'properties' => array(
                'name' => array('field' => 'e.name')
            ),
            'conditions' => array()
        )));
        
        $this->querizer = new DomainQuery($repository);
    }
    
    public function testInstanceOfDomainQuery()
    {
        $this->assertTrue($this->querizer instanceOf DomainQueryInterface);
    }
    
    public function testRetrieveOne()
    {
        $item = $this->querizer
            ->datafields(array('id', 'name'))
            ->conditions(array('active_item'))
            ->result()
        ;
        
        $this->assertEquals('Bob', $item['name']);
    }
    
    public function testRetrieveAll()
    {
        $item = $this->querizer
            ->datafields(array('id', 'name'))
            ->conditions(array('active_item'))
            ->results()
        ;
        
        $this->assertEquals(2, sizeOf($item));
        
        $this->assertEquals('Bob', $item[0]['name']);
        
        $this->assertEquals('Joe', $item[1]['name']);
    }
}