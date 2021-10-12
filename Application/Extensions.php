<?php

namespace Application;

class Extensions {

    private static $pathExtMain = "KW/extensions/ext.conf";
    private static $extensionsPathes = array();
    private static $extensionsName = array();
    private static $extensionList = array();

    private static $extensionListFrontElement = array();

    public function __construct() {}

    private static function initFrontElements(array $extList) {
        global $cf, $hlp;
        $array_ext_elems = array();
        $pathElems = $extList['path'] . "/front/front-elements.conf";
        $elemsExt = $cf->getValueFromKeyConf($pathElems, "front-elements");

        if ($elemsExt != "") {
            $elemsArr = explode(",", $elemsExt);
            for ($i = 0; $i < count($elemsArr); $i++) {
                $elem = $elemsArr[$i];
                if ($elem != "") {
                    $elemArr = array(
                        "name" => $elem,
                        "nb-specif" => $cf->getValueFromKeyConf($pathElems, $elem),
                        "dependencies" => $cf->getValueFromKeyConf($pathElems, $elem . "-dependencies"),
                        "front" => $cf->getValueFromKeyConf($pathElems, $elem . "-front"),
                        "back" => $cf->getValueFromKeyConf($pathElems, $elem . "-back"),
                        "css" => $cf->getValueFromKeyConf($pathElems, $elem . "-css"),
                        "path-options" => $pathElems,
                        "own-path" => $extList['path'] . "/front/elements/" . $elem,
                    );
                    array_push($array_ext_elems, $elemArr);
                }
            }
        }
        if ($extList['use'] == "true") {
            array_push(self::$extensionListFrontElement, array("name" => $extList['name'], "author" =>  $extList['author'], "elems" => $array_ext_elems));
        }
    }

    public static function init_extensions() {
        global $cf, $hlp;

        $extensionsStr = $cf->getValueFromKeyConf(self::$pathExtMain, "list-ext");

        if ($extensionsStr != "") {
            $extensions = explode(",", $extensionsStr);
            for ($i = 0; $i < count($extensions); $i++) {
                if ($extensions[$i] != "") {
                    array_push(self::$extensionsPathes, $extensions[$i]);
                    $pathExtensionFileConf = "KW/extensions/" . $extensions[$i] . "/ext.conf";
                    if (file_exists($pathExtensionFileConf)) {
                        $extList = array(
                            "name" => $cf->getValueFromKeyConf($pathExtensionFileConf, "name"),
                            "version" => $cf->getValueFromKeyConf($pathExtensionFileConf, "version"),
                            "author" => $cf->getValueFromKeyConf($pathExtensionFileConf, "author"),
                            "description" => $cf->getValueFromKeyConf($pathExtensionFileConf, "description"),
                            "dependencies" => $cf->getValueFromKeyConf($pathExtensionFileConf, "dependencies"),
                            "language" => $cf->getValueFromKeyConf($pathExtensionFileConf, "lang"),
                            "isFront" => $cf->getValueFromKeyConf($pathExtensionFileConf, "isFront"),
                            "isBack" => $cf->getValueFromKeyConf($pathExtensionFileConf, "isBack"),
                            "isDbExt" => $cf->getValueFromKeyConf($pathExtensionFileConf, "isDbExtension"),
                            "use" => $cf->getValueFromKeyConf($pathExtensionFileConf, "use"),
                            "icon" => $cf->getValueFromKeyConf($pathExtensionFileConf, "icon"),
                            "path" => "KW/extensions/" . $extensions[$i],
                            "folder" => $extensions[$i],
                        );
                        if ($extList['icon'] == "") {
                            $extList['icon'] = $hlp->getMainUrl() . "/" . $cf->sys_getMainIco();
                        } else {
                            $extList['icon'] = $hlp->getMainUrl() . "/KW/extensions/" . $extensions[$i] . "/ressources/" . $extList['icon'];
                        }
                        if ($extList['isFront'] == true) {
                            self::initFrontElements($extList);
                        }
                        array_push(self::$extensionsName, $extList['name']);
                        array_push(self::$extensionList, $extList);
                    }
                }
            }
        }
    }

    public static function getExtensionList():array {
        return self::$extensionList;
    }

    public static function getExtensionsFrontElement():array {
        return self::$extensionListFrontElement;
    }

    private static function getExtensionFromListByName(string $name):array {
        $extensions = self::$extensionList;        
        for ($i = 0; $i < count($extensions); $i++) {
            if ($extensions[$i]['name'] == $name) {
                return $extensions[$i];
            }
        }
        return array();
    }

    public static function stopExtension(string $name) {
        global $cf;
        $extension = self::getExtensionFromListByName($name);
        if (count($extension) <= 0) {
            return;
        }
        $cf->addValueFormKeyConf($extension['path'] . "/ext.conf", "use", "false");
    }

    public static function startExtension(string $name) {
        global $cf;
        $extension = self::getExtensionFromListByName($name);

        if (count($extension) <= 0) {
            return;
        }
        $cf->addValueFormKeyConf($extension['path'] . "/ext.conf", "use", "true");
    }

