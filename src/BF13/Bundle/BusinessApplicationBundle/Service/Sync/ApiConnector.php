<?php
namespace BF13\Bundle\BusinessApplicationBundle\Service\Sync;

use BF13\Bundle\BusinessApplicationBundle\Service\Sync\HttpTransport\Transport;

class ApiConnector
{

    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
    }

    public function getLastRelease($token, $params = '')
    {
        if($params)
        {
            $params = '?' . $params;
        }

        $api_action = '/export/exportlastrelease/{token}' . $params;
        $api_data = array(
            '{token}' => $token
        );

        $api_call = strtr($api_action, $api_data);

        return $this->transport->request($api_call);
    }

    public function getRelease($release = null, $token, $params = '')
    {
        if($params)
        {
            $params = '?' . $params;
        }

        $api_action = '/export/exportrelease/{release}/{token}' . $params;
        $api_data = array(
            '{token}' => $token,
            '{release}' => $release
        );

        $api_call = strtr($api_action, $api_data);

        return $this->transport->request($api_call);
    }
}