<?php
namespace BF13\Component\Breadcrumb\Tests;

use BF13\Component\Breadcrumb\Breadcrumb;

class BreadcrumbTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $settingsFile = __DIR__ . '/fixtures/breadcrumb.yml';
       
        $this->breadcrumb = new Breadcrumb($settingsFile);
    }
    
    public function testLoadBreadcrumb()
    {
        $this->assertTrue(0 < sizeOf($this->breadcrumb->getRaw()));
    }
}