<?php
namespace BF13\Bundle\BusinessApplicationBundle\Service\Sync\HttpTransport;

use BF13\Bundle\BusinessApplicationBundle\Service\Sync\Exception\SyncException;

class CurlTransport implements Transport
{

    public function __construct($endpoint, $auth)
    {
        $this->endpoint = $endpoint;

        $this->auth = $auth;
    }

    /*
     * (non-PHPdoc)
     * @see \BF13\Bundle\BusinessApplicationBundle\Service\Sync\DataLoader\Loader::load()
     */
    public function request($url)
    {
        $url = $this->endpoint . $url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-type: application/zip'
        ));

        if ('' != trim($this->auth)) {
            curl_setopt($ch, CURLOPT_USERPWD, $this->auth);
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $http_response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        switch (substr($http_status, 0, 1)) {
            case 2:
                return $http_response;
                break;

            case 4:
                if (404 == $http_status) {

                    throw new SyncException(sprintf('! Erreur HTTP %s : la page est introuvable !', $http_status));
                }

                throw new SyncException('! Erreur HTTP ' . $http_status . ": " . $http_response);
                break;

            case 5:
                if (503 == $http_status) {

                    throw new SyncException('! Erreur HTTP : La construction a généré une erreur !');
                }

                throw new SyncException('! Erreur HTTP ' . $http_status . "\n" . $url . "\n " . curl_error($ch));
                break;

            default:
                throw new SyncException('! Erreur HTTP ' . $http_status . "\n" . $url . "\n " . curl_error($ch));
        }

        curl_close($ch);
    }
}