<?php

namespace Application;

class Extensions {

    private static $pathExtMain = "KW/extensions/ext.conf";
    private static $extensionsPathes = array();
    private static $extensionsName = array();
    private static $extensionList = array();

    private static $extensionListFrontElement = array();

    public function __construct() {}

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
}

?>