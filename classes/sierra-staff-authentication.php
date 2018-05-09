<?php

include_once($_SERVER['DOCUMENT_ROOT'] . "/classes/sierra-apiAccessToken.php");

class sierraStaffAuthentication
{
    /** @var config $config */
    protected $config;
    private $validStaffMember = false;
    private $apiAccessToken = NULL;


    public function __construct($config,$username,$password)
    {
        $this->config = $config;

        $newToken = new sierraApiAccessToken($this->config);
        $this->apiAccessToken = $newToken->getCurrentApiAccessToken();

        $this->setValidStaff($username,$password);
    }

    private function setValidStaff($username,$password) {
        // make the request
        $uri = 'https://' . $this->config->getDB() . ':443/iii/sierra-api/v' . (string)$this->config->getApiVer() . '/internal/users/auth/staff';
        $postFields = json_encode(array('userName' => $username, 'password' => $password));
        $header = array(
            "Authorization: Bearer " . $this->apiAccessToken,
            "Content-Type: application/json"
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header );

        $serverOutput = json_decode(curl_exec($ch));
        curl_close($ch);

        // a valid staff login returns an array of permission numbers.  We only care that it's a staff member, so all
        // we test for is the successful array key
        $this->validStaffMember = (property_exists($serverOutput,'authorizations'));
    }

    public function isValidLogin() {
        return $this->validStaffMember;
    }
}