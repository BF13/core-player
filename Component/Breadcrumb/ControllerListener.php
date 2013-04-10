<?php
namespace BF13\Component\Breadcrumb;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use BF13\Component\Breadcrumb\TwigExtension\BreadcrumbExtension;

class ControllerListener
{
    protected $extension;

    public function __construct($extension)
    {
        $this->extension = $extension;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {

            $data = $event->getController();

            $Controller = $data[0];

            $interface = 'BF13\Component\Breadcrumb\BreadcrumbControllerInterface';

            $interfaces = class_implements($Controller);

            if(in_array($interface, $interfaces))
            {
                $this->extension->setController($Controller);

                $this->extension->setRequest($event->getRequest());
            }
        }
    }
}
