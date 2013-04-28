<?php
namespace BF13\Component\Notification\Tests;

use BF13\Component\Notification\Notification;
use BF13\Bundle\BusinessApplicationBundle\Entity\InstantMessage;

class NotificationTest extends \PHPUnit_Framework_TestCase
{
    protected $notification;
    
    protected function setUp()
    {
        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')
        ->disableOriginalConstructor()
        ->setMethods(array('set', 'getFlashBag', 'add'))
        ->getMock()
        ;
        
        $session->expects($this->any())
        ->method('getFlashBag')
        ->will($this->returnSelf())
        ;
        
        $domain = $this->getMockBuilder('BF13\Component\Storage\StorageConnectorInterface')
        ->disableOriginalConstructor()
        ->setMethods(array('store', 'getQuerizer', 'datafields', 'conditions', 'result', 'retrieveNew', 'getHandler', 'create'))
        ->getMock()
        ;
        
        $domain->expects($this->any())
        ->method('getQuerizer')
        ->will($this->returnSelf())
        ;
        
        $domain->expects($this->any())
        ->method('datafields')
        ->will($this->returnSelf())
        ;
        
        $domain->expects($this->any())
        ->method('conditions')
        ->will($this->returnSelf())
        ;
        
        $domain->expects($this->any())
        ->method('result')
        ->will($this->returnValue(array('total' => 2)))
        ;
        
        $domain->expects($this->any())
        ->method('create')
        ->will($this->returnValue(new InstantMessage()))
        ;
        
        $domain->expects($this->any())
        ->method('getHandler')
        ->will($this->returnSelf())
        ;
        
        $domain->expects($this->any())
        ->method('store')
        ->will($this->returnValue(true))
        ;
        
        $this->notification = new Notification($session, $domain);
    }
    
    public function testSuccessNotify()
    {
        $this->assertTrue($this->notification->notify());
    }
    
    public function testAddMessage()
    {
        $message = $this->getMock('BF13\Component\Notification\NotificationMessage');
        
        $this->assertTrue($this->notification->addMessage($message));
    }
}