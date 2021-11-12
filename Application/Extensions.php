<?php

namespace Application;

class Extensions {

    private static $pathExtMain = "KW/extensions/ext.conf";
    private static $extensionsPathes = array();
    private static $extensionsName = array();
    private static $extensionList = array();
    private static $extensionsBack = array();

    private static $varsKW = array(
        "configExtension" => 0,
    );

    private static $actionVarsKw = array(

    );

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
                        "vars" => $cf->getValueFromKeyConf($pathElems, $elem . "-vars"),
                    );
                    array_push($array_ext_elems, $elemArr);
                }
            }
        }
        if ($extList['use'] == "true") {
            array_push(self::$extensionListFrontElement, array("name" => $extList['name'], "author" =>  $extList['author'], "elems" => $array_ext_elems));
        }
    }

    private static function initBackElement(array $extList) {
        global $cf;
        if ($extList['use'] == "true") {
            $pathBack = $extList['path'] . "/back/manager-ui.conf";
            $nb_buttons = $cf->getValueFromKeyConf($pathBack, "manager-ui");
            if ($nb_buttons == "" || $nb_buttons <= 0) {
                return;
            }
            $totArr = array();
            for ($i = 1; $i <= $nb_buttons; $i++) {
                $arrBack = array(
                    "extensionName" => $extList['name'],
                    "main-path" => $extList['path'],
                    "folderName" => $extList['folder'],
                    "id" => $i,
                    "access" => $cf->getValueFromKeyConf($pathBack, "manager-ui-button" . $i . "-accessName"),
                    "button" => array(
                        "text" => $cf->getValueFromKeyConf($pathBack, "manager-ui-button" . $i),
                        "logo" => $cf->getValueFromKeyConf($pathBack, "manager-ui-button" . $i . "-logo"),
                        "logoColor" => $cf->getValueFromKeyConf($pathBack, "manager-ui-button" . $i . "-logoColor"),
                        "category" => $cf->getValueFromKeyConf($pathBack, "manager-ui-button" . $i . "-cat"),
                        "catLogo" => $cf->getValueFromKeyConf($pathBack, "manager-ui-button" . $i . "-catLogo"),
                    ),
                    "panel" => array(
                        "html" => $cf->getValueFromKeyConf($pathBack, "manager-ui-pannel" . $i . "-html"),
                        "css" => $cf->getValueFromKeyConf($pathBack, "manager-ui-pannel" . $i . "-css"),
                        "php" => $cf->getValueFromKeyConf($pathBack, "manager-ui-pannel" . $i . "-php"),
                    ),
                );
                array_push($totArr, $arrBack);
            }
            array_push(self::$extensionsBack, $totArr);
        }
    }

    private static function initDatabaseExtension(array $extList) {
        global $cf, $hlp, $db;
        $pathDbConf = $extList['path'] . "/database/db.conf";
        $tables = explode(",", $cf->getValueFromKeyConf($pathDbConf, "table-used"));
        foreach($tables as $table) {
            if (isset($table) && $table != "") {
                $tableIntels = array(
                    "name" => $table,
                    "vars" => array()
                );
                $vars = explode(",", $cf->getValueFromKeyConf($pathDbConf, $table));
                foreach($vars as $varGet) {
                    if (isset($varGet) && $varGet != "") {
                        $keyVar = $table . "-" . $varGet;
                        $arrayVar = array(
                            "name" => $varGet,
                            "type" => $cf->getValueFromKeyConf($pathDbConf, $keyVar . "-type"),
                            "size" => $cf->getValueFromKeyConf($pathDbConf, $keyVar . "-size"),
                            "value" => $cf->getValueFromKeyConf($pathDbConf, $keyVar . "-value"),
                            "nullable" => $cf->getValueFromKeyConf($pathDbConf, $keyVar . "-nullable"),
                            "index" => $cf->getValueFromKeyConf($pathDbConf, $keyVar . "-index"),
                            "ai" => $cf->getValueFromKeyConf($pathDbConf, $keyVar . "-ai"),
                        );
                        array_push($tableIntels['vars'], $arrayVar);
                    }
                }
                if ($db->tabelExists($table) && count($tableIntels['vars']) > 0) {
                    $db->addVariableToDb($tableIntels);
                } else if (count($tableIntels['vars']) > 0) {
                    $db->addTableToDb($tableIntels);
                }
            }
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
                        if ($extList['isFront'] == "true" && $extList['use'] == "true") {
                            self::initFrontElements($extList);
                        }
                        if ($extList['isBack'] == "true" && $extList['use'] == "true") {
                            self::initBackElement($extList);
                        }
                        if ($extList['isDbExt'] == "true" && $extList['use'] == "true") {
                            self::initDatabaseExtension($extList);
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

    public static function getExtensionsBackList():array {
        return self::$extensionsBack;
    }

    public static function getExtensionsFrontElement():array {
        return self::$extensionListFrontElement;
    }

    public static function getExtensionFromListByName(string $name):array {
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
        $new_catsLogo = array();
        $btns = array();
        $mainCats = array("navMenuAdmin", "navMenuFiles", "navMenuWebsite");

        $extensions = self::$extensionsBack;

        for ($i = 0; $i < count($extensions); $i++) {
            for($backId = 0; $backId < count($extensions[$i]); $backId++) {
                if ($hlp->haveAccesTo($extensions[$i][$backId]['access'])) {
                    if (in_array($extensions[$i][$backId]['button']['category'], $mainCats) == false) {
                        if (in_array($extensions[$i][$backId]['button']['category'], $new_cats) == false) {
                            array_push($new_cats, $extensions[$i][$backId]['button']['category']);
                            array_push($new_catsLogo, array(
                                "btnName" => $extensions[$i][$backId]['button']['category'],
                                "btnLogo" => $extensions[$i][$backId]['button']['catLogo'],
                            ));
                        }
                        array_push($btns, $extensions[$i][$backId]);
                    }
                }
            }
        }

        $res = "";

        for ($i = 0; $i < count($new_cats); $i++) {
            if ($new_cats[$i] != "") {
                $logoCat = "bxs-layer-plus";
                if ($new_catsLogo[$i]['btnLogo'] != "") {
                    $logoCat = $new_catsLogo[$i]['btnLogo'];
                }
                $idNavMenuBtn = str_replace(" ", "_", $new_cats[$i]) . "-idNavMenu";
                $res .= '<button class="btnNavMenu" id="' . $idNavMenuBtn . '" onclick="displayNavMenu(\''. $new_cats[$i] . '\', \'icon-' . $new_cats[$i] . '\', \'' . $idNavMenuBtn . '\')"'
                    . '><i class="bx ' . $logoCat . '" ></i> '
                    . $new_cats[$i] . '<i class="bx bx-down-arrow iconDirectory" id="icon-' . $new_cats[$i] . '"></i></button>';
                $res .= '<div class="closeMenuNav" id="' . $new_cats[$i] . '">';
                for ($btnId = 0; $btnId < count($btns); $btnId++) {
                    if ($btns[$btnId]['button']['category'] == $new_cats[$i]) {
                        $textButton = $btns[$btnId]['button']['text'];
                        $pathBack = $btns[$btnId]['main-path'] . "/back/manager-ui.conf";
                        if ($cf->getValueFromKeyConf($pathBack, "button-translate") == "true") {
                            $resTrans = self::getLangaugeValue($btns[$btnId]['extensionName'], "btn" . $btns[$btnId]['id'] . "-text");
                            if ($resTrans != "") {
                                $textButton = $resTrans;
                            }
                        }
                        $path_manager_ui = $btns[$btnId]['main-path'] . "/back/manager-ui.conf";
                        $btnIdHtml = $btns[$btnId]['folderName'] . "-" . $btns[$btnId]['id'];
                        $pageIdHtml = $cf->getValueFromKeyConf($path_manager_ui, "manager-ui-pannel" . ($btns[$btnId]['id']) . "-id");
                        $res .= '<button class="btnNavMenu" id="' .$btnIdHtml . '" onclick="'
                            . 'displayContextMenu(\'' . $pageIdHtml . '\',\'' . $btnIdHtml . '\', \'' . $btnIdHtml . '\')">';
                        if ($btns[$btnId]['button']['logo'] != "") {
                            $res .= '<i class="' . $btns[$btnId]['button']['logo'] . '"';
                            $res .= ' style="padding: 5px;border-radius: 5px;background-color: ';
                            if ($btns[$btnId]['button']['logoColor'] != "") {
                                $res .= $btns[$btnId]['button']['logoColor'];
                            } else {
                                $res .= "#b07423";
                            }
                            $res .= '"></i> ';
                        }
                        $res .= $textButton;
                        $res .= "</button>";
                    }
                }
                $res .= "</div>";
            }
        }
        for ($btnId = 0; $btnId < count($btns); $btnId++) {
            if ($btns[$btnId]['button']['category'] == "") {
                $btnIdHtml = $btns[$btnId]['folderName'] . "-" . $btns[$btnId]['id'];
                $path_manager_ui = $btns[$btnId]['main-path'] . "/back/manager-ui.conf";
                $pageIdHtml = $cf->getValueFromKeyConf($path_manager_ui, "manager-ui-pannel" . ($btnId + 1) . "-id");
                $res .= '<button class="btnNavMenu" id="' .$btnIdHtml . '" onclick="'
                    . 'displayContextMenu(\'' . $pageIdHtml . '\',\'' . $btnIdHtml . '\')">';
                if ($btns[$btnId]['button']['logo'] != "") {
                    $res .= '<i class="' . $btns[$btnId]['button']['logo'] . '"';
                    $res .= ' style="padding: 5px;border-radius: 5px;background-color: ';
                    if ($btns[$btnId]['button']['logoColor'] != "") {
                        $res .= $btns[$btnId]['button']['logoColor'];
                    } else {
                        $res .= "#b07423";
                    }
                    $res .= '"></i> ';
                }
                $res .= $btns[$btnId]['button']['text'];
                $res .= "</button>";
            }
        }
        return $res;
    }

    public static function getButtonFromCat(string $catName):string {
        global $hlp, $cf;

        $res = "";
        $extensionsBack = self::$extensionsBack;
        for ($i = 0; $i < count($extensionsBack); $i++) {
            for ($backId = 0; $backId < count($extensionsBack[$i]); $backId++) {
                $backExt = $extensionsBack[$i][$backId];
                $pathBack = $backExt['main-path'] . "/back/manager-ui.conf";
                if ($hlp->haveAccesTo($backExt['access']) && $backExt['button']['category'] == $catName) {
                    $textButton = $backExt['button']['text'];
                    if ($cf->getValueFromKeyConf($pathBack, "button-translate") == "true") {
                        $resTrans = self::getLangaugeValue($backExt['extensionName'], "btn" . $backExt['id'] . "-text");
                        if ($resTrans != "") {
                            $textButton = $resTrans;
                        }
                    }
                    $iconBeforeBtn = "";
                    if ($backExt['button']['logo'] != "") {
                        $iconBeforeBtn = '<i class="' . $backExt['button']['logo'] . '"></i> ';
                    }
                    $callActiveFunction = "displayContextMenu('". 'folder-' . $backExt['folderName'] . '-' . $backExt['id'] . "', '" . $backExt['folderName'] . '-' . $backExt['id'] . "')";
                    $res .= '<button class="btnNavMenu" id="'
                        . $backExt['folderName'] . '-' . $backExt['id'] . '" onclick="'
                        . $callActiveFunction
                        . '">'
                        . $iconBeforeBtn . $textButton . '</button>';
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
                        $pathPhp = $cf->getValueFromKeyConf($pathBackManger, "manager-ui-pannel" . $btn . "-php");
                        if ($pathPhp != "" && file_exists($extension['path'] . "/back/panels/php/" . $pathPhp)) {
                            require $extension['path'] . "/back/panels/php/" . $pathPhp;
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
                        if ($pathCss != $extension['path'] . "/back/panels/css/" && file_exists($pathCss)) {
                            $res .= '<link rel="stylesheet" href="' . $hlp->getMainUrl() . "/" . $pathCss . '">';
                        }
                    }
                }
            }
        }
        return $res;
    }

    public static function getHtmlAddedExtensionManager() {
        global $cf;
        $extensions = self::$extensionsBack;
        $nb_extensions = count($extensions);
        $panelsToRequire = array();
        for ($i = 0; $i < $nb_extensions; $i++) {
            $extensionX = $extensions[$i];
            for ($x = 0; $x < count($extensionX); $x++) {
                $extension = $extensionX[$x];
                if ($extension['panel']['html'] != "") {
                    $path = $extension['main-path'] . "/back/panels/html/" . $extension['panel']['html'];
                    array_push($panelsToRequire, $path);
                }
            }
        }
        foreach($panelsToRequire as $path) {
            require $path;
        }
    }

    public static function getJsAddedExtensionManager():string {
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
                        $pathJs = $extension['path'] . "/back/panels/js/" . $cf->getValueFromKeyConf($pathBackManger, "manager-ui-pannel" . $btn . "-js");
                        if ($cf->getValueFromKeyConf($pathBackManger, "manager-ui-pannel" . $btn . "-js") != "" && file_exists($pathJs)) {
                            $res .= "<script src='" . $hlp->getMainUrl() . "/" . $pathJs . "'></script>";
                        }
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

    private static function remplaceStaticKeyWord($str, $pathConfFile):string {
        global $cf;
        $exp1 = explode("\$kw['configExtension']['", $str);
        for ($i = 0; $i < count($exp1); $i++) {
            $exp2 = explode("']", $exp1[$i]);
            for ($x = 0; $x < count($exp2); $x++) {
                if ($cf->strStartWith($exp2[$x], "\$cf-") && $cf->strContains($exp2[$x], " ") == false) {
                    $res = str_replace("\$cf-", "", $exp2[$x]);
                    $val = $cf->getValueFromKeyConf($pathConfFile, $res);
                    if ($val != "") {
                        $str = str_replace("\$kw['configExtension']['\$cf-" . $res . "']", $val, $str);
                    }
                }
            }
        }
        return $str;
    }

    public static function getHtmlExtensionFromBalise(string $type, string $elementName):string {
        global $ep;
        for ($i = 0; $i < count(self::$extensionListFrontElement); $i++) {
            $elems = self::$extensionListFrontElement[$i]['elems'];
            for ($x = 0; $x < count($elems); $x++) {
                if ($type == $elems[$x]['name']) {
                    $pathExtConfFile = "";
                    for ($j = 0; $j < count(self::$extensionList); $j++) {
                        if (self::$extensionList[$j]['name'] == self::$extensionListFrontElement[$i]['name']) {
                            $pathExtConfFile = self::$extensionList[$j]['path'] . "/config.conf";
                        }
                    }
                    $vars = explode(",", $elems[$x]['vars']);
                    require $elems[$x]['own-path'] . "/frontPage.php";
                    $strHtml = "";
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
                    return "";
                }
            }
        }
        return "";
    }

    public static function getVarsFromFrontElement($elemName):array {
        for ($i = 0; $i < count(self::$extensionListFrontElement); $i++) {
            $elems = self::$extensionListFrontElement[$i]['elems'];
            for ($x = 0; $x < count($elems); $x++) {
                if ($elemName == $elems[$x]['name']) {
                    return explode(",", $elems[$x]['vars']);
                }
            }
        }
        return array();
    }

    public static function getPageEditHtmlFromElement($elemName):string {
        for ($i = 0; $i < count(self::$extensionListFrontElement); $i++) {
            $elems = self::$extensionListFrontElement[$i]['elems'];
            for ($x = 0; $x < count($elems); $x++) {
                if ($elemName == $elems[$x]['name']) {
                    return $elems[$x]['own-path'] . "/editPage.html";
                }
            }
        }
        return "";
    }

    public static function getFrontBackPaheFromElement(string $type, array $balise):string {
        $path = "";
        $vars = array();
        $pathExtenConfFile = "";
        for ($i = 0; $i < count(self::$extensionListFrontElement); $i++) {
            $elems = self::$extensionListFrontElement[$i]['elems'];
            for ($x = 0; $x < count($elems); $x++) {
                if ($type == $elems[$x]['name']) {
                    for ($j = 0; $j < count(self::$extensionList); $j++) {
                        if (self::$extensionList[$j]['name'] == self::$extensionListFrontElement[$i]['name']) {
                            $pathExtenConfFile = self::$extensionList[$j]['path'] . "/config.conf";
                        }
                    }
                    $path = $elems[$x]['own-path'] . "/frontBackPage.php";
                    $vars = explode(",", $elems[$x]['vars']);
                    break;
                }
            }
        }
        if ($path == "" || file_exists($path) == false) {
            return "";
        }
        $readed = "";
        $f = fopen($path, "r");
        if ($f) {
            $sizeF = filesize($path);
            if ($sizeF > 0) {
                $readed = fread($f, $sizeF);
                for ($j = 0; $j < count($vars); $j++) {
                    if ($vars[$j] != "") {
                        $readed = str_replace("\\\$kw['" . $vars[$j] . "']", $balise[$vars[$j]], $readed);
                        $readed = str_replace("\$kw['" . $vars[$j] . "']", $balise[$vars[$j]], $readed);
                        $readed = self::remplaceStaticKeyWord($readed, $pathExtenConfFile);
                    }
                }
            }
        }
        fclose($f);
        return $readed;
    }

    public static function getConfigFileExtension(string $extensionName):string {
        for ($i = 0; $i < count(self::$extensionList); $i++) {
            if (self::$extensionList[$i]['name'] == $extensionName) {
                return self::$extensionList[$i]['path'] . "/config.conf";
            }
        }
        return "";
    }

    public static function getConfigExtensionMainFile(string $extensionName):string {
        for ($i = 0; $i < count(self::$extensionList); $i++) {
            if (self::$extensionList[$i]['name'] == $extensionName) {
                return self::$extensionList[$i]['path'] . "/ext.conf";
            }
        }
        return "";
    }

    public static function getManagerUiExtension(string $extensionName):string {
        $res = "";
        for ($i = 0; $i < count(self::$extensionList); $i++) {
            if (self::$extensionList[$i]['name'] == $extensionName) {
                $pathRes = self::$extensionList[$i]['path'] . "/back/manager-ui.conf";
                if (file_exists($pathRes)) {
                    return $pathRes;
                } else {
                    return "";
                }
            }
        }
        return "";
        return $res;
    }

    public static function getPathAllEditFiles(string $type):array {
        for ($i = 0; $i < count(self::$extensionListFrontElement); $i++) {
            $elems = self::$extensionListFrontElement[$i]['elems'];
            for ($x = 0; $x < count($elems); $x++) {
                if ($type == $elems[$x]['name']) {
                    return array(
                        "front" => $elems[$x]['own-path'] . "/frontPage.php",
                        "css" => $elems[$x]['own-path'] . "/frontPage.css",
                        "editPage" => $elems[$x]['own-path'] . "/editPage.php",
                        "backFront" => $elems[$x]['own-path'] . "/frontBackPage.php",
                    );
                }
            }
        }
        return array();
    }

    public static function getCssExtensionUsed():string {
        global $cf, $hlp, $ep;
        if (!isset($_SESSION['editName'])) {
            return "";
        }
        $res = "";
        $nameElems = $ep->getElementsName();
        for ($i = 0; $i < count($nameElems); $i++) {
            $balise = $ep->genArrayElements($nameElems[$i]);
            if (self::isExtensionBaliseType($balise['type'])) {
                $pathes = self::getPathAllEditFiles($balise['type']);
                if (isset($pathes['css'])) {
                    $res .= "<link href=" . $hlp->getMainUrl() . "/" . $pathes['css'] . " rel=\"stylesheet\">";
                }
            }
        }
        return $res;
    }

    public static function getMainPathExtension(string $extensionName):string {
        for ($i = 0; $i < count(self::$extensionList); $i++) {
            if (self::$extensionList[$i]['name'] == $extensionName) {
                return self::$extensionList[$i]['path'];
            }
        }
        return "";
    }

    public static function getLangaugeValue(string $extensionName, string $key):string {
        if (!isset($_SESSION['language'])) {
            $_SESSION['language'] = "en";
        }
        return self::getLangaugeValueFromLang($extensionName, $key, $_SESSION['language']);
    }

    public static function getLangaugeValueFromLang(string $extensionName, string $key, string $lang):string {
        global $cf;
        $targetLanguage = "";
        $firstAvailableLang = "";
        $mainExtConf = self::getConfigExtensionMainFile($extensionName);
        $availableLanguages = explode(",", $cf->getValueFromKeyConf($mainExtConf, "lang"));
        $mainPath = self::getMainPathExtension($extensionName);

        $nb_languages = count($availableLanguages);

        for ($i = 0; $i < $nb_languages; $i++) {
            $tryFile = $mainPath ."/languages/" . $availableLanguages[$i] . ".conf";
            if ($lang == $availableLanguages[$i] && file_exists($tryFile)) {
                $targetLanguage = $availableLanguages[$i];
                break;
            }
            if ($availableLanguages[$i] != "" && file_exists($tryFile)) {
                if ($firstAvailableLang == "") {
                    $firstAvailableLang = $availableLanguages[$i];
                }
            }
        }
        $pathLang = $mainPath ."/languages/" . $targetLanguage . ".conf";
        if ($targetLanguage == "") {
            $targetLanguage = $firstAvailableLang;
            if ($targetLanguage == "") {
                return "No targeted language or file does not exists";
            }
            $pathLang = $mainPath ."/languages/" . $targetLanguage . ".conf";
        }
        return $cf->getValueFromKeyConf($pathLang, $key);
    }

    public static function updateLangValue(string $extensionName, string $key, string $lang, string $value) {
        global $cf;
        $cf->addValueFormKeyConf(self::getLanguageConfPath($extensionName, $lang), $key, $value);
    }

    public static function getLanguageConfPath(string $extensionName, string $lang):string {
        $mainPath = self::getMainPathExtension($extensionName);
        return $mainPath ."/language/" . $lang . ".conf";
    }
}

?>