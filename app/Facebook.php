<?php
namespace App;

class Facebook extends \Facebook\Facebook
{
    public function get($endpoint, $accessToken = null, $eTag = null, $graphVersion = null)
    {
        try {
            return parent::get($endpoint, $accessToken, $eTag, $graphVersion);
        } catch (\Exception $e) {
            if ($e->getCode() == 100 && $e->getSubErrorCode() == 33) {
                throw $e;
            }
            
            \Log::error('Facebook API: ' . $e->getMessage(), ['endpoint' => $endpoint]);
            return false;
        }
    }
}