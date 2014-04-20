<?php

namespace BF13\Bundle\BusinessApplicationBundle\Entity;

/**
 * InstantMessage
 */
class InstantMessage
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $to;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $content;

    /**
     * @var boolean
     */
    private $was_read;

    /**
     * @var \DateTime
     */
    private $created_at;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set from
     *
     * @param string $from
     * @return InstantMessage
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get from
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set to
     *
     * @param string $to
     * @return InstantMessage
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get to
     *
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return InstantMessage
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return InstantMessage
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set was_read
     *
     * @param boolean $wasRead
     * @return InstantMessage
     */
    public function setWasRead($wasRead)
    {
        $this->was_read = $wasRead;

        return $this;
    }

    /**
     * Get was_read
     *
     * @return boolean
     */
    public function getWasRead()
    {
        return $this->was_read;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return InstantMessage
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    /**
     * @var string
     */
    private $from_user;

    /**
     * @var string
     */
    private $to_user;


    /**
     * Set from_user
     *
     * @param string $fromUser
     * @return InstantMessage
     */
    public function setFromUser($fromUser)
    {
        $this->from_user = $fromUser;

        return $this;
    }

    /**
     * Get from_user
     *
     * @return string
     */
    public function getFromUser()
    {
        return $this->from_user;
    }

    /**
     * Set to_user
     *
     * @param string $toUser
     * @return InstantMessage
     */
    public function setToUser($toUser)
    {
        $this->to_user = $toUser;

        return $this;
    }

    /**
     * Get to_user
     *
     * @return string
     */
    public function getToUser()
    {
        return $this->to_user;
    }
}
