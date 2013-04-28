<?php
namespace BF13\Component\Storage\Tests\Doctrine;

use BF13\Component\Storage\DoctrineStorage\Connector;
use BF13\Component\Storage\DoctrineStorage\Repository;
/**
 * @author FYAMANI
 *
 */
class ConnectorTest extends \PHPUnit_Framework_TestCase
{
    protected $storage;
    
    protected function setUp()
    {
        $this->em = $this->getMock('Doctrine\ORM\EntityManager', array('getRepository'), array(), '', false);
        
        $this->kernel = $this->getMock('Symfony\Component\HttpKernel\Kernel', array('locateResource', 'registerBundles', 'registerContainerConfiguration'), array(), '', false);
        
        $this->storage = new Connector($this->em, $this->kernel);
    }

    /**
     * Test the connection to the Handler
     * 
     */
    public function testGetHandler()
    {
        $stub_repository = $this->getMock('BF13\Component\Storage\StorageRepositoryInterface', array('find', 'findOneBy', 'getClassName', 'getDefaultSchema'), array(), '', false);
        
        $this->em->expects($this->any())->method('getRepository')->will($this->returnValue($stub_repository));
        
        $handler = $this->storage->getHandler('My\Identity');

        $this->assertInstanceOf('BF13\Component\Storage\StorageHandlerInterface', $handler);
    }

    /**
     * Test the connection to the Querizer
     * 
     */
    public function testGetQuerizer()
    {
        $stub_repository = $this->getMock('BF13\Component\Storage\StorageRepositoryInterface', array('find', 'findOneBy', 'getClassName', 'getDefaultSchema'), array(), '', false);
        
        $this->em->expects($this->any())->method('getRepository')->will($this->returnValue($stub_repository));
        
        $schema_path = __DIR__ . '/Mock/doctrine/DoctrineEntity.dql.yml';
        
        $this->kernel->expects($this->any())->method('locateResource')->will($this->returnValue($schema_path));
        
        $querizer = $this->storage->getQuerizer('My\Dom\Entity:DoctrineEntity');

        $this->assertInstanceOf('BF13\Component\Storage\StorageQuerizerInterface', $querizer);
    }
}

