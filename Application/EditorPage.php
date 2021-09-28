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

    public static function getHtmlEditable($path) {
        global $cf, $hlp;
        $res = "";
        $path = $hlp->getPathPageFromName($path);
        $f = fopen($path, "r");
        if ($f) {
            $str = fread($f, filesize($path));
            fclose($f);
        } else {
            fclose($f);
            return $res;
        }
        $lines = explode("\n", $str);
        $nlines = array();
        $start = false;

        for ($i = 0; $i < count($lines); $i++) {
            if ($start == false && $cf->strContains($lines[$i], "<body>") == true) {
                $start = true;
            } else if ($start == true && $cf->strContains($lines[$i], "</body>") == false) {
                array_push($nlines, $cf->rmFChars($lines[$i], 2));
            } else if ($cf->strContains($lines[$i], "</body>") == true) {
                break;
            }
        }
        for ($i = 0; $i < count($nlines); $i++) {
            for ($x = 0; $x < count(self::$editorsConstantValues); $x++) {
                $indexes = array_keys(self::$editorsConstantValues);
                $index = $indexes[$x];
                $phps = self::$editorsConstantValues;
                if (strlen($nlines[$i]) >= strlen($phps[$index])) {
                    $phpVal = $phps[$index];
                    $editVal = self::$editorKey . $index;
                    $nlines[$i] = str_replace($phpVal, $editVal, $nlines[$i]);
                }
            }
            if (count($nlines) <= 0) {
                $res = $nlines[$i];
            } else {
                $res .= "\n" . $nlines[$i];
            }
        }
        $res = $cf->replaceStrStr($res, "\t", "    ");
        return $res;
    }

    private static function getStartHtmlUneditable($path):string {
        global $cf;
        $res = "";
        if (file_exists($path) == false)
            return "";
        $f = fopen($path, "r");
        $str = fread($f, filesize($path));
        fclose($f);
        $lines = explode("\n", $str);
        for ($i = 0; $i < count($lines); $i++) {
            if ($cf->strContains($lines[$i], "<body>")) {
                $res .= "\t<body>\n";
                break;
            }
            $res .= $lines[$i] . "\n";
        }
        return $res;
    }

    public static function saveHtmlEditable($path, $input) {
        global $cf, $hlp;
        $path = $hlp->getPathPageFromName($path);
        $head = self::getStartHtmlUneditable($path);
        if ($head == "") {
            return;
        }
        $lines = explode("\n", $input);
        $input = "";
        for ($i = 0; $i < count($lines); $i++) {
            for ($x = 0; $x < count(self::$editorsConstantValues); $x++) {
                $indexes = array_keys(self::$editorsConstantValues);
                $index = $indexes[$x];
                $phpVal = self::$editorsConstantValues[$index];
                $to_replace = self::$editorKey . $index;
                $lines[$i] = str_replace($to_replace, $phpVal, $lines[$i]);
            }
            if ($input == "") {
                $input = $lines[$i];
            } else {
                $input .= "\n" . $lines[$i];
            }
        }
        $input = $cf->replaceStrStr($input, "    ", "\t");
        for ($i = 1; $i < strlen($input); $i++) {
            if ($input[$i] == "\n") {
                $input = $hlp->addStrinStrAtPos($input, "\t\t", $i+1);
            }
        }
        $head .= "\t\t" . $input . "\n\t</body>\n</html>";
        $f = fopen($path, "w");
        fwrite($f, $head, strlen($head));
        fclose($f);
    }

    public static function getAllCssJsContent($namePage):array {
        global $hlp, $cf;
        $res = array("css"=>null, "js"=>null);
        $page = $hlp->getPageIntelsFromName($namePage);
        if (count($page) > 0) {
            if (isset($page['pathCss']) && $page['pathCss'] != NULL && strlen($page['pathCss']) > 0) {
                $cssPath = $hlp->getMainUrl() . $page['pathCss'];
                $f = fopen($cssPath, "r");
                $clearPathCss = $cf->rmFChars($page['pathCss'], 1);
                $size = filesize($clearPathCss);
                if ($size > 0) {
                    $res['css'] = fread($f, $size);
                } else if (file_exists($cssPath)) {
                    $res['css'] = "";
                }
                fclose($f);
            }
            if (isset($page['pathJs']) && $page['pathJs'] != NULL && strlen($page['pathJs']) > 0) {
                $jsPath = $hlp->getMainUrl() . $page['pathJs'];
                $f = fopen($jsPath, "r");
                $clearPathCss = $cf->rmFChars($page['pathJs'], 1);
                $size = filesize($clearPathCss);
                if ($size > 0) {
                    $res['js'] = fread($f, $size);
                } else if (file_exists($jsPath)) {
                    $res['js'] = "";
                }
                fclose($f);
            }
        }
        return $res;
    }

    public static function saveAllCssJsContent($name, array $arrIntels) {
        global $hlp, $cf;
        $page = $hlp->getPageIntelsFromName($name);
        if ($arrIntels['css'] != NULL) {
            $clearPathCss = $cf->rmFChars($page['pathCss'], 1);
            $f = fopen($clearPathCss, "w");
            fwrite($f, $arrIntels['css']);
            fclose($f);
        }
        if ($arrIntels['js'] != NULL) {
            $clearPathJs = $cf->rmFChars($page['pathJs'], 1);
            $f = fopen($clearPathJs, "w");
            fwrite($f, $arrIntels['js']);
            fclose($f);
        }
    } 
}
?>