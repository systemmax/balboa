<?php

// if the constant is already defined, then the CRON script did it and we don't need to set it
defined('APP_ROOT') or include_once('../root-config.php');

include_once(APP_ROOT . "classes/sierra-apiAccessToken.php");
/**
Right now the only data we need out of this class is the bib title. But we may need/want to get more later.
 */
class sierraBib
{
    /** @var  config $config */
    protected $config;

    private $apiAccessToken = NULL;
    private $bibData = array();
    private $bibId;
    private $materialTypeCode;
    private $materialTypeValue;
    private $materialTypeImageFilename;

    public function __construct($config, $bibId)
    {
        $this->config = $config;

        $this->bibId = $bibId;

        $newToken = new sierraApiAccessToken($this->config);

        $this->apiAccessToken = $newToken->getCurrentApiAccessToken();

        $uri = 'https://' . $this->config->getDB() . ':443/iii/sierra-api/v' . (string)$this->config->getApiVer() . '/bibs/' . $bibId;
        $uri .= '?id=' . $bibId;
        $uri .= '&fields=title,author,varFields,fixedFields,materialType,publishYear,available';

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

        $this->bibData = $result;
        curl_close($ch);

        $this->setBibFormat();
    }

    public function getMarcRecord() {
        $uri = 'https://' . $this->config->getDB() . ':443/iii/sierra-api/v' . (string)$this->config->getApiVer() . '/bibs/' . $this->bibId . '/marc';

        // Build the header
        $headers = array(
            "Authorization: Bearer " . $this->apiAccessToken,
            "Accept: application/marc-in-json" // this parameter was CRITICAL.  Wouldn't work until we did this.
        );

        // make the request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = json_decode(curl_exec($ch),true);

        curl_close($ch);

        return $result;
    }

    public function getMarcRecordLink() {
        $uri = 'https://' . $this->config->getDB() . ':443/iii/sierra-api/v' . (string)$this->config->getApiVer() . '/bibs/marc';
        $uri .= '?id=' . $this->bibId;
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

        $resultData = json_decode(curl_exec($ch),true);
        curl_close($ch);

        $marcRecordLink = $resultData['MarcSummary']['file'];

        return $marcRecordLink;
    }

    public function getItemIds() {
        $uri = 'https://' . $this->config->getDB() . ':443/iii/sierra-api/v' . (string)$this->config->getApiVer() . '/items/?bibIds=' . $this->bibId;
        $uri .= '&fields=id';

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

        $result = json_decode(curl_exec($ch),true);

        curl_close($ch);

        return $result['entries'];
    }

    public function getBibId() {
        return $this->bibId;
    }

    public function getBibTitle() {
        return $this->bibData['title'];
    }

    public function getBibAuthor() {
        return $this->bibData['author'];
    }

    public function getPubYear() {
        return $this->bibData['publishYear'];
    }

    public function getAvailability() {
        return $this->bibData['available'];
    }


    // we say "ISBN" here but it could really be any field that content cafe will match cover art for (ISBN, UPC, etc.)
    public function getFirstISBN()
    {
        // look through the MARC for the first ISBN
        $thisISBN = 9999999999999;  // every MARC record will at least have a dummy ISBN so content cafe will deliver a "no image" image as a placeholder

        // iterate through all the variable length fields until we hit the first 020 or 024
        $isbnTags = array("020","024");
        foreach ($this->bibData['varFields'] as $thisField) {
            $thisMarcTag = $thisField['marcTag'];
            if (in_array($thisMarcTag,$isbnTags)){
                $thisISBN = $thisField['subfields'][0]['content'];
                // strip the ISBN to numbers.  Content cafe can deal with the extraneous junk; however - goodreads needs it sanitized
                $thisISBN = preg_replace("/[^0-9]/", "", $thisISBN); // strip all but the numbers
                break;
            }
        }
        return $thisISBN;
    }

    public function getOclcNumber()
    {
        $thisOCLC = "";
        // iterate through all the variable length fields until we hit the 035
        $oclcTags = array("035");
        foreach ($this->bibData['varFields'] as $thisField) {
            $thisMarcTag = $thisField['marcTag'];
            if (in_array($thisMarcTag,$oclcTags)){
                $thisOCLC = $thisField['subfields'][0]['content'];
                // strip the OCLC Control number to numbers.
                $thisOCLC = preg_replace("/[^0-9]/", "", $thisOCLC); // strip all but the numbers
                break;
            }
        }
        return $thisOCLC;
    }

