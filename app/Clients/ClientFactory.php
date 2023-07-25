<?php

namespace App\Clients;

use App\Clients\Cbr\CbrXmlClient;
use App\Clients\Cbr\CbrSoapClient;
use Exception;

class ClientFactory
{
    public function initializeClient(string $client)
    {
        if ($client == 'cbr-soap') {
            return new CbrSoapClient;
        } elseif ($client == 'cbr-xml') {
            return new CbrXmlClient;
        }

        throw new Exception('Unsupported client');
    }
}
