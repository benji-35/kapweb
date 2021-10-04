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

    public static function addElement($name, $type, $parent="body", $class="", $content="") {
        if (!isset($_SESSION['editName'])) {
            return;
        }
        $elements = array();
        $nameElems = self::getElementsName();
        $to_write = "";
        for ($i = 0; $i < count($nameElems); $i++) {
            if ($to_write == "") {
                $to_write = "elements=" . $nameElems[$i];
            } else {
                $to_write .= "," . $nameElems[$i];
            }
            array_push($elements, self::genArrayElements($nameElems[$i]));
        }
        $to_write .= "," . $name;
        $to_write .= "\n";
        for ($i = 0; $i < count($nameElems); $i++) {
            $balise = $elements[$i];
            $to_write .= $balise['name'] . "=" . $balise['type'] . "\n";
            $to_write .= $balise['name'] . "-class=" . $balise['class'] . "\n";
            $to_write .= $balise['name'] . "-content=" . $balise['content'] . "\n";
            $to_write .= $balise['name'] . "-parent=" . $balise['parent'] . "\n";
            $to_write .= $balise['name'] . "-children=" . $balise['children'] . "\n";
            if ($balise['type'] == "input") {
                $to_write .= $balise['name'] . "-itype=" . $balise['itype'] . "\n";
                $to_write .= $balise['name'] . "-readonly=" . $balise['readonly'] . "\n";
                $to_write .= $balise['name'] . "-placeholder=" . $balise['placeholder'] . "\n";
                $to_write .= $balise['name'] . "-value=" . $balise['value'] . "\n";
                $to_write .= $balise['name'] . "-iname=" . $balise['iname'] . "\n";
            } else if ($balise['type'] == "img") {
                $to_write .= $balise['name'] . "-src=" . $balise['src'] . "\n";
            } else if ($balise['type'] == "form") {
                $to_write .= $balise['name'] . "-method=" . $balise['method'] . "\n";
            } else if ($balise['type'] == "a") {
                $to_write .= $balise['name'] . "-link=" . $balise['link'] . "\n";
            }
        }
        $to_write .= $name . "=" . $type . "\n";
        $to_write .= $name . "-class=" . $class . "\n";
        $to_write .= $name . "-content=" . $content . "\n";
        $to_write .= $name . "-parent=" . $parent . "\n";
        $to_write .= $name . "-child=\n";
        if ($type == "a") {
            $to_write .= $name . "-link=\n";
        } else if ($type == "input") {
            $to_write .= $name . "-itype=\n";
            $to_write .= $name . "-readonly=\n";
            $to_write .= $name . "-placeholder=\n";
            $to_write .= $name . "-value=\n";
            $to_write .= $name . "-iname=\n";
        } else if ($type == "img") {
            $to_write .= $name . "-src=\n";
        } else if ($type == "form") {
            $to_write .= $name . "-method=\n";
        }
        $path = "KW/public/pages/" . $_SESSION['editName'] . ".conf";
        $f = fopen($path, "w");
        if ($f) {
            fwrite($f, $to_write, strlen($to_write));
        }
        fclose($f);
    }

    private static function getDeletedStr($arr, $name):array {
        if (!isset($_SESSION['editName'])) {
            return array();
        }
        $children = "";
        for ($i = 0; $i < count($arr); $i++) {
            if ($arr[$i]['name'] == $name) {
                $children = $arr[$i]['children'];
                unset($arr[$i]);
            }
        }
        if ($children != "") {
            $arrChildren = explode(",", $children);
            for ($i = 0; $i < count($arrChildren); $i++) {
                $arr = self::getDeletedStr($arr, $arrChildren[$i]);
            }
        }
        return $arr;
    }

    public static function deleteElement($name, $curr=0) {
        if (!isset($_SESSION['editName'])) {
            return;
        }
        $elements = array();
        $nameElems = self::getElementsName();
        $to_write = "";
        
        for ($i = 0; $i < count($nameElems); $i++) {
            if ($nameElems[$i] != $name) {
                array_push($elements, self::genArrayElements($nameElems[$i]));
            }
        }
        $n_arr = self::getDeletedStr($elements, $name);

        for ($i = 0; $i < count($n_arr); $i++) {
            if (isset($n_arr[$i])) {
                if ($to_write == "") {
                    $to_write = $n_arr[$i]['name'];
                } else {
                    $to_write .= "," . $n_arr[$i]['name'];
                }
            }
        }
        $to_write .= $to_write . "\n";
        for ($i = 0; $i < count($n_arr); $i++) {
            $balise = $n_arr[$i];
            $to_write .= $balise['name'] . "=" . $balise['type'] . "\n";
            $to_write .= $balise['name'] . "-class=" . $balise['class'] . "\n";
            $to_write .= $balise['name'] . "-content=" . $balise['content'] . "\n";
            $to_write .= $balise['name'] . "-parent=" . $balise['parent'] . "\n";
            $to_write .= $balise['name'] . "-children=" . $balise['children'] . "\n";
            if ($balise['type'] == "input") {
                $to_write .= $balise['name'] . "-itype=" . $balise['itype'] . "\n";
                $to_write .= $balise['name'] . "-readonly=" . $balise['readonly'] . "\n";
                $to_write .= $balise['name'] . "-placeholder=" . $balise['placeholder'] . "\n";
                $to_write .= $balise['name'] . "-value=" . $balise['value'] . "\n";
                $to_write .= $balise['name'] . "-iname=" . $balise['iname'] . "\n";
            } else if ($balise['type'] == "img") {
                $to_write .= $balise['name'] . "-src=" . $balise['src'] . "\n";
            } else if ($balise['type'] == "form") {
                $to_write .= $balise['name'] . "-method=" . $balise['method'] . "\n";
            } else if ($balise['type'] == "a") {
                $to_write .= $balise['name'] . "-link=" . $balise['link'] . "\n";
            }
        }

        $path = "KW/public/pages/" . $_SESSION['editName'] . ".conf";
        $f = fopen($path, "w");
        if ($f) {
            fwrite($f, $to_write, strlen($to_write));
        }
        fclose($f);
    }

    public static function updateElement(string $name, array $arr) {
        if (count($arr) <= 0)
            return;
        if (!isset($_SESSION['editName'])) {
            return;
        }
        $elements = array();
        $nameElems = self::getElementsName();
        $to_write = "";

        for ($i = 0; $i < count($nameElems); $i++) {
            if ($nameElems[$i] != $name) {
                array_push($elements, self::genArrayElements($nameElems[$i]));
            }
        }

        for ($i = 0; $i < count($elements); $i++) {
            if (isset($n_arr[$i])) {
                if ($n_arr[$i]['name'] == $name) {
                    $n_arr[$i]['name'] = $arr['name'];
                }
                if ($to_write == "") {
                    $to_write = $n_arr[$i]['name'];
                } else {
                    $to_write .= "," . $n_arr[$i]['name'];
                }
            }
        }
        $to_write .= $to_write . "\n";
        for ($i = 0; $i < count($elements); $i++) {
            $balise = $elements[$i];
            if ($balise['name'] == $name) {
                $to_write .= $arr['name'] . "=" . $balise['type'] . "\n";
                $to_write .= $arr['name'] . "-class=" . $arr['class'] . "\n";
                $to_write .= $arr['name'] . "-content=" . $arr['content'] . "\n";
                $to_write .= $arr['name'] . "-parent=" . $balise['parent'] . "\n";
                $to_write .= $arr['name'] . "-children=" . $balise['children'] . "\n";
                if ($balise['type'] == "input") {
                    $to_write .= $arr['name'] . "-itype=" . $balise['itype'] . "\n";
                    $to_write .= $arr['name'] . "-readonly=" . $arr['readonly'] . "\n";
                    $to_write .= $arr['name'] . "-placeholder=" . $arr['placeholder'] . "\n";
                    $to_write .= $arr['name'] . "-value=" . $arr['value'] . "\n";
                    $to_write .= $arr['name'] . "-iname=" . $balise['iname'] . "\n";
                } else if ($balise['type'] == "img") {
                    $to_write .= $arr['name'] . "-src=" . $balise['src'] . "\n";
                } else if ($balise['type'] == "form") {
                    $to_write .= $arr['name'] . "-method=" . $balise['method'] . "\n";
                } else if ($balise['type'] == "a") {
                    $to_write .= $arr['name'] . "-link=" . $balise['link'] . "\n";
                }
            } else {
                $balise = $elements[$i];
                $to_write .= $balise['name'] . "=" . $balise['type'] . "\n";
                $to_write .= $balise['name'] . "-class=" . $balise['class'] . "\n";
                $to_write .= $balise['name'] . "-content=" . $balise['content'] . "\n";
                $to_write .= $balise['name'] . "-parent=" . $balise['parent'] . "\n";
                $to_write .= $balise['name'] . "-children=" . $balise['children'] . "\n";
                if ($balise['type'] == "input") {
                    $to_write .= $balise['name'] . "-itype=" . $balise['itype'] . "\n";
                    $to_write .= $balise['name'] . "-readonly=" . $balise['readonly'] . "\n";
                    $to_write .= $balise['name'] . "-placeholder=" . $balise['placeholder'] . "\n";
                    $to_write .= $balise['name'] . "-value=" . $balise['value'] . "\n";
                    $to_write .= $balise['name'] . "-iname=" . $balise['iname'] . "\n";
                } else if ($balise['type'] == "img") {
                    $to_write .= $balise['name'] . "-src=" . $balise['src'] . "\n";
                } else if ($balise['type'] == "form") {
                    $to_write .= $balise['name'] . "-method=" . $balise['method'] . "\n";
                } else if ($balise['type'] == "a") {
                    $to_write .= $balise['name'] . "-link=" . $balise['link'] . "\n";
                }
            }
        }
        
    }

    public static function getAllCssJsContent($fileName):array {
        $res = array(
            "css" => "",
            "js" => ""
        );
        $f = fopen("KW/public/ressources/css/" . $fileName . ".css", "r");
        if ($f) {
            $res['css'] = fread($f, filesize("KW/public/ressources/css/" . $fileName . ".css"));
        }
        fclose($f);
        $f = fopen("KW/public/ressources/js/" . $fileName . ".js", "r");
        if ($f) {
            $res['js'] = fread($f, filesize("KW/public/ressources/js/" . $fileName . ".js"));
        }
        fclose($f);
        return $res;
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

    public static function genArrayElements($elemName):array {
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
            "type" => preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, $elemName)),
            "class" => preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, $elemName . "-class")),
            "parent" => preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, $elemName . "-parent")),
            "children" => preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, $elemName . "-children")),
            "content" => preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, $elemName . "-content")),
        );

        if ($res['type'] == "img") {
            $res = array_merge($res, array("src" => $cf->getValueFromKeyConf($path, $elemName . "-src")));
        } else if ($res['type'] == "input") {
            $inputArray = array(
                "itype" => preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, $elemName . "-itype")),
                "placeholder" => preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, $elemName . "-placeholder")),
                "readonly" => preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, $elemName . "-readonly")),
                "value" => preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, $elemName . "-value")),
                "iname" => preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, $elemName . "-iname")),
            );
            $res = array_merge($res, $inputArray);
        } else if ($res['type'] == "form") {
            $formArray = array(
                "method" => preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, $elemName . "-method")),
            );
            $res = array_merge($res, $formArray);
        }

        return $res;
    }

    private static function getElementCanBeAdded($type):array {
        $res = array();
        if ($type == "form") {

        }
        return $res;
    }

    public static function getSelectAdded($type):string {
        $res = "";
        $res .= "<optgroup label=\"HTML Basics\">";
        $res .= "<optgroup label=\"Text\">";
        $res .= "<option value=\"h1\">H1</option>";
        $res .= "<option value=\"h2\">H2</option>";
        $res .= "<option value=\"h3\">H3</option>";
        $res .= "<option value=\"h4\">H4</option>";
        $res .= "<option value=\"h5\">H5</option>";
        $res .= "<option value=\"h6\">H6</option>";
        $res .= "<option value=\"p\">P</option>";
        $res .= "</optgroup>";
        $res .= "<optgroup label=\"Action\">";
        $res .= "<option value=\"a\">A</option>";
        $res .= "<option value=\"button\">Button</option>";
        if ($type != "form") {
            $res .= "<option value=\"form\">Form</option>";
        }
        $res .= "</optgroup>";
        $res .= "<optgroup label=\"Media\">";
        $res .= "<option value=\"source\">Source</option>";
        $res .= "<option value=\"picture\">Picture</option>";
        $res .= "</optgroup>";
        $res .= "<optgroup label=\"Navigation\">";
        $res .= "<option value=\"table\">Table</option>";
        $res .= "<option value=\"nav\">Nav</option>";
        $res .= "<option value=\"div\">Div</option>";
        $res .= "</optgroup>";
        $res .= "</optgroup>";
        if ($type == "form" || $type == "select" || $type == "nav" || $type == "source" || $type == "picture") {
            $res .= "<optgroup label=\"====\">";
            $res .= "</optgroup>";
            $res .= "<optgroup label=\"Specific\">";
            if ($type == "form") {
                $res .= "<option value=\"input\">Input</option>";
                $res .= "<option value=\"select\">Select</option>";
                $res .= "<option value=\"label\">Label</option>";
            }
            if ($type == "select") {
                $res .= "<option value=\"optgroup\">Optgroup</option>";
                $res .= "<option value=\"option\">Option</option>";
            }
            $res .= "</optgroup>";
        }
        return $res;
    }

    private static function getDivEditorBalise($arr, $name, $editable=true):string {
        $res = "";
        for ($i = 0; $i < count($arr); $i++) {
            if ($arr[$i]['name'] == $name) {
                $balise = $arr[$i];
                $children = explode(",", $balise['children']);
                
                $res .= "<div class=\"editDiv\">\n";
                $res .= "\t<div class=\"headerEdit\">\n";
                $res .= "\t\t<h3>" . $balise['name'] . "</h3>\n";
                $res .= "\t</div>\n";
                $res .= "<h4>Type : " . $balise['type'] . "</h4>\n";
                $res .= "<h4>Name :</h4>\n";
                $res .= "<input type=\"text\" value=\"" . $balise['name'] . "\" name=\"name-" . $balise['name'] . "\">\n";
                $res .= "<h4>Content :</h4>";
                $res .= "<textarea class=\"contentEdit\">" . $balise['content'] . "</textarea>\n";
                $res .= "<h4>Special Edit :</h4>\n";
                if ($balise['type'] == "img") {
                    $res .= "<input type=\"text\" placeholder=\"Source...\" value=\"" . str_replace("\"", "'", $balise['src']) . "\">\n";
                } else if ($balise['type'] == "input") {

                } else {
                    $res .= "<p>No special edit</p>\n";
                }
                $res .= "\t<div class=\"footerEdit\">\n";
                if ($editable == true) {
                    $res .= "\t\t<input type=\"submit\" value=\"Save\" name=\"save-" . $balise['name'] . "\">\n";
                    $res .= "\t\t<input type=\"submit\" value=\"Delete\" name=\"delete-" . $balise['name'] . "\">\n";
                }
                $res .= "\t\t<select name=\"addElement-" . $balise['name'] . "\">";
                $res .= self::getSelectAdded($balise['type']);
                $res .= "\t\t</select>";
                $res .= "\t</div>\n";
                if (count($children) > 0 || $balise['children'] != "") {
                    $res .= "<h3>Children :</h3>\n";
                    for ($i = 0; $i < count($children); $i++) {
                        if ($children[$i] != "") {
                            $res .= self::getDivEditorBalise($arr, $children[$i], true);
                        }
                    }
                }
                $res .= "</div>\n";
                break;
            }
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

    private static function getBaliseFromName($arr, $name):array {
        for ($i = 0; $i < count($arr); $i++) {
            if ($arr[$i]['name'] == $name) {
                return $arr[$i];
            }
        }
        return array();
    }

    private static function isInType($arr, $parent, $type):bool {
        if ($parent == "") {
            return false;
        } else {
            $balise = self::getBaliseFromName($arr, $parent);
            if (count($balise) == 0)
                return false;
            if ($balise['type'] == $type) {
                return true;
            }
            return self::isInType($arr, $balise['parent'], $type);
        }
        return false;
    }

    private static function canBeDisplay($arr, $balise):bool {
        if ($balise['type'] == "input") {
            if (self::isInType($arr, $balise['parent'], "form")) {
                return true;
            }
            return false;
        }
        if ($balise['type'] == "label") {
            if (self::isInType($arr, $balise['parent'], "form")) {
                return true;
            }
            return false;
        }
        if ($balise['type'] == "select") {
            if (self::isInType($arr, $balise['parent'], "form")) {
                return true;
            }
            return false;
        }
        if ($balise['type'] == "option") {
            if (self::isInType($arr, $balise['parent'], "select")) {
                return true;
            }
            return false;
        }
        if ($balise['type'] == "fieldset") {
            if (self::isInType($arr, $balise['parent'], "form")) {
                return true;
            }
            return false;
        }
        if ($balise['type'] == "legend") {
            if (self::isInType($arr, $balise['parent'], "fieldset")) {
                return true;
            }
            return false;
        }
        return true;
    }

    private static function getHtmlStringBalise($arr, $parent) {
        global $cf, $hlp, $db;
        $res = "";
        for ($i = 0; $i < count($arr); $i++) {
            $balise = $arr[$i];
            if (self::canBeDisplay($arr, $balise) == true) {
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
                    if ($balise['type'] == "input") {
                        $res .= " type=\"" . $balise['itype'] . "\"";
                        $res .= " placeholder=\"" . $balise['placeholder'] . "\"";
                        $res .= " value=\"" . $balise['value'] . "\"";
                        $res .= " name=\"" . $balise['iname'] . "\"";
                        $res .= " readonly=\"" . $balise['readonly'] . "\"";
                    }
                    if ($balise['type'] == "form") {
                        $res .= " method=\"" . $balise['method'] . "\"";
                    }
                    if (self::isBaliseAutoClose($balise['type'])) {
                        $res .= ">";
                    } else {
                        $res .= ">\n" . $balise['content'] . "\n" . self::getHtmlStringBalise($arr, $balise['name']) . "\n";
                        $res .= "</" . $balise['type'] . ">\n";
                    }
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
        $resStr = "";
        $res = array();
        $nameElems = self::getElementsName();
        for ($i = 0; $i < count($nameElems); $i++) {
            array_push($res, self::genArrayElements($nameElems[$i]));
        }
        $resStr .= self::getHtmlStringBalise($res, "body");
        return $resStr;
    }
}
?>