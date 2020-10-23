<?php

namespace App;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;

ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

class PayPalClient
{
    /**
     * Returns PayPal HTTP client instance with environment that has access
     * credentials context. Use this instance to invoke PayPal APIs, provided the
     * credentials have access.
     */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    /**
     * Set up and return PayPal PHP SDK environment with PayPal access credentials.
     * This sample uses SandboxEnvironment. In production, use ProductionEnvironment.
     */
    public static function environment()
    {
        // SANDBOX
        $clientId = getenv("CLIENT_ID") ?: "AaD1jx7dmn0kDatlZ2jv_1z9zBlQeAM5uBN_3m5KubcR2Uy0aPA18-HvU7l6bgQ83q_SMh09BOhyk7fZ";
        $clientSecret = getenv("CLIENT_SECRET") ?: "EN0ZIMHZ378kl2YA4vMbwN5t_512yvhLALiOyMKUO1RFtA9rAQVNrqSINrb7rdJKv3bsQEbATT4czahw";
        return new SandboxEnvironment($clientId, $clientSecret);
        // LIVE
        /*
        $clientId = getenv("CLIENT_ID") ?: "AVWxZABs2mfRSII-eYUohB4jFj5Q2sCSXfCV4qB89HB7JNyg5OTBVUEU5jlrhyc0EcVzoBtBUfblAy6a";
        $clientSecret = getenv("CLIENT_SECRET") ?: "EGElrPVXyvOGedIdejwpF-UKJJL-pScRQ71JVwt6iFmPEC-a2HHzCA8cWYkHO9_3HxBOIlb31DNzw1rE";
        return new ProductionEnvironment($clientId, $clientSecret);
        */
    }
}