    public static function removeExtensionFromUsingList($name) {
        global $cf;
        $extension = self::getExtensionFromListByName($name);

        if (count($extension) <= 0) {
            return;
        }
        $folderName = $extension['folder'];
        $extensionsStr = $cf->getValueFromKeyConf(self::$pathExtMain, "list-ext");
        $nextensionStr = "";
        if ($extensionsStr != "") {
            $extensions = explode(",", $extensionsStr);
            for ($i = 0; $i < count($extensions); $i++) {
                if ($extensions[$i] != $folderName) {
                    if ($nextensionStr == "") {
                        $nextensionStr .= $extensions[$i];
                    } else {
                        $nextensionStr .= "," . $extensions[$i];
                    }
                }
            }
            $cf->addValueFormKeyConf(self::$pathExtMain, "list-ext", $nextensionStr);
        }
    }

    public static function otherButtonAcces():string {
        global $cf, $hlp;
        $new_cats = array();
        $btns = array();
        $mainCats = array("navMenuAdmin", "navMenuFiles", "navMenuWebsite");

        $extensions = self::$extensionList;
        for ($i = 0; $i < count($extensions); $i++) {
            $extension = $extensions[$i];
            if ($extension['isBack'] && $extension['use'] == "true") {
                $pathBackManger = $extension['path'] . "/back/manager-ui.conf";
                $nb_uis = $cf->getValueFromKeyConf($pathBackManger, "manager-ui");
                if ($nb_uis >= 1) {
                    for ($btn = 1; $btn <= $nb_uis; $btn++) {
                        $catGet = $cf->getValueFromKeyConf($pathBackManger, "manager-ui-button" . $btn . "-cat");
                        if ($catGet != "navMenuAdmin" && $catGet != "navMenuFiles" && $catGet != "navMenuWebsite") {
                            if (in_array($catGet, $new_cats) == false) {
                                array_push($new_cats, $catGet);
                            }
                            $txtBtn = $cf->getValueFromKeyConf($pathBackManger, "manager-ui-button" . $btn);
                            $callActiveFunction = "displayContextMenu('". 'folder-' . $extension['folder'] . "-" . $btn . "', '" . $extension['folder'] . "-" . $btn . "')";
                            $iconBeforeBtn = "";
                            $iconClassGet = $cf->getValueFromKeyConf($pathBackManger, "manager-ui-button" . $btn . "-catLogo");
                            if ($iconClassGet != "") {
                                $iconBeforeBtn = '<i class="' . $iconClassGet . '"></i> ';
                            }
                            $htmlBtn = '<button class="btnNavMenu" id="' . $extension['folder'] . "-" . $btn . '" onclick="' . $callActiveFunction . '">' . $iconBeforeBtn . $txtBtn . '</button>';
                            array_push($btns, array("cat" => $catGet, "html" => $htmlBtn));
                        }
                    }
                }
            }
        }

        $res = "";

        for ($i = 0; $i < count($new_cats); $i++) {
            if ($hlp->haveAccesTo($new_cats[$i])) {
                $res .= '<button class="btnNavMenu" onclick="displayNavMenu(\'ncat-' . 
                    $new_cats[$i] . '\', \'icon-' . 
                    $new_cats[$i] . '\')"><i id="icon-' . 
                    $new_cats[$i] . '" class="far fa-arrow-alt-circle-down"></i> ' . 
                    $new_cats[$i] . '</button>';
                $res .= '<div class="closeMenuNav" id="ncat-' . 
                    $new_cats[$i] . '">';
                for ($x = 0; $x < count($btns); $x++) {
                    if ($btns[$x]['cat'] == $new_cats[$i]) {
                        $res .= $btns[$x]['html'];
                    }
                }
                $res .= "</div>";
            }
        }

        return $res;
    }

    public static function getButtonFromCat(string $catName):string {
        global $cf;

        $res = "";
        $extensions = self::$extensionList;
        for ($i = 0; $i < count($extensions); $i++) {
            $extension = $extensions[$i];
            if ($extension['isBack'] && $extension['use'] == "true") {
                $pathBackManger = $extension['path'] . "/back/manager-ui.conf";
                $nb_uis = $cf->getValueFromKeyConf($pathBackManger, "manager-ui");
                if ($nb_uis >= 1) {
                    for ($btn = 1; $btn <= $nb_uis; $btn++) {
                        $catGet = $cf->getValueFromKeyConf($pathBackManger, "manager-ui-button" . $btn . "-cat");
                        if ($catGet == $catName) {
                            $txtBtn = $cf->getValueFromKeyConf($pathBackManger, "manager-ui-button" . $btn);
                            $callActiveFunction = "displayContextMenu('". 'folder-' . $extension['folder'] . "-" . $btn . "', '" . $extension['folder'] . "-" . $btn . "')";
                            $iconBeforeBtn = "";
                            $iconClassGet = $cf->getValueFromKeyConf($pathBackManger, "manager-ui-button" . $btn . "-catLogo");
                            if ($iconClassGet != "") {
                                $iconBeforeBtn = '<i class="' . $iconClassGet . '"></i> ';
                            }
                            $res .= '<button class="btnNavMenu" id="' . $extension['folder'] . "-" . $btn . '" onclick="' . $callActiveFunction . '">' . $iconBeforeBtn . $txtBtn . '</button>';
                        }
                    }
                }
            }
        }
        return $res;
    }

