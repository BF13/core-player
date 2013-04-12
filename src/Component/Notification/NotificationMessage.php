<?php
namespace BF13\Component\Notification;

class NotificationMessage
{
    protected $from;

    protected $to;

    protected $subject;

    protected $content;

    /**
     * @return the unknown_type
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param unknown_type $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return the unknown_type
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param unknown_type $to
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return the unknown_type
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param unknown_type $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return the unknown_type
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param unknown_type $content
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
}