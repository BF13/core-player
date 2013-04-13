<?php
namespace BF13\Component\DomainConnect\Tests;

use BF13\Component\DomainConnect\DomainConnector;

/**
 * 
 * @author FYAMANI
 *
 */
class DomainConnectorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $om = $this->getMockBuilder('BF13\Component\DomainConnect\Doctrine\DoctrineManager')
        ->disableOriginalConstructor()
        ->setMethods(array('getRepository', 'find', 'findOneBy', 'initDomainScheme'))
        ->getMock();
        
        $om->expects($this->any())
        ->method('getRepository')
        ->will($this->returnSelf());
        
        $om->expects($this->any())
        ->method('find')
        ->will($this->returnValue(null));
        
        $om->expects($this->any())
        ->method('initDomainScheme')
        ->will($this->returnValue(true));
        
        $kernel = $this->getMockBuilder('Symfony\Component\HttpFoundation\Kernel')
        ->disableOriginalConstructor()
        ->setMethods(array('locateResource'))
        ->getMock();
        
        $path = __DIR__ . '/fixtures/data.dql.yml';
        
        $kernel->expects($this->any())
        ->method('locateResource')
        ->will($this->returnValue($path));
        
        $this->repository = new DomainConnector($om, $kernel);
    }
    
    public function testGetManager()
    {
        $item = $this->repository->getManager();
        
        $this->assertInstanceOf('BF13\Component\DomainConnect\DomainManagerInterface', $item);
    }
    
    public function testGetQuerizer()
    {
        $item = $this->repository->getQuerizer('Bundle:MyItem');
        
        $this->assertInstanceOf('BF13\Component\DomainConnect\DomainQueryInterface', $item);
    }
}