<?php
namespace BF13\Component\DomainConnect\Tests\Doctrine;

use BF13\Component\DomainConnect\Doctrine\DomainQuery;
use BF13\Component\DomainConnect\DomainQueryInterface;
use BF13\Component\DomainConnect\Doctrine\DomainEntityRepository;

class DoctrineQueryTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $items = array(
            0 => array('name' => 'Bob'),
            1 => array('name' => 'Joe')
        );
        
        $builder = $this->getMock('Doctrine\ORM\QueryBuilder', array('addWhere', 'setParameter', 'getQuery', 'getResult'), array(), '', false);
        
        $builder->expects($this->any())
        ->method('getQuery')
        ->will($this->returnSelf())
        ;

        $builder->expects($this->any())
        ->method('getResult')
        ->will($this->returnValue($items))
        ;

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();

        $em->expects($this->any())->method('createQueryBuilder')->will($this->returnValue($builder));

        $class = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')->disableOriginalConstructor()
                //         ->setMethods(array())
                ->getMock();

        $repository = new DomainEntityRepository($em, $class);
        
        $repository->initDomainScheme(array('My\Entity' => array(
            'alias' => 'e',
            'properties' => array(
                'id' => array('field' => 'e.id'),
                'name' => array('field' => 'e.name')
            ),
            'conditions' => array(
                'active_item' => array('items' => array('e.id = :id' => 'id'))
            )
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
//             ->conditions(array('active_item'))
            ->result()
        ;
        
        $this->assertEquals('Bob', $item['name']);
    }
    
    public function testRetrieveAll()
    {
        $item = $this->querizer
            ->datafields(array('id', 'name'))
//             ->conditions(array('active_item' => 'param'))
            ->results()
        ;
        
        $this->assertEquals(2, sizeOf($item));
        
        $this->assertEquals('Bob', $item[0]['name']);
        
        $this->assertEquals('Joe', $item[1]['name']);
    }
}