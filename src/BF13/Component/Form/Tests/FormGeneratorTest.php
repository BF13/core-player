<?php
namespace BF13\Component\Form\Tests;

use BF13\Component\Form\FormGenerator;

class FormGeneratorTest extends \PHPUnit_Framework_TestCase
{
    protected $generator;
    
    protected function setUp()
    {
        $factory = $this->getMockBuilder('Symfony\Component\Form\FormFactory')
        ->disableOriginalConstructor()
        ->setMethods(array('create'))
        ->getMock()
        ;
        
        $factory->expects($this->any())
        ->method('create')
        ->will($this->returnValue(true))
        ;
        
        $loader = 'BF13\Component\Form\Loader\YamlFileLoader';
        
        $this->generator = new FormGenerator($factory, $loader);
    }
    
    public function testSuccessBuildForm()
    {
        $file = __DIR__ . '/fixtures/form_test.form.yml';
        
        $item = $this->generator->buildForm($file);
        
        $this->assertTrue($item);
    }
}