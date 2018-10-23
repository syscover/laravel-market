<?php namespace Syscover\Market\Services;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

class PayPalCoreService
{
    public static function getApiContext()
    {
        // Set mode
        if(config('pulsar-market.paypal_mode') == 'live')
        {
            $client_id  = config('pulsar-market.paypal_live_client_id');
            $secret     = config('pulsar-market.paypal_live_secret');
        }
        else
        {
            $client_id  = config('pulsar-market.paypal_sandbox_client_id');
            $secret     = config('pulsar-market.paypal_sandbox_secret');
        }

        // init PayPal API Context
        $apiContext       = new ApiContext(new OAuthTokenCredential($client_id, $secret));

        // SDK configuration
        $apiContext->setConfig([
            'mode'                      => config('pulsar-market.paypal_mode'),    // Specify mode, sandbox or live
            'http.ConnectionTimeOut'    => 30,                                          // Specify the max request time in seconds
            'log.LogEnabled'            => true,                                        // Whether want to log to a file
            'log.FileName'              => storage_path() . '/logs/paypal.log',         // Specify the file that want to write on
            'log.LogLevel'              => 'FINE'                                       // Available option 'FINE', 'INFO', 'WARN' or 'ERROR', Logging is most verbose in the 'FINE' level and decreases as you proceed towards ERROR
        ]);

        return $apiContext;
    }
}