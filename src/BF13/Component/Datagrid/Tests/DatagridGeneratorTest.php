<?php
namespace BF13\Component\Datagrid\Tests;

use BF13\Component\Datagrid\DatagridGenerator;

class DatagridGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /* (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $conn = null;
        
        $kernel = $this->getMockBuilder('Symfony\Framework\HttpFoundation\Kernel\Kernel')
        ->disableOriginalConstructor()
        ->setMethods(array('locateResource', 'getBundle', 'getNamespace'))
        ->getMock()
        ;
        
        $kernel->expects($this->any())
        ->method('locateResource')
        ->will($this->returnValue(__DIR__ . '/fixtures/grid.datagrid.yml'))
        ;
        
        $kernel->expects($this->any())
        ->method('getBundle')
        ->will($this->returnSelf())
        ;
        
        $kernel->expects($this->any())
        ->method('getNamespace')
        ->will($this->returnValue('\Domain'))
        ;
        
        $this->generator = new DatagridGenerator($conn, $kernel);
    }

    public function testLoadDatagrid()
    {
        $model = '@bundle:grid.datagrid.yml';
        
        $datagrid = $this->generator->buildDatagrid($model);
        
        $this->assertInstanceOf('BF13\Component\Datagrid\Model\DatagridObject', $datagrid);
    }
}
