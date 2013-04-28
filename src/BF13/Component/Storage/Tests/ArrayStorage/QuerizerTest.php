<?php
namespace BF13\Component\Storage\Tests\ArrayUnit;

use BF13\Component\Storage\ArrayUnit\Querizer;
use BF13\Component\Storage\ArrayUnit\Handler;

/**
 * @author FYAMANI
 *
 */
class QuerizerTest extends \PHPUnit_Framework_TestCase
{
    protected $querizer;

    public function setUp()
    {
        $datasource = array(
                'My\Dom\Identity' => array(
                        'structure' => array('columns' => array('id', 'name')), 
                        'rows' => array(
                                1 => array('id' => '1', 'name' => 'Bob'), 
                                3 => array('id' => '3', 'name' => 'Jack'),
                        ),
                ));
        
        $handler = new Handler('My\Dom\Identity', $datasource);

        $this->querizer = new Querizer($handler);
    }

    public function testRetrieveAll()
    {
        $items = $this->querizer
        ->from('My\Dom\Identity')
        ->datafields(array('name'))
        ->conditions(array('active' => null))
        ->sort(array('name' => 'DESC'))
        ->group(array('name'))
        ->results()
        ;
        
        $this->assertEquals(2, sizeOf($items));
        
        $this->assertEquals('Bob', $items[1]['name']);
        
        $this->assertEquals('Jack', $items[3]['name']);
    }
}
