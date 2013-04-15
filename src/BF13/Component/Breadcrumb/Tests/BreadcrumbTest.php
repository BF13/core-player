<?php
namespace BF13\Component\Breadcrumb\Tests;

use BF13\Component\Breadcrumb\Breadcrumb;

class BreadcrumbTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $settingsFile = __DIR__ . '/fixtures/breadcrumb.yml';
       
        $this->breadcrumb = new Breadcrumb($settingsFile);
        
        $this->breadcrumb->setRootNode('Public');
        
        $this->breadcrumb->setActiveRoute('_my_route_name');
    }
    
    public function testLoadBreadcrumb()
    {
        $this->assertTrue(0 < sizeOf($this->breadcrumb->getRaw()));
    }
    
    public function testGetActiveMenu()
    {
        $this->assertEquals('Menu1', $this->breadcrumb->getActiveRoot());
    }
    
    public function testGetMenus()
    {
        $this->assertArrayHasKey('Menu1', $this->breadcrumb->getRoots());
    }
    
    public function testGetRootName()
    {
        $this->assertEquals('Welcome', $this->breadcrumb->getRootName());
    }
    
    public function testGetChilds()
    {
        $childs = $this->breadcrumb->getChilds();
        
        $this->assertTrue(is_array($childs));
        
        $this->assertTrue(array_key_exists('Actions', $childs));
    }
}