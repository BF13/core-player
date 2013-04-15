<?php
namespace BF13\Component\DomainConnect\Tests\Doctrine;

use BF13\Component\DomainConnect\Doctrine\DomainEntityRepository;

class DoctrineEntityRepositoryTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $builder = $this->getMock('Doctrine\ORM\QueryBuilder', array('addWhere', 'setParameter'), array(), '', false);

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();

        $em->expects($this->any())->method('createQueryBuilder')->will($this->returnValue($builder));

        $class = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')->disableOriginalConstructor()
                //         ->setMethods(array())
                ->getMock();

        $this->repository = new DomainEntityRepository($em, $class);
    }

    public function testIsValid()
    {
        $this->assertInstanceOf('BF13\Component\DomainConnect\Doctrine\DomainEntityRepository', $this->repository);
    }

    public function testAliasNotFoundException()
    {
        $this->setExpectedException('BF13\Component\DomainConnect\Exception\WrongSchemaException');

        $scheme = array();

        $scheme['My\Entity'] = array(
        //             'alias' => 'a',
        'properties' => array(), 'conditions' => array(),);

        $this->repository->initDomainScheme($scheme);
    }

    public function testPropertiesNotFoundException()
    {
        $this->setExpectedException('BF13\Component\DomainConnect\Exception\WrongSchemaException');

        $scheme = array();

        $scheme['My\Entity'] = array('alias' => 'a', 
        //             'properties' => array(),
        'conditions' => array(),);

        $this->prepareRepositoryScenario($scheme);
    }

    public function testConditionsNotFoundException()
    {
        $this->setExpectedException('BF13\Component\DomainConnect\Exception\WrongSchemaException');

        $scheme = array();

        $scheme['My\Entity'] = array('alias' => 'a', 'properties' => array(),
        //             'conditions' => array(),
        );

        $this->prepareRepositoryScenario($scheme);
    }

    public function testSuccessSchemaLoaded()
    {
        $scheme = array();
        
        $scheme['My\Entity'] = array(
                'alias' => 'a',
                'properties' => array('myfield1' => array('field' => 'a.field1')),
                'conditions' => array('first_condition' => array('items' => array('myfield1' => 'a.field1 = :myfield1'))),
        );

        $data = array('myfield1');
        
        $conditions = array('first_condition' => 'abc');
        
        $order = array('myfield1' => 'DESC');
        
        $this->prepareRepositoryScenario($scheme, $data, $conditions, $order);

        $this->assertInstanceOf('Doctrine\ORM\EntityRepository', $this->repository);
    }

    protected function prepareRepositoryScenario($scheme = array(), $data = array(), $conditions = array(), $order = null)
    {
        $this->repository->initDomainScheme($scheme);

        $this->repository->selectQuery($data);

        $this->repository->conditionQuery($conditions);
        
        $this->repository->orderBy($order);
    }
}
