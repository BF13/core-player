<?php
namespace BF13\Component\ValueList\Tests;

use BF13\Component\ValueList\ValueList;

class ValueListTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $values = array(
                array('id' => 1, 'value_key' => 'ouinon.oui', 'value' => 'oui', 'list_key' => 'ouinon'),
                array('id' => 2, 'value_key' => 'ouinon.non', 'value' => 'non', 'list_key' => 'ouinon'),
        );
        
        $repository = $this->getMockBuilder('BF13\Component\DomainConnect\DomainRepository')
        ->disableOriginalConstructor()
        ->setMethods(array('getQuerizer', 'datafields', 'results'))
        ->getMock();
        ;
        
        $repository->expects($this->any())
        ->method('getQuerizer')
        ->will($this->returnSelf())
        ;
        
        $repository->expects($this->any())
        ->method('datafields')
        ->will($this->returnSelf())
        ;
        
        $repository->expects($this->any())
        ->method('results')
        ->will($this->returnValue($values))
        ;
        
        $this->valueList = new ValueList($repository);
    }
    
    public function testSuccessShowList()
    {
        $list = $this->valueList->getListValues('ouinon');
        
        $this->assertEquals(2, sizeOf($list));
        
        $this->assertEquals('oui', $list['ouinon.oui']);
        
        $this->assertEquals('non', $list['ouinon.non']);
    }
    
    public function testNotExistingList()
    {
        $list = $this->valueList->getListValues('ouinonx');
        
        $this->assertTrue(is_array($list));
        
        $this->assertEquals(0, sizeOf($list));
    }
    
    public function testNullList()
    {
        $list = $this->valueList->getListValues();
        
        $this->assertTrue(is_array($list));
        
        $this->assertEquals(0, sizeOf($list));
    }
}