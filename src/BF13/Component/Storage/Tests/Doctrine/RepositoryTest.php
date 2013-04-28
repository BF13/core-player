<?php
namespace BF13\Component\Storage\Tests\Doctrine;

use BF13\Component\Storage\DoctrineStorage\Repository;
/**
 * @author bitnami
 *
 */
class RepositoryTest extends \PHPUnit_Framework_TestCase
{
    protected $repository;
    
    protected function setUp()
    {
        $this->stub_em = $this->getMock('Doctrine\ORM\EntityManager', array('getRepository', 'persist', 'remove', 'flush'), array(), '', false);
        
        $class = $this->getMock('Doctrine\ORM\Mapping\ClassMetadata', array(), array(), '', false);
        
        $this->repository = new Repository($this->stub_em, $class);
    }
    
    public function testIt()
    {
        
    }
}
