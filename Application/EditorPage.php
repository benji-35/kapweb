<?php
namespace Application;

use Application\ConfFiles;

use function PHPSTORM_META\type;

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

    private static $representationTypeElement = array(
        "div" => "bx bx-window",
        "h1" => "bx bx-text",
        "h2" => "bx bx-text",
        "h3" => "bx bx-text",
        "h4" => "bx bx-text",
        "h5" => "bx bx-text",
        "h6" => "bx bx-text",
        "p" => "bx bx-text",
        "a" => "bx bx-link",
        "img" => "bx bx-image-alt",
        "source" => "bx bx-collapse",
        "audio" => "bx bxs-music",
        "video" => "bx bxs-video-recording",
        "button" => "bx bxs-joystick-button",
        "i" => "bx bxs-inbox",
        "form" => "bx bxs-paper-plane",
        "input" => "bx bxs-edit-alt",
        "textarea" => "bx bxs-edit-alt",
        "picture" => "bx bxs-image-alt",
        "table" => "bx bx-table",
        "nav" => "bx bxs-navigation",
        "ul" => "bx bx-list-ul",
        "li" => "bx bx-list-ol",
        "select" => "bx bx-checkbox-checked",
        "option" => "bx bx-bar-chart",
        "optgroup" => "bx bx-bar-chart-square",
        "label" => "bx bxs-label",
    );

    private static $representationTypeElementUnknown = "bx bxs-extension";

    private static $extensionsElementsGlob = array();

    public function __construct() {}

    public function initEditor($cf, $hlp) {
        global $cf, $hlp;
        self::$cf = $cf;
        self::$hlp = $hlp;
    }

    public static function getUnknownIconElement():string {
        return self::$representationTypeElementUnknown;
    }

    private static function stringOfElemsArr(array $arr, array $names):string {
        global $ext;
        if (count($arr) <= 0)
            return "";
        $to_write = "";
        for ($i = 0; $i <= count($names); $i++) {
            if (isset($names[$i])) {
                if ($to_write == "") {
                    $to_write = "elements=" . $names[$i];
                } else {
                    $to_write .= "," . $names[$i];
                }
            }
        }
        $to_write .= "\n";
        for ($i = 0; $i < count($arr); $i++) {
            if (isset($arr[$i])) {
                $balise = $arr[$i];
                $to_write .= $balise['name'] . "=" . $balise['type'] . "\n";
                $to_write .= $balise['name'] . "-class=" . $balise['class'] . "\n";
                $to_write .= $balise['name'] . "-content=" . $balise['content'] . "\n";
                $to_write .= $balise['name'] . "-parent=" . $balise['parent'] . "\n";
                $to_write .= $balise['name'] . "-children=" . $balise['children'] . "\n";
                if ($ext->isExtensionBaliseType($balise['type'])) {
                    $vars = $ext->getVarsFromFrontElement($balise['type']);
                    for ($j = 0; $j < count($vars); $j++) {
                        if ($vars[$j] != "") {
                            $to_write .= $balise['name'] . "-" . $vars[$j] . "=" . $balise[$vars[$j]] . "\n";
                        }
                    }
                } else if ($balise['type'] == "input") {
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
                    $to_write .= $balise['name'] . "-target=" . $balise['target'] . "\n";
                }
            }
        }
        return $to_write;
    }

    public static function addElement($name, $type, $parent="body", $class="", $content="", int $position=-1) {
        global $ext;
        if (!isset($_SESSION['editName'])) {
            return;
        }
        $elements = array();
        $nameElems = self::getElementsName();
        for ($i = 0; $i < count($nameElems); $i++) {
            array_push($elements, self::genArrayElements($nameElems[$i]));
        }
        array_push($nameElems, $name);
        for ($i = 0; $i < count($nameElems); $i++) {
            if (isset($nameElems[$i]) && $nameElems[$i] == $parent) {
                $children = $elements[$i]['children'];
                if ($children == "") {
                    $children = $name;
                } else {
                    if ($position == -1) {
                        $children .= "," . $name;
                    } else {
                        $childrenList = explode(",", $children);
                        $nchildrenList = array();
                        if ($position >= count($childrenList)) {
                            array_push($nchildrenList, $name);
                        } else {
                            $currId = 0;
                            foreach ($childrenList as $childList) {
                                if ($currId == $position) {
                                    array_push($nchildrenList, $name);
                                }
                                array_push($nchildrenList, $childList);
                                $currId++;
                            }
                        }
                        $children = "";
                        foreach ($nchildrenList as $child) {
                            if ($children == "") {
                                $children = $child;
                            } else {
                                $children .= "," . $child;
                            }
                        }
                    }
                }
                $elements[$i]['children'] = $children;
                break;
            }
        }
        $to_write = self::stringOfElemsArr($elements, $nameElems);
        $to_write .= "\n";
        $to_write .= $name . "=" . $type . "\n";
        $to_write .= $name . "-class=" . $class . "\n";
        $to_write .= $name . "-content=" . $content . "\n";
        $to_write .= $name . "-parent=" . $parent . "\n";
        $to_write .= $name . "-children=\n";
        if ($ext->isExtensionBaliseType($type)) {
            $vars = $ext->getVarsFromFrontElement($type);
            for ($j = 0; $j < count($vars); $j++) {
                $to_write .= $name . "-" . $vars[$j] . "=\n";
            }
        } else if ($type == "a") {
            $to_write .= $name . "-link=\n";
            $to_write .= $name . "-target=\n";
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
        for ($i = 0; $i <= count($arr); $i++) {
            if (isset($arr[$i])) {
                if ($arr[$i]['name'] == $name) {
                    $children = $arr[$i]['children'];
                    unset($arr[$i]);
                    break;
                }
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

    public static function deleteElement($name) {
        if (!isset($_SESSION['editName'])) {
            return;
        }
        $elements = array();
        $nameElems = self::getElementsName();

        for ($i = 0; $i <= count($nameElems); $i++) {
            if ($nameElems[$i] != $name) {
                array_push($elements, self::genArrayElements($nameElems[$i]));
            } else {
                unset($nameElems[$i]);
            }
        }
        $n_arr = self::getDeletedStr($elements, $name);

        $to_write = self::stringOfElemsArr($n_arr, $nameElems);

        $to_write = str_replace($name . ",", "", $to_write);
        $to_write = str_replace("," . $name, "", $to_write);
        $to_write = str_replace($name, "", $to_write);

        $path = "KW/public/pages/" . $_SESSION['editName'] . ".conf";
        $f = fopen($path, "w");
        if ($f) {
            fwrite($f, $to_write, strlen($to_write));
        }
        fclose($f);
    }

    public static function resetElement($name) {
        if (!isset($_SESSION['editName'])) {
            return;
        }
        $elements = array();
        $nameElems = self::getElementsName();
        $to_write = "";
        $children = "";

        for ($i = 0; $i < count($nameElems); $i++) {
            $arrGen = self::genArrayElements($nameElems[$i]);
            if ($nameElems[$i] == $name) {
                $children = $arrGen['children'];
                $arrGen['children'] = "";
            }
            array_push($elements, $arrGen);
        }
        $arrChildren = explode(",", $children);
        for ($i = 0; $i < count($arrChildren); $i++) {
            $elements = self::getDeletedStr($elements, $arrChildren[$i]);
        }
        for ($i = 0; $i <= count($elements); $i++) {
            if (isset($elements[$i])) {
                if ($to_write == "") {
                    $to_write = "elements=" . $elements[$i]['name'];
                } else {
                    $to_write .= "," . $elements[$i]['name'];
                }
            }
        }
        $to_write .= "\n";
        for ($i = 0; $i <= count($elements); $i++) {
            if (isset($elements[$i])) {
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
                    $to_write .= $balise['name'] . "-target=" . $balise['target'] . "\n";
                }
            }
        }
        $path = "KW/public/pages/" . $_SESSION['editName'] . ".conf";
        $f = fopen($path, "w");
        if ($f) {
            fwrite($f, $to_write, strlen($to_write));
        }
        fclose($f);
    }

    public static function updateArraySaveFromExtensions(array $currArr, array $balise):array {
        global $ext;
        if ($ext->isExtensionBaliseType($balise['type'])) {
            $vars = $ext->getVarsFromFrontElement($balise['type']);
            for ($i = 0; $i < count($vars); $i++) {
                $postName = $balise['name'] . "-" . $vars[$i];
                if (isset($_POST[$postName])) {
                    $currArr = array_merge($currArr, array($vars[$i] => $_POST[$postName]));
                }
            }
        }
        return $currArr;
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
                if ($to_write != "") {
                    $to_write .= "," . $nameElems[$i];
                } else {
                    $to_write = "elements=" . $nameElems[$i];
                }
            } else {
                $arrGet = self::genArrayElements($nameElems[$i]);
                $keys = array_keys($arrGet);
                for ($x = 0; $x < count($keys); $x++) {
                    if (key_exists($keys[$x], $arr)) {
                        $arrGet[$keys[$x]] = $arr[$keys[$x]];
                    }
                }
                array_push($elements, $arrGet);
            }
        }

        $to_write .= "," . $arr['name'];

        for ($i = 0; $i < count($elements); $i++) {
            if (isset($n_arr[$i])) {
                if ($n_arr[$i]['name'] == $name) {
                    $n_arr[$i]['name'] = $arr['name'];
                }
            }
        }
        $to_write .= "\n";
        for ($i = 0; $i < count($elements); $i++) {
            $balise = $elements[$i];
            $keysToSave = array_keys($balise);
            for ($j = 0; $j < count($keysToSave); $j++) {
                $key = $keysToSave[$j];
                if ($key != "name") {
                    if ($key == "type") {
                        $to_write .= $balise['name'] . "=" . $balise[$key] . "\n";
                    } else {
                        $to_write .= $balise['name'] . "-" . $key . "=" . $balise[$key] . "\n";
                    }
                }
            }
        }
        $path = "KW/public/pages/" . $_SESSION['editName'] . ".conf";
        $f = fopen($path, "w");
        if ($f) {
            fwrite($f, $to_write, strlen($to_write));
        }
        fclose($f);
    }

    public static function getAllCssJsContent($fileName):array {
        $res = array(
            "css" => NULL,
            "js" => NULL
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

    public static function getElementsName():array {
        if (!isset($_SESSION['editName'])) {
            return array();
        }
        global $cf;
        $path = "KW/public/pages/" . $_SESSION['editName'] . ".conf";
        $elemReaded = preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, "elements"));
        return explode(",", $elemReaded);
    }

    public static function genArrayElements($elemName):array {
        global $cf, $ext;
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
        $typeGet = preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, $elemName));
        $res = array(
            "name" => $elemName,
            "type" => $typeGet,
            "class" => preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, $elemName . "-class")),
            "parent" => preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, $elemName . "-parent")),
            "children" => preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, $elemName . "-children")),
            "content" => preg_replace('/\p{C}+/u', "", $cf->getValueFromKeyConf($path, $elemName . "-content")),
        );

        if ($ext->isExtensionBaliseType($res['type'])) {
            $vars = $ext->getVarsFromFrontElement($res['type']);
            for ($i = 0; $i < count($vars); $i++) {
                if ($vars[$i] != "") {
                    $res = array_merge($res, array($vars[$i] => $cf->getValueFromKeyConf($path, $elemName . "-" . $vars[$i])));
                }
            }
        } else if ($res['type'] == "img") {
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
        } else if ($res['type'] == "source") {
            $res = array_merge($res, array("src" => $cf->getValueFromKeyConf($path, $elemName . "-src")));
        } else if ($res['type'] == "a") {
            $res = array_merge($res, array("link" => $cf->getValueFromKeyConf($path, $elemName . "-link")));
            $res = array_merge($res, array("target" => $cf->getValueFromKeyConf($path, $elemName . "-target")));
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
        global $ext;
        self::$extensionsElementsGlob = $ext->getExtensionsFrontElement();
        $extAdded = self::$extensionsElementsGlob;
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
        $res .= "<option value=\"picture\">Picture</option>";
        $res .= "<option value=\"img\">Image</option>";
        $res .= "<option value=\"audio\">Audio</option>";
        $res .= "<option value=\"video\">Video</option>";
        $res .= "</optgroup>";
        $res .= "<optgroup label=\"Navigation\">";
        $res .= "<option value=\"table\">Table</option>";
        $res .= "<option value=\"nav\">Nav</option>";
        $res .= "<option value=\"div\">Div</option>";
        $res .= "</optgroup>";
        $res .= "</optgroup>";
        if ($type == "form" || $type == "select" || $type == "nav" || $type == "audio" || $type == "video" || $type == "picture") {
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
            if ($type == "audio" || $type == "video" || $type == "picture") {
                $res .= "<option value=\"source\">Source</option>";
            }
            $res .= "</optgroup>";
        }
        
        for ($i = 0; $i < count($extAdded); $i++) {
            $extName = $extAdded[$i]['name'];
            $extAuthor = $extAdded[$i]['author'];
            $extElems = $extAdded[$i]['elems'];
            if (count($extElems) > 0) {
                $dependenciesElems = array();
                $res .= '<optgroup label="=-=-="></optgroup>';
                $res .= '<optgroup label="' . $extName . ' - ' . $extAuthor . '">';
                for ($elemId = 0; $elemId < count($extElems); $elemId++) {
                    $elem = $extElems[$elemId];
                    $elemName = $elem['name'];
                    if ($elem['dependencies'] == "" || strlen($elem['dependencies']) <= 0) {
                        $res .= '<option value="' . $elemName . '">' . $elemName . '</option>';
                    } else {
                        $dependencies = explode(",", $elem['dependencies']);
                        for ($depId = 0; $depId < count($dependencies); $depId++) {
                            $dependecy = $dependencies[$depId];
                            echo 'Type = "' . $type . '", dependency="' . $dependecy . '"' . "\n";
                            if ($dependecy != "" && $type==$dependecy) {
                                if (array_key_exists($dependecy, $dependenciesElems)) {
                                    array_push($dependenciesElems[$dependecy], $elem['name']);
                                } else {
                                    $dependenciesElems = array($dependecy => array($elem['name']));
                                }
                            }
                        }
                    }
                }
                if (count($dependenciesElems) > 0) {
                    $dependenciesNames = array_keys($dependenciesElems);
                    for ($keyId = 0; $keyId < count($dependenciesNames); $keyId++) {
                        $nameDependency = $dependenciesNames[$keyId];
                        $res .= '<optgroup label="===="></optgroup>';
                        $res .= '<optgroup label="Specifics">';
                        for ($x = 0; $x < count($dependenciesElems[$nameDependency]); $x++) {
                            $nameElem = $dependenciesElems[$nameDependency][$x];
                            $res .= '<option value="' . $nameElem . '">' . $nameElem . '</option>';
                        }
                        $res .= "</optgroup>";
                    }
                }
                $res .= '</optgroup>';
            }
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

    public static function isBaliseAutoClose($type):bool {
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
        global $cf, $hlp, $db, $ext;
        $res = "";
        $sizeArr = count($arr);
        for ($i = 0; $i < $sizeArr; $i++) {
            $balise = $arr[$i];
            if (self::canBeDisplay($arr, $balise) == true) {
                if ($balise['parent'] == $parent) {
                    if ($ext->isExtensionBaliseType($balise['type'])) {
                        $res .= $ext->getHtmlExtensionFromBalise($balise['type'], $balise['name']);
                    } else {
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
                        if ($balise['type'] == "a") {
                            $res .= " target=\"" . $balise['target'] . "\"";
                            $res .= " href=\"" . $balise['link'] . "\"";
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
        }
        return $res;
    }

    private static function readSortElemPage(array $elems, string $parent, array $currArr):array {
        $res = $currArr;
        $nParents = array();

        foreach($elems as $i => $elem) {
            if (isset($elem)) {
                if ($elem['name'] == $parent) {
                    if (isset($elem['children']) && $elem['children'] != "") {
                        $children = explode(",", $elem['children']);
                        $nParents = $children;
                    }
                    array_push($res, $elem);
                    unset($elems[$i]);
                }
            }
        }
        foreach ($nParents as $nPar) {
            $res = self::readSortElemPage($elems, $nPar, $res);
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
        $res = self::readSortElemPage($res, "body", array());
        $resStr .= self::getHtmlStringBalise($res, "body");
        return $resStr;
    }

    public static function generatePhpCode() {
        global $cf, $hlp, $ext;
        if (!isset($_SESSION['editName'])) {
            return;
        }
        if (file_exists("KW/public/tmpPhpPage.php")) {
            $f = fopen("KW/public/tmpPhpPage.php", "w");
            if ($f) {
                fwrite($f, "", 0);
            }
            fclose($f);
        }
        $res = "<?php\n";
        $nameElems = self::getElementsName();
        for ($i = 0; $i < count($nameElems); $i++) {
            $balise = self::genArrayElements($nameElems[$i]);
            $toRequire = $ext->getFrontBackPaheFromElement($balise['type'], $balise);
            if ($toRequire != "") {
                $toRequire = str_replace("<?php", "", $toRequire);
                $toRequire = str_replace("?>", "", $toRequire);
                $res .= $toRequire . "\n";
            }
        }
        $res .= "?>";
        if (file_exists("KW/public/tmpPhpPage.php")) {
            $f = fopen("KW/public/tmpPhpPage.php", "w");
            if ($f) {
                fwrite($f, $res, strlen($res));
            }
            fclose($f);
        }
        require "KW/public/tmpPhpPage.php";
    }

    public static function saveCssJs(array $inputs) {
        if (!isset($_SESSION['editName'])) {
            return;
        }
        $css= "";
        $js = "";
        if (isset($inputs['css'])) {
            $css = $inputs['css'];
        }
        if (isset($inputs['js'])) {
            $js = $inputs['js'];
        }
        if ($css != "") {
            $path = "KW/public/ressources/css/" . $_SESSION['editName'] . ".css";
            $f = fopen($path, "w");
            if ($f) {
                fwrite($f, $css, strlen($css));
            }
            fclose($f);
        }
        if ($js != "") {
            $path = "KW/public/ressources/js/" . $_SESSION['editName'] . ".js";
            $f = fopen($path, "w");
            if ($f) {
                fwrite($f, $js, strlen($js));
            }
            fclose($f);
        }
    }

    public static function getSpecificsOptions(array $balise):string {
        global $ext;
        $type = $balise['type'];
        $nameNoSpacing = str_replace(" ", "_", $balise['name']);
        if ($type == "input") {
            return '<label>Readonly :</label>
            <input type="checkbox" name="readonly-'. $nameNoSpacing . '">
            <input type="text" placeholder="Placeholder..." value="' . $balise['placeholder'] . '" name="chgPh-' . $nameNoSpacing . '">
            <input type="text" placeholder="Value..." value="' . $balise['value'] . '" name="chgIVal-' . $nameNoSpacing . '">';
        }
        if ($type == "img") {
            return '<input type="text" placeholder="Source of image..." value="' . str_replace("\"", "'", $balise['src']) . '" name="imgSrc-' . $nameNoSpacing . '">';
        }
        if ($type == "source") {
            return '<input type="text" placeholder="Source..." value="' . str_replace("\"", "'", $balise['src']) . '" name="imgSrc-' . $nameNoSpacing . '">';
        }
        if ($type == "a") {
            return '<input type="text" placeholder="Link..." value="' . str_replace("\"", "'", $balise['link']) . '" name="chgLink-' . $nameNoSpacing . '">' . 
            '<input type="text" placeholder="Target..." value="' . str_replace("\"", "'", $balise['target']) . '" name="chgTarget-' . $nameNoSpacing . '">';
        }
        if ($type == "p" || $type == "h1" || $type == "h2" || $type == "h3" || $type == "h4" || $type == "h5" || $type == "h6") {
            return "<textarea class=\"contentTextValue\" name=\"chgContent-$nameNoSpacing\">" . $balise['content'] . "</textarea>";
        }
        if ($ext->isExtensionBaliseType($balise['type'])) {
            $vars = $ext->getVarsFromFrontElement($balise['type']);
            $editPagePath = $ext->getPageEditHtmlFromElement($balise['type']);
            $res = "";
            if (file_exists($editPagePath)) {
                $f = fopen($editPagePath, "r");
                if ($f) {
                    $size = filesize($editPagePath);
                    if ($size > 0) {
                        $res = fread($f, $size);
                    }
                }
                fclose($f);
            }
            for ($i = 0; $i < count($vars); $i++) {
                if ($vars[$i] != "") {
                    $res = str_replace("\$kw['" . $vars[$i] . "']", $nameNoSpacing . "-" . $vars[$i], $res);
                    $currVal = $balise[$vars[$i]];
                    $res = str_replace("\$kw['get-" . $vars[$i] . "']", $currVal, $res);
                }
            }
            return $res;
        }
        return '';
    }

    private static function getElemsFromParent(string $parentName, array $elems):array {
        $res = array();
        $nElems = $elems;
        foreach ($elems as $i => $elem) {
            if ($elem['parent'] == $parentName) {
                unset($nElems[$i]);
                array_push($res, array(
                    "name" => $elem['name'],
                    "type" => $elem['type'],
                    "class" => $elem['class'],
                    "parent" => $elem['parent'],
                    "children" => self::getElemsFromParent($elem['name'], $nElems),
                    "content" => $elem['content'],
                ));
            }
        }
        return $res;
    }

    public static function sortElems(array $elems):array {
        return self::getElemsFromParent("", $elems);
    }

    private static function getIconHtmlElement(string $type) {
        if (array_key_exists($type, self::$representationTypeElement)) {
            return self::$representationTypeElement[$type];
        } else {
            return self::$representationTypeElementUnknown;
        }
    }

    private static function getElemsFromParentReturnHtml(string $parentName, array $elems, int $sizeBtn):string {
        global $ext;
        $res = "";
        $nElems = $elems;
        $nSize = $sizeBtn - 5;
        foreach ($elems as $i => $elem) {
            if ($elem['name'] == $parentName) {
                unset($nElems[$i]);
                $baliseArray = self::genArrayElements($elem['name']);
                $icon = self::getIconHtmlElement($baliseArray['type']);
                if ($ext->isExtensionBaliseType($baliseArray['type'])) {
                    $icon = $ext->getIconFromElement($baliseArray['type']);
                }
                $nameNoSpacing = str_replace(" ", "_", $elem['name']);
                if (isset($elem['children']) && $elem['children'] != "") {
                    $children = explode(",",$elem['children']);
                    $res .= "<button style=\"width: $sizeBtn%\" class=\"btnNavElem\" onclick=\"openNavBar('navMenu-" . $nameNoSpacing . "', 'editMenu-" . $nameNoSpacing . "', 'iconNavMenu-" . $nameNoSpacing . "')\"><i class=\"$icon textNavMenu\"></i><p class=\"textNavMenu\">". $elem['name'] . "</p><i id=\"iconNavMenu-" . $nameNoSpacing . "\" class=\"bx bxs-down-arrow iconNavBar\"></i></button>";
                    $res .= "<div style=\"display: none;\" id=\"navMenu-" . $nameNoSpacing . "\">";
                    foreach ($children as $child) {
                        $res .= self::getElemsFromParentReturnHtml($child, $nElems, $nSize);
                    }
                    $res .= "</div>";
                } else {
                    $res .= "<button style=\"width: $sizeBtn%\" class=\"btnNavElem\" onclick=\"openEditMenu('editMenu-" . $nameNoSpacing . "')\"><i class=\"$icon textNavMenu\"></i><p class=\"textNavMenu\">". $elem['name'] . "</p></button>";
                }
            }
        }
        return $res;
    }

    public static function sortElemsAndGetHtml(array $elems):string {
        return self::getElemsFromParentReturnHtml("body", $elems, 100);
    }

    public static function getAllEditMenus(array $elems):string {
        global $hlp, $ext;
        $res = "";
        $saveWord = $hlp->getLangWorldMainFile("w-save", "Save");
        $deleteWorld = $hlp->getLangWorldMainFile("w-delete", "Delete");
        $resetWord = $hlp->getLangWorldMainFile("w-reset", "Reset");
        $addWord = $hlp->getLangWorldMainFile("w-add", "To Add");
        $cancelWord = $hlp->getLangWorldMainFile("w-cancel", "Cancel");
        $nameWord = $hlp->getLangWorldMainFile("w-name", "Name");
        foreach ($elems as $elem) {
            $selectPos = "<option value=\"0\">Avant tous les enfants</option>";
            $children = explode(",", $elem['children']);
            foreach ($children as $i => $child) {
                if ($child != "") {
                    $idPos = $i + 1;
                    $selectPos .= "<option value=\"$idPos\">Après $child</option>";
                }
            }
            $baliseArray = self::genArrayElements($elem['name']);
            $selectPos .= "<option value=\"-1\">à la fin</option>";
            $nameNoSpacing = str_replace(" ", "_", $elem['name']);
            $type = $elem['type'];
            $selectOptAdd = self::getSelectAdded($type);
            $res .= "<div class=\"editMenu\" id=\"editMenu-" . $nameNoSpacing . "\" style=\"display: none;\"><form method=\"POST\">";
            $res .= "<h3>" . $elem['name'] . "</h3>";
            $res .= "<div class=\"editBarElement\">";
            $res .= "<button class=\"btnEditBarElement\" title=\"$saveWord\" name=\"save-$nameNoSpacing\"><i class='bx bxs-save bx-sm'></i></button>";
            $res .= "<button class=\"btnEditBarElement\" title=\"$resetWord\"><i class='bx bx-reset bx-sm'></i></button>";
            $res .= "<button name=\"delete-$nameNoSpacing\" class=\"btnEditBarElement\" title=\"$deleteWorld\"><i class='bx bxs-trash bx-sm'></i></button>";
            $res .= "<button name=\"reset-$nameNoSpacing\" type=\"button\" class=\"btnEditBarElement\" title=\"$addWord\" onclick=\"showHideAddElem('$nameNoSpacing-formAddElem', true)\"><i class='bx bxs-plus-circle bx-sm'></i></button>";
            $res .= "<div class=\"formAddElement\" id=\"$nameNoSpacing-formAddElem\">";
            $res .= "<select name=\"typeAddChild-$nameNoSpacing\">$selectOptAdd</select>";
            $res .= "<select name=\"positionAddChild-$nameNoSpacing\">$selectPos</select>";
            $res .= "<input type=\"text\" placeholder=\"$nameWord...\" name=\"addChildName-$nameNoSpacing\">";
            $res .= "<input type=\"submit\" value=\"$addWord\" name=\"addChild-$nameNoSpacing\">";
            $res .= "<button type=\"button\" onclick=\"showHideAddElem('$nameNoSpacing-formAddElem', false)\">$cancelWord</button>";
            $res .= "</div>";
            $res .= "</div>";
            $res .= "<div class=\"classEdit\"><h4>calsses : </h4>";
            $res .= "<input id=\"nameNClass-$nameNoSpacing\" type=\"text\" placeholder=\"Enter name of new class...\">";
            $res .= "<button type=\"button\" onclick=\"addClass('$nameNoSpacing-calssArea', 'nameNClass-$nameNoSpacing')\">New Class</button>";
            $classesElems = explode(" ", $elem['class']);
            $inClassTextArea = "";
            $idClass = 0;
            foreach ($classesElems as $classesElem) {
                if (isset($classesElem) && $classesElem != "") {
                    $res .= "<button id=\"$nameNoSpacing-class$idClass\" type=\"button\" onclick=\"removeClass('$nameNoSpacing-calssArea', '$nameNoSpacing-class$idClass', '$classesElem')\" class=\"elemClass\"><p>" . $classesElem . "</p></button>";
                    $inClassTextArea .= $classesElem . "\n";
                    $idClass++;
                }
            }
            $res .= "</div>";
            $res .= "<textarea id=\"$nameNoSpacing-calssArea\" hidden name=\"class-" . $elem['name'] . "\">" . $inClassTextArea . "</textarea>";
            $res .= self::getSpecificsOptions($baliseArray);
            $res .= "</form>";
            $res .= "</div>";
        }
        return $res;
    }
}

?>
