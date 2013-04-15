<?php
namespace BF13\Component\DomainConnect\Tests\Doctrine;

use BF13\Component\DomainConnect\Doctrine\DoctrineManager;
use BF13\Component\DomainConnect\DomainManagerInterface;
use BF13\Component\DomainConnect\Doctrine\DomainEntityRepository;

class DoctrineManagerTest extends \PHPUnit_Framework_TestCase
{
    protected function setManager($data = array())
    {
        $repository = $this->getMockBuilder('BF13\Component\DomainConnect\Doctrine\DomainEntityRepository')
        ->disableOriginalConstructor()
        ->setMethods(array('find'))
        ->getMock()
        ;
        
        $repository->expects($this->any())->method('find')->will($this->returnValue($data))
        ;
        
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
        ->disableOriginalConstructor()
        ->setMethods(array('getRepository'))
        ->getMock()
        ;
        
        $em->expects($this->any())
        ->method('getRepository')
        ->will($this->returnValue($repository))
        ;
        
        return new DoctrineManager($em);
    }
    
    public function testInstanceOfDomainManager()
    {
        $manager = $this->setManager();
        
        $this->assertTrue($manager instanceOf DomainManagerInterface);
    }
    
    public function testGetRepository()
    {
        $manager = $this->setManager();
        
        $rep = $manager->getRepository('FQCN');

        $this->assertInstanceOf('BF13\Component\DomainConnect\Doctrine\DomainEntityRepository', $rep);
    }
    
    public function testRetrieveById()
    {
        $manager = $this->setManager(array('name' => 'my_name'));
        
        $item = $manager->retrieve('FQCN', 1);

        $this->assertEquals('my_name', $item['name']);
    }
    
    public function testExceptionOnRetrieveNotExisting()
    {
        $manager = $this->setManager(null);
        
        $this->setExpectedException('Exception');
        
        $item = $manager->retrieve('FQCN', 909);
    }
}