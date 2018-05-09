<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/sierra-apiAccessToken.php");

class sierraPatronTypes
{
    /** @var config $config */
    protected $config;

    private $apiAccessToken = NULL;

    // an array of pickupLocations data built from the JSON returned by the API
    private $patronTypes = array();

    public function __construct($config)
    {
        $this->config = $config;

        $newToken = new sierraApiAccessToken($config);
        $this->apiAccessToken = $newToken->getCurrentApiAccessToken();

        $this->populatePatronTypeData();
    }

    private function populatePatronTypeData()
    {
        $uri = 'https://' . $this->config->getDB() . ':443/iii/sierra-api/v' . (string)$this->config->getApiVer() . '/patrons/metadata';
        $uri .= '?fields=patronType';

        // Build the header
        $headers = array(
            "Authorization: Bearer " . $this->apiAccessToken
        );

        // make the request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $this->patronTypes = $result[0]["values"];
    }

    public function getPatronTypeDesc($patronTypeCode) {
        foreach ($this->patronTypes as $thisType) {
            if ($thisType['code'] == $patronTypeCode) {
                return $thisType["desc"];
            }
        }
        return "undefined description code.  :(";
    }
}