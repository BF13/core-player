<?php
namespace BF13\Bundle\BusinessApplicationBundle\Model;

class ValidateAction
{
    protected $confirm;

    public function getConfirm()
    {
        return $this->confirm;
    }

    public function setConfirm($confirm)
    {
        $this->confirm = $confirm;
    }
}
