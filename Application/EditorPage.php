<?php
namespace Application;

use Application\ConfFiles;

class EditorPage {

    private static $cf;
    private static $hlp;

    private static $editorKey = "%_KW";

    private static $editorsConstantValues = array(
        "['titlePage']" => "<?=\$_SESSION['titlePage']/*don't touch this line*/?>",
        "['isConnected']" => "<?=\$hlp->isConnected()/*don't touch this line*/?>",
        "['isConnectedAdmin']" => "<?=\$hlp->isConnectedSu()/*don't touch this line*/?>",
        "['isConnectedNormal']" => "<?=\$hlp->isConnectedNo()/*don't touch this line*/?>",
        "['isConnectedB']" => "<?php \$r = \$hlp->isConnected();if (\$r == 1) {echo \"true\";} else {echo \"false\";}/*don't touch this line*/?>",
        "['isConnectedAdminB']" => "<?php \$r = \$hlp->isConnectedSu(); if (\$r == 1) {echo \"true\";} else {echo \"false\";}/*don't touch this line*/?>",
        "['isConnectedNormalB']" => "<?php \$r = \$hlp->isConnectedNo(); if (\$r == 1) {echo \"true\";} else {echo \"false\";}/*don't touch this line*/?>",
    );

    public function __construct() {}

    public function initEditor($cf, $hlp) {
        global $cf, $hlp;
        self::$cf = $cf;
        self::$hlp = $hlp;
    }

    public static function saveHtmlEditor() {

    }

