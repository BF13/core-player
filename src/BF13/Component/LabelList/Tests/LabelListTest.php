<?php
namespace BF13\Component\LabelList\Tests;

use BF13\Component\LabelList\LabelList;

class LabelListTest extends \PHPUnit_Framework_TestCase
{
    private $list;
    
    public function setUp()
    {
        $values = array(
                array('id' => 1, 'label_key' => 'form.name', 'label' => 'Votre nom', 'list_key' => 'form'),
                array('id' => 2, 'label_key' => 'form.civility', 'label' => 'Votre titre', 'list_key' => 'form'),
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
        
        $this->list = new LabelList($repository);
    }
    
    public function testSuccessShowLabel()
    {
        $list = $this->list->getLabelValues('form');
        
        $this->assertEquals(2, sizeOf($list));
        
        $this->assertEquals('Votre nom', $list['form.name']);
    }
    
    public function testNotExistingList()
    {
        $list = $this->list->getLabelValues('form_nonexistingllist');
        
        $this->assertEquals(0, sizeOf($list));
    }
}