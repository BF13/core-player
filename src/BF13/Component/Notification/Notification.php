<?php
namespace BF13\Component\Notification;

use BF13\Component\Notification\NotificationMessage;
use BF13\Component\Storage\StorageConnectorInterface;

/**
 * Service de messagerie interne
 *
 * @author FYAMANI
 *
 */
class Notification
{
    protected $session;

    protected $domainRepository;

    public function __construct($session, StorageConnectorInterface $domainRepository)
    {
        $this->session = $session;

        $this->domainRepository = $domainRepository;
    }

    public function notify($message = null, $notify = true)
    {
        $this->session->set('instant_notif', $notify);

        if($message)
        {
            $this->session->getFlashBag()->add('success', $message);
        }
        
        return true;
    }

    public function checkNewMessage($username)
    {
        $total_messages = $this->domainRepository
            ->getQuerizer('BF13BusinessApplicationBundle:InstantMessage')
            ->datafields(array('total'))
            ->conditions(array(
                'new_messages' => array(
                    'was_read' => 0,
                    'username' => $username,
            )))
        ->result();

        $total = $total_messages['total'];

        if(0 < $total)
        {
            $msg = sprintf('Vous avez <b>%s</b> nouveaux messages !', $total);

            $this->notify();
        }
    }

    public function addMessage(NotificationMessage $message, $disable_check = false)
    {
        $InstantMessage = $this->domainRepository->getHandler('BF13BusinessApplicationBundle:InstantMessage')->create();

        $InstantMessage->setFromUser($message->getFrom());
        $InstantMessage->setToUser($message->getTo());
        $InstantMessage->setSubject($message->getSubject());
        $InstantMessage->setContent($message->getContent());
        $InstantMessage->setWasRead(0);
        $InstantMessage->setCreatedAt(new \Datetime());

        $this->domainRepository->store($InstantMessage);

        $username = $message->getTo();

        if(! $disable_check)
        {
            $this->checkNewMessage($username);
        }
        
        return true;
    }
}