<?php
namespace BF13\Component\Breadcrumb\Listener;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use BF13\Component\Breadcrumb\TwigExtension\BreadcrumbExtension;

class ControllerListener
{
    protected $service;

    public function __construct($service)
    {
        $this->service = $service;
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
                $rootNode = $Controller->getBreadcrumbName();
                
                $this->service->setRootNode($rootNode);

                $activeRoute = $event->getRequest()->get('_route');
                
                $this->service->setActiveRoute($activeRoute);
            }
        }
    }
}
