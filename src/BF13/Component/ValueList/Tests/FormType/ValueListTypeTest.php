<?php
namespace BF13\Component\ValueList\Tests;

use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;
use BF13\Component\ValueList\FormType\ValueListType;

class ValueListTypeTest extends TypeTestCase
{
    protected $valueListeService;
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->valueListeService = $this
            ->getMockBuilder('BF13\Component\ValueList\ValueList')
            ->disableOriginalConstructor()
            ->setMethods(array('getListValues'))
            ->getMock();
        ;
    }
    
    public function testAddFormField()
    {
        $values = array('select-a', 'select-b', 'select-c');
        
        $this->valueListeService
            ->expects($this->any())
            ->method('getListValues')
            ->will($this->returnValue($values))
            ;
        
        $valueListType = new ValueListType($this->valueListeService);
        
        $formBuilder = $this->factory->createBuilder('form');
        
        $formBuilder->add('my_list', $valueListType);
        
        $form = $formBuilder->getForm();
        
        $this->assertTrue($form->isSynchronized());
        
        $view = $form->createView();
        
        $field = $view->children['my_list'];
        
        foreach($field->vars['choices'] as $value)
        {
            $this->assertTrue(in_array($value->label, $values));
        }
    }
}