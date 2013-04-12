<?php
namespace BF13\Component\Notification;

use BF13\Component\Notification\NotificationMessage;

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

    public function __construct($session, $domainRepository, $securityContext)
    {
        $this->session = $session;

        $this->securityContext = $securityContext;

        $this->domainRepository = $domainRepository;
    }

    public function notify($message = null, $notify = true)
    {
        $this->session->set('instant_notif', $notify);

        if($message)
        {
            $this->session->getFlashBag()->add('success', $message);
        }
    }

    public function verifierMessage($username)
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

    public function ajouterMessage(NotificationMessage $message)
    {
        $InstantMessage = $this->domainRepository->retrieveNew('BF13BusinessApplicationBundle:InstantMessage');

        $InstantMessage->setFromUser($message->getFrom());
        $InstantMessage->setToUser($message->getTo());
        $InstantMessage->setSubject($message->getSubject());
        $InstantMessage->setContent($message->getContent());
        $InstantMessage->setWasRead(0);
        $InstantMessage->setCreatedAt(new \Datetime());

        $this->domainRepository->store($InstantMessage);

        $username = $message->getTo();

        $this->verifierMessage($username);
    }
}