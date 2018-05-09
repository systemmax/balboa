<?php

// this is the germ of a multi-lingual string engine. This is good enough until and unless we have a greater need
// for alternately spelled terms here and there.

class localeStrings
{
    public static function translate($locale_code, $wordToTranslate) {
        if ($locale_code == "US") return $wordToTranslate;
        switch ($wordToTranslate) {
            case 'Cataloging' :
                switch ($locale_code) {
                    case 'CN' :
                    case 'UK' :
                        return 'Cataloguing';
                        break;
                }
                break;
            case 'Catalog' :
                switch ($locale_code) {
                    case 'CN' :
                    case 'UK' :
                        return 'Catalogue';
                        break;
                }
                break;
            case 'March 1' :
                switch ($locale_code) {
                    case 'CN' :
                    case 'UK' :
                        return '1 March';
                        break;
                }
                break;
            case 'February 15' :
                switch ($locale_code) {
                    case 'CN' :
                    case 'UK' :
                        return '15 February';
                        break;
                }
                break;
            case 'February 2' :
                switch ($locale_code) {
                    case 'CN' :
                    case 'UK' :
                        return '2 February';
                        break;
                }
                break;
            case 'January 17' :
                switch ($locale_code) {
                    case 'CN' :
                    case 'UK' :
                        return '17 January';
                        break;
                }
                break;
            default :
                return 'UNDEFINED LOCALE WORD';
        }
        return "";
    }

    public static function shortFormatString($localeCode) {
        switch ($localeCode) {
            case 'CN' :
            case 'UK' :
                return 'd M Y';
                break;
            default:
                return 'M d Y';
        }
    }

    public static function longFormatString($localeCode) {
        switch ($localeCode) {
            case 'CN' :
            case 'UK' :
                return 'd F, Y';
                break;
            default:
                return 'F d, Y';
        }
    }
}

