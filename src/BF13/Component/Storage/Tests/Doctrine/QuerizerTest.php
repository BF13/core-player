<?php
namespace BF13\Component\Storage\Tests\Doctrine;

use BF13\Component\Storage\DoctrineUnit\Querizer;
use BF13\Component\Storage\DoctrineUnit\Handler;

/**
 * @author FYAMANI
 *
 */
class QuerizerTest extends \PHPUnit_Framework_TestCase
{
    protected $querizer;

    protected function setUp()
    {
        $this->stub_builder = $this->getMock('Doctrine\ORM\QueryBuilder', array('select', 'from', 'andWhere', 'groupBy', 'leftJoin', 'addOrderBy', 'getQuery', 'getResult'), array(), '', false);

        $this->stub_repository = $this->getMock('BF13\Component\Storage\DoctrineUnit\Repository', array('getDefaultSchema'), array(), '', false);

        $this->querizer = new Querizer($this->stub_repository, $this->stub_builder);
    }

    public function testRetrieveAll()
    {
        $data = array('aa', 'bb');
        
        $this->stub_builder->expects($this->any())
            ->method('getQuery')
            ->will($this->returnSelf())
        ;

        $this->stub_builder->expects($this->any())
            ->method('getResult')
            ->will($this->returnValue($data))
        ;

        $items = $this->querizer->results();

        $this->assertTrue(is_array($items));

        $this->assertEquals('aa', $items[0]);

        $this->assertEquals('bb', $items[1]);
    }
}