    public static function haveAccesToExt(string $catName):bool {
        global $hlp;
        return $hlp->haveAccesTo($catName);
    }

    public static function getPhpExtensionManager() {
        global $cf;
        $extensions = self::$extensionList;
        for ($i = 0; $i < count($extensions); $i++) {
            $extension = $extensions[$i];
            if ($extension['isBack'] && $extension['use'] == "true") {
                $pathBackManger = $extension['path'] . "/back/manager-ui.conf";
                $nb_uis = $cf->getValueFromKeyConf($pathBackManger, "manager-ui");
                if ($nb_uis >= 1) {
                    for ($btn = 1; $btn <= $nb_uis; $btn++) {
                        $pathPhp = $extension['path'] . "/back/panels/php/" . $cf->getValueFromKeyConf($pathBackManger, "manager-ui-pannel" . $btn . "-php");
                        if (file_exists($pathPhp)) {
                            require $pathPhp;
                        }
                    }
                }
            }
        }
    }

    public static function getCssAddedExtensionManager():string {
        $res = "";
        global $cf, $hlp;
        $extensions = self::$extensionList;
        for ($i = 0; $i < count($extensions); $i++) {
            $extension = $extensions[$i];
            if ($extension['isBack'] && $extension['use'] == "true") {
                $pathBackManger = $extension['path'] . "/back/manager-ui.conf";
                $nb_uis = $cf->getValueFromKeyConf($pathBackManger, "manager-ui");
                if ($nb_uis >= 1) {
                    for ($btn = 1; $btn <= $nb_uis; $btn++) {
                        $pathCss = $extension['path'] . "/back/panels/css/" . $cf->getValueFromKeyConf($pathBackManger, "manager-ui-pannel" . $btn . "-css");
                        if (file_exists($pathCss)) {
                            $res .= '<link rel="stylesheet" href="' . $hlp->getMainUrl() . "/" . $pathCss . '">';
                        }
                    }
                }
            }
        }
        return $res;
    }

    public static function getHtmlAddedExtensionManager():string {
        $res = "";
        global $cf;
        $extensions = self::$extensionList;
        for ($i = 0; $i < count($extensions); $i++) {
            $extension = $extensions[$i];
            if ($extension['isBack'] && $extension['use'] == "true") {
                $pathBackManger = $extension['path'] . "/back/manager-ui.conf";
                $nb_uis = $cf->getValueFromKeyConf($pathBackManger, "manager-ui");
                if ($nb_uis >= 1) {
                    for ($btn = 1; $btn <= $nb_uis; $btn++) {
                        $pathHtml = $extension['path'] . "/back/panels/html/" . $cf->getValueFromKeyConf($pathBackManger, "manager-ui-pannel" . $btn . "-html");
                        $res .= '<div class="contextDev" id="' . 'folder-' . $extension['folder'] . "-" . $btn . '">';
                        if (file_exists($pathHtml)) {
                            $f = fopen($pathHtml, "r");
                            if ($f) {
                                $csize = filesize($pathHtml);
                                if ($csize <= 0) {
                                    $res .= "";
                                } else {
                                    $res .= fread($f, $csize);
                                }
                            }
                            fclose($f);
                        }
                        $res .= '</div>';
                    }
                }
            }
        }
        return $res;
    }

    public static function isExtensionBaliseType($type):bool {
        for ($i = 0; $i < count(self::$extensionListFrontElement); $i++) {
            $elems = self::$extensionListFrontElement[$i]['elems'];
            for ($x = 0; $x < count($elems); $x++) {
                if ($type == $elems[$x]['name']) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function getHtmlExtensionFromBalise($type):string {
        for ($i = 0; $i < count(self::$extensionListFrontElement); $i++) {
            $elems = self::$extensionListFrontElement[$i]['elems'];
            for ($x = 0; $x < count($elems); $x++) {
                if ($type == $elems[$x]['name']) {
                    $f = fopen($elems[$x]['own-path'] . "/frontPage.html", "r");
                    $strHtml = "";
                    if ($f) {
                        $size = filesize($elems[$x]['own-path'] . "/frontPage.html");
                        if ($size > 0) {
                            $strHtml = fread($f, $size);
                        }
                    }
                    fclose($f);
                    if (file_exists($elems[$x]['own-path'] . "/frontPage.css")) {
                        $f = fopen($elems[$x]['own-path'] . "/frontPage.css", "r");
                        if ($f) {
                            $size = filesize($elems[$x]['own-path'] . "/frontPage.css");
                            if ($size > 0) {
                                $strHtml .= "<style>" . fread($f, $size) . "</style>";
                            }
                        }
                        fclose($f);
                    }
                    return $strHtml;
                }
            }
        }
        return "";
    }
}

?>