    private function setBibFormat() {

        $this->materialTypeCode = trim($this->bibData['materialType']['code']);
        $this->materialTypeValue = trim($this->bibData['materialType']['value']);
        switch ($this->materialTypeCode) {
            case 'a'         : {
                $this->materialTypeImageFilename = "bookicon.png";
                break;
            }
            case 'c'         : {
                $this->materialTypeImageFilename = "media_music.png";
                break;
            }
            case 'd'         : {
                $this->materialTypeImageFilename = "media_music.png";
                break;
            }
            case 'e'         : {
                $this->materialTypeImageFilename = "mapicon.png";
                break;
            }
            case 'f'         : {
                $this->materialTypeImageFilename = "mapicon.png";
                break;
            }
            case 'g'         : {
                $this->materialTypeImageFilename = "filmicon.png";
                break;
            }
            case 'i'         : {
                $this->materialTypeImageFilename = "cdicon.png";
                break;
            }
            case 'j'         : {
                $this->materialTypeImageFilename = "media_music.png";
                break;
            }
            case 'k'         : {
                $this->materialTypeImageFilename = "bookicon.png";          /* NEED A BETTER ICON */
                break;
            }
            case 'm'         : {
                $this->materialTypeImageFilename = "bookicon.png";          /* NEED A BETTER ICON */
                break;
            }
            case 'o'         : {
                $this->materialTypeImageFilename = "bookicon.png";          /* NEED A BETTER ICON */
                break;
            }
            case 'w'         : {
                $this->materialTypeImageFilename = "journalicon.png";
                break;
            }
            case 'r'         : {
                $this->materialTypeImageFilename = "3dmediaicon.png";
                break;
            }
            case 't'         : {
                $this->materialTypeImageFilename = "bookicon.png";              /* NEED A BETTER ICON */
                break;
            }
            case 'z'         : {
                $this->materialTypeImageFilename = "bookicon.png";
                break;
            }
            case 'y'         : {
                 $this->materialTypeImageFilename = "bookicon.png";
                break;
            }
            case 'q'         : {
                $this->materialTypeImageFilename = "ebookicon.png";
                break;
            }
            case 'x'         : {
                $this->materialTypeImageFilename = "journalicon.png";
                break;
            }
            case 'u'         : {
                $this->materialTypeImageFilename = "audiocassetteicon.png";
                break;
            }
            case 'v'         : {
                $this->materialTypeImageFilename = "vhsicon.png";
                break;
            }
            case 's'         : {
                $this->materialTypeImageFilename = "audiocassetteicon.png";
                break;
            }
            case 'l'         : {
                $this->materialTypeImageFilename = "bookicon.png";
                break;
            }
            case 'b'         : {
                $this->materialTypeImageFilename = "bookicon.png";
                break;
            }
            case '2'         : {
                $this->materialTypeImageFilename = "media_music.png";
                break;
            }
            case 'h'         : {
                $this->materialTypeImageFilename = "ebookicon.png";
                break;
            }
            case 'p'         : {
                $this->materialTypeImageFilename = "mapicon.png.png";               /* NEED A BETTER ICON */
                break;
            }
            case 'n'         : {
                $this->materialTypeImageFilename = "journalicon.png";
                break;
            }
            case '3'         : {
                $this->materialTypeImageFilename = "bookicon.png";
                break;
            }
            default         :
                $this->materialTypeImageFilename = "bookicon.png";
                break;
        }
    }

    public function getBibFormat() {
        return $this->materialTypeValue;
    }

    public function getMaterialTypeFilename() {
        $fullimageFileName = 'images/material-types/' . $this->materialTypeImageFilename;
        return $fullimageFileName;
    }


    public function getPACURL() {
        return $this->config->getPacServer() . "/iii/encore/record/C__Rb" . $this->bibId;
    }

    public function getBookjacketImageURL()
    {
        // convert the isbn to 13 digits.  We get better luck finding images
        // with 13 digit ISBNs.
        $myIsbn = $this->getFirstISBN();
        if (strlen($myIsbn) == 10) {
            $myIsbn = $this->thirteenDigitIsbn($myIsbn);
        }

        $imgURL = "contentcafe2.btol.com/ContentCafe/Jacket.aspx";
        $imgURL .= "?UserID=" . $this->config->getContentCafeID() . "&Password=" . $this->config->getContentCafePassword();
        $imgURL .= "&Type=M";
        $imgURL .= "&Value=" . $myIsbn;
        $imgURL .= "&Return=T";
        $imgURL .= "&erroroverride=1";
        return $imgURL;
    }

    public function getBibSummary()
    {
        $thisSummary="";
        $summaryTag = "520";
        foreach ($this->bibData['varFields'] as $thisField) {
            $thisMarcTag = $thisField['marcTag'];
            if ($thisMarcTag == $summaryTag) {
                $thisSummary = $thisField['subfields'][0]['content'];
                break;
            }
        }
        return $thisSummary;
    }

    // converts a 10 digit isbn to 13 digits.  We use it on getting bookjacket images because we have better luck with 13 digit ISBNs.
    protected function thirteenDigitIsbn($tenDigitIsbn)
    {
        $newIsbn = "978" . substr($tenDigitIsbn, 0, 9);

        // add the odd and even digits
        $digitSum = 0;
        for ($i = 1; $i <= 12; ++$i) {
            // multiply the even digits by 3
            ($i % 2 == 0 ?
                $digitSum += 3 * $newIsbn[$i - 1] :
                $digitSum += $newIsbn[$i - 1]);
        }
        $checkDigit = (10 - ($digitSum % 10));
        return $newIsbn . $checkDigit;
    }
}