<?php
namespace BF13\Bundle\BusinessApplicationBundle\Service\Sync\HttpTransport;

interface Transport
{

    public function request($url);
}