    private static function getElementsName():array {
        if (!isset($_SESSION['editName'])) {
            return array();
        }
        global $cf;
        $path = "KW/public/pages/" . $_SESSION['editName'] . ".conf";
        $elemReaded = preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, "elements"));
        return explode(",", $elemReaded);
    }

    private static function genArrayElements($elemName):array {
        global $cf;
        if (!isset($_SESSION['editName'])) {
            return array(
                "name" => $elemName,
                "type" => "",
                "class" => "",
                "parent" => "",
                "children" => ""
            );
        }
        $path = "KW/public/pages/" . $_SESSION['editName'] . ".conf";
        $res = array(
            "name" => $elemName,
            "type" => $cf->getValueFromKeyConf($path, $elemName),
            "class" => $cf->getValueFromKeyConf($path, $elemName . "-class"),
            "parent" => $cf->getValueFromKeyConf($path, $elemName . "-parent"),
            "children" => $cf->getValueFromKeyConf($path, $elemName . "-children"),
            "content" => $cf->getValueFromKeyConf($path, $elemName . "-content"),
        );

        if ($res['type'] == "img") {
            $res = array_merge($res, array("src" => $cf->getValueFromKeyConf($path, $elemName . "-src")));
        } else if ($res['type'] == "input") {

        } else if ($res['type'] == "form") {

        }

        return $res;
    }

    public static function getHtmlEditor():array {
        if (!isset($_SESSION['editName'])) {
            return array();
        }
        $res = array();
        $nameElems = self::getElementsName();
        for ($i = 0; $i < count($nameElems); $i++) {
            array_push($res, self::genArrayElements($nameElems[$i]));
        }
        return $res;
    }

    private static function isBaliseAutoClose($type):bool {
        if ($type == "area" || $type == "br")
            return true;
        if ($type == "hr" || $type == "img")
            return true;
        if ($type == "input" || $type == "link")
            return true;
        if ($type == "meta" || $type == "param")
            return true;
        return false;
    }

    private static function getCmdExec($str):string {
        global $cf, $hlp, $db;
        $res = "";
        $execs = explode("\$", $str);
        if ($execs[0] == "")
            unset($execs[0]);
        if (count($execs) == 1) {
            $args = explode("->", $execs[1]);
            $cmd = $args[1];
            $cmd = str_replace("()", "", $cmd);
            $argsStr = "";
            if ($cf->strContains($cmd, "(")) {
                $argsCmd = explode("(", $cmd);
                $argsCmd = explode(")", $argsCmd[1]);
                $argsStr = $argsCmd[0];
            }
            if ($args[0] == "hlp") {
                return $hlp->$cmd($argsStr);
            } else if ($args[0] == "cf") {
                return $cf->$cmd($argsStr);
            } else if ($args[0] == "db") {
                return $db->$cmd($argsStr);
            }
        } else {
            $argsStr = "";
            for ($i = count($execs); $i >= 2; $i--) {
                if (isset($execs[$i])) {
                    $execs[$i] = "\$" . $execs[$i];
                }
                if ($i > 1) {
                    $args = explode(", ", $execs[$i]);
                    for ($x = 0; $x < count($args); $x++) {
                        if ($args[$x][0] == "\$") {
                            $args[$x] = "\"" . self::getCmdExec($args[$x]) . "\"";
                        } else {
                            $args[$x] = str_replace("(", "", $args[$x]);
                            $args[$x] = str_replace(")", "", $args[$x]);
                        }
                    }
                    $execs[$i] = "";
                    for ($x = 0; $x < count($args); $x++) {
                        if ($execs[$i] == "") {
                            $execs[$i] = $args[$x];
                        } else {
                            $execs[$i] .= ", " . $args[$x];
                        }
                    }
                    if ($argsStr == "") {
                        $argsStr .= $execs[$i];
                    } else {
                        $argsStr .= ", " . $execs[$i];
                    }
                }
            }
            $execs[1] = str_replace("(", "", $execs[1]);
            $cmds = explode("->", $execs[1]);
            $func = $cmds[1];
            if ($cmds[0] == "hlp") {
                $res = $hlp->$func($argsStr);
            } else if ($cmds[0] == "cf") {
                $res = $cf->$func($argsStr);
            } else if ($cmds[0] == "db") {
                $res = $db->$func($argsStr);
            }
        }
        return $res;
    }

    private static function getHtmlStringBalise($arr, $parent) {
        global $cf, $hlp, $db;
        $res = "";
        for ($i = 0; $i < count($arr); $i++) {
            $balise = $arr[$i];
            if ($balise['parent'] == $parent) {
                $res .= "<" . $balise['type'] . " id=\"" . $balise['name'] . "\"";
                if ($balise['class'] != "") {
                    $res .= " class=\"" . $balise['class'] . "\"";
                }
                if ($balise['type'] == "img") {
                    $src = "";
                    if ($cf->strStartWith($balise['src'], "<?=")) {
                        $balise['src'] = $cf->getStrFromPos($balise['src'], 3);
                        $balise['src'] = $cf->strRmChars($balise['src'], strlen($balise['src']) - 2, strlen($balise['src']));
                        $execs = explode(" . ", $balise['src']);
                        for ($x = 0; $x < count($execs); $x++) {
                            if ($execs[$x][0] != "'" && $execs[$x][0] != "\"") {
                                $src .= self::getCmdExec($execs[$x]);
                            } else {
                                $strSrc = $cf->getStrFromPos($execs[$x], 1);
                                $strSrc = $cf->strRmChars($strSrc, strlen($strSrc) - 1, strlen($strSrc));
                                $src .= $strSrc;
                            }
                        }
                    } else {
                        $src = $balise['src'];
                    }
                    $res .= " src=\"" . $src . "\"";
                }
                if (self::isBaliseAutoClose($balise['type'])) {
                    $res .= ">";
                } else {
                    $res .= ">\n" . $balise['content'] . "\n" . self::getHtmlStringBalise($arr, $balise['name']) . "\n";
                    $res .= "</" . $balise['type'] . ">\n";
                }
            }
        }
        return $res;
    }

    public static function generateHtmlCode():string {
        global $cf, $hlp;
        if (!isset($_SESSION['editName'])) {
            return "no text in this page";
        }
        $res = "";
        $edit = self::getHtmlEditor();
        $res .= self::getHtmlStringBalise($edit, "body");
        return $res;
    }
}
?>