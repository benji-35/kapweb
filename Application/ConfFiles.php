<?php
namespace Application;

class ConfFiles {

    private static $pathDbConfig = "KW/kapweb_sys/kw_db_connect.conf";
    private static $pathOtherConf = "KW/kapweb_sys/kw_files.conf";

    public function __construct() {}

    public static function strCanBeRead($str): bool {
        for ($i = 0; $i < strlen($str); $i++) {
            if ($str[$i] != "\t" && $str[$i] != "\n" && $str[$i] != " ")
                return true;
        }
        return false;
    }

    public static function strContains(string $str, string $target):bool {
        $s1 = strlen($str);
        $s2 = strlen($target);
        if ($s2 > $s1)
            return false;
        $curr = 0;
        for ($i = 0; $i < $s1; $i++) {
            if ($str[$i] == $target[$curr]) {
                $curr++;
            } else {
                $curr = 0;
            }
            if ($curr >= $s2)
                return true;
        }
        if ($curr >= $s2)
            return true;
        return false;
    }

    public static function rmFChars(string $str, int $nb):string {
        $res = "";

        for ($i = $nb; $i < strlen($str); $i++) {
            $res .= $str[$i];
        }
        return $res;
    }

    public static function strEqualitySize($str1, $str2, $size):bool {
        if (strlen($str2) > $size)
            return false;
        if (strlen($str1) < $size)
            $size = strlen($str1);
        for ($i = 0; $i < $size; $i++) {
            if ($str1[$i] != $str2[$i]) {
                return false;
            }
        }
        return true;
    }

    public static function getStrFromPos($str, $pos):string {
        $res = "";
        for ($i = $pos; $i < strlen($str); $i++) {
            $res .= $str[$i];
        }
        return $res;
    }

    public static function getStrToPos($str, $pos):string {
        $res = "";
        if ($pos > strlen($str))
            $pos = strlen($str);
        for ($i = 0; $i < $pos; $i++) {
            $res .= $str[$i];
        }
        return $res;
    }

    public static function strRmChars($str, $posA, $posB):string {
        $res = "";
        for ($i = 0; $i < $posA; $i++) {
            $res .= $str[$i];
        }
        for ($i = $posB; $i < strlen($str); $i++) {
            $res .= $str[$i];
        }
        return $res;
    }

    public static function replaceStrStr($str, $target, $replace):string {

        for ($i = 0; $i < strlen($str); $i++) {
            $strCurr = self::getStrFromPos($str, $i);
            if (self::strEqualitySize($strCurr, $target, strlen($target))) {
                $str = self::strRmChars($str, $i, $i + strlen($target));
                $str = self::getStrToPos($str, $i) . $replace . self::getStrFromPos($str, $i);
                $i--;
            }
        }
        return $str;
    }

    public static function strStartWith(string $str, string $target):bool {
        $s2 = strlen($target);
        if (strlen($str) < $s2)
            return false;
        for ($i = 0; $i < $s2; $i++) {
            if ($str[$i] != $target[$i])
                return false;
        }
        return true;
    }

    public static function strEquality(string $str1, string $str2):bool {
        if (strlen($str2) != strlen($str1))
            return false;
        for ($i = 0; $i < strlen($str1); $i++) {
            if ($str1[$i] != $str2[$i])
                return false;
        }
        return true;
    }

    public static function getValueFromKeyConf($pathFile, $key):string {
        if (file_exists($pathFile) == false || filesize($pathFile) == 0)
            return "\0";
        $f = fopen($pathFile, "r");
        $str = fread($f, filesize($pathFile));
        fclose($f);
        $lines = explode("\n", $str);
        for ($i = 0; $i < count($lines); $i++) {
            $arrLine = explode("=", $lines[$i]);
            if (count($arrLine) >= 2 && self::strEquality($arrLine[0], $key)) {
                $res = $arrLine[1];
                for ($i = 2; $i < count($arrLine); $i++) {
                    $res .= "=" . $arrLine[$i];
                }
                if (self::strCanBeRead($res)) {
                    return $res;
                } else {
                    return "\0";
                }
            }
        }
        return "\0";
    }

    private static function haveKeyInText($text, $key):bool {
        $lines = explode("\n", $text);
        for ($i = 0; $i < count($lines); $i++) {
            if (self::strStartWith($lines[$i], $key))
                return true;
        }
        return false;
    }

    public static function addValueFormKeyConf($pathFile, $key, $value) {
        $totStr = "";
        if (file_exists($pathFile) && filesize($pathFile) > 0) {
            $f = fopen($pathFile, "r");
            $str = fread($f, filesize($pathFile));
            fclose($f);
            $totStr = $str;
        }
        if (self::haveKeyInText($totStr, $key)) {
            $text = explode("\n", $totStr);
            $totStr = "";
            for ($i = 0; $i < count($text); $i++) {
                if (self::strStartWith($text[$i], $key . "=")) {
                    $text[$i] = $key . "=" . $value;
                }
                if ($totStr == "") {
                    $totStr = $text[$i];
                } else {
                    $totStr .= "\n" . $text[$i];
                }
            }
        } else {
            if ($totStr != "")
                $totStr .= "\n";
            $totStr .= $key . "=" . $value; 
        }
        $f = fopen($pathFile, "w");
        if ($f) {
            fwrite($f, $totStr, strlen($totStr));
        }
        fclose($f);
    }

    public static function sys_getMainIco():string {
        return self::getValueFromKeyConf(self::$pathOtherConf, "main_icon");
    }

    public static function getDbConfig():string {
        return self::$pathDbConfig;
    }

    public static function getFilesConfig():string {
        return self::$pathOtherConf;
    }
}
?>