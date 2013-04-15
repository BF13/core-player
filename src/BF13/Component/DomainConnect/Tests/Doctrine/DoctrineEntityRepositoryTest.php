<?php
namespace BF13\Component\DomainConnect\Tests\Doctrine;

use BF13\Component\DomainConnect\Doctrine\DomainEntityRepository;
class DoctrineEntityRepositoryTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->builder = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
        ->disableOriginalConstructor()
//         ->setMethods(array('from'))
        ->getMock()
        ;
        
        
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
        ->disableOriginalConstructor()
//         ->setMethods(array())
        ->getMock()
        ;
        
        $em->expects($this->any())->method('createQueryBuilder')->will($this->returnValue($this->builder));
        
        $class = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
        ->disableOriginalConstructor()
//         ->setMethods(array())
        ->getMock()
        ;
        
        $this->repository = new DomainEntityRepository($em, $class);
    }
    
    public function testIsValid()
    {
        $this->assertInstanceOf('Doctrine\ORM\EntityRepository', $this->repository);
    }
    
    public function testInitializeQuery()
    {
        $this->assertInstanceOf('Doctrine\ORM\QueryBuilder', $this->repository->initializeQuery());
    }
    
    public function testNotExistingField()
    {
        $this->setExpectedException('Exception');
        
        $data = array('field1');
        
        $this->assertInstanceOf('Doctrine\ORM\QueryBuilder', $this->repository->selectQuery($this->builder, $data));
    }
}