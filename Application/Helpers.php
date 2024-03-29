<?php
namespace Application;

use Application\Database;
use Application\ConfFiles;

class Helpers {

    private static $db = NULL;
    private static $cf = NULL;

    private static function startWith($string, $handle): bool {
        $len = strlen($handle);
        return (substr($string, 0, $len) === $handle);
    }

    private static $phpBaseContent = "<?php\n?>\n<!DOCTYPE html>\n<html lang=\"<?=\$_SESSION['language']?>\">\n"
        . "\t<head>\n\t\t<title><?=\$_SESSION['titlePage']?></title>\n"
        . "\t\t<meta charset=\"utf-8\">\n"
        . "\t\t<?php\n"
        . "\t\t\tif (isset(\$_SESSION['cssPath'])) {\n"
        . "\t\t?>\n"
        . "\t\t\t\t<link href=\"<?=\$_SESSION['cssPath']?>\" rel=\"stylesheet\">\n"
        . "\t\t<?php\n"
        . "\t\t\t}\n"
        . "\t\t\tif (isset(\$_SESSION['jsPath'])) {\n"
        . "\t\t?>\n"
        . "\t\t\t\t<script type=\"text/javascript\" src=\"<?=\$_SESSION['jsPath']?>\"></script>\n"
        . "\t\t<?php\n"
        . "\t\t\t}\n"
        . "\t\t\tif (isset(\$_SESSION['icoPage'])) {\n"
        . "\t\t?>\n"
        . "\t\t\t<link rel=\"icon\" href=\"<?=\$_SESSION['icoPage']?>\">\n"
        . "\t\t<?php\n"
        . "\t\t\t}\n"
        . "\t\t?>\n"
        . "\t</head>\n"
        . "\t<body>\n"
        . "\t\t<div class=\"presentationPage\">\n"
        . "\t\t\t<picture>\n"
        . "\t\t\t\t<img src=<?=\$hlp->getMainUrl() . \"/\" . \$cf->getValueFromKeyConf(\$cf->getFilesConfig(), \"main_iconBlack\")?>>\n"
        . "\t\t\t</picture>\n"
        . "\t\t\t<div class=\"whiteSpacing\">\n"
        . "\t\t\t\t<h1>Nouvelle page <?=\" '\" . \$_SESSION['titlePage'] . \"'\"?></h1>\n"
        . "\t\t\t\t<p>Bravo, vous avez créé une nouvelle page pour votre site</p>\n"
        . "\t\t\t\t<p>Il vous est possible de la modifier dans votre espace de gestion du site</p>\n"
        . "\t\t\t\t<p>ou directement via les dossier</p>\n"
        . "\t\t\t</div>\n"
        ."\t\t</div>\n"
        . "\t</body>\n" 
        . "</html>";
    private static $cssBaseContent = "body {\n\tpadding: 0;\n\tmargin: 0;\n}\n.presentationPage {\n"
        . "\tposition: absolute;\n"
        . "\tleft: 50%;\n"
        . "\ttop: 50%;\n"
        . "\ttransform: translate(-50%, -50%);\n"
        . "\tborder: 2px solid black;\n"
        . "\ttext-align: center;\n"
        . "\tbackground-color: orange;\n"
        . "}\n\n"
        . ".whiteSpacing {\n"
        . "\tbackground-color: white;\n"
        . "\twidth: 100%;\n"
        . "}\n"
        . ".presentationPage img {\n"
        . "\tpadding-top: 25px;\n"
        . "}\n\n"
        . "#img {\n"
        . "\twidth: 50px;\n"
        . "\theight: 50px;\n"
        . "}\n";
    private static $jsBaseContent = "//welcome, this page is created for js for the page ";
    private static $baseLang = array(
        [
            "short" => "en",
            "name" => "English",
            "en" => "English",
        ],
        [
            "short" => "fr",
            "name" => "Français",
            "en" => "French",
        ],
        [
            "short" => "de",
            "name" => "Deutsch",
            "en" => "German",
        ],
        [
            "short" => "it",
            "name" => "Italiano",
            "en" => "Italian",
        ],
        [
            "short" => "es",
            "name" => "Español",
            "en" => "Spanish",
        ],
        [
            "short" => "da",
            "name" => "Danish",
            "en" => "Danish",
        ],
        [
            "short" => "nl",
            "name" => "Dutch",
            "en" => "Dutch",
        ],
        [
            "short" => "ru",
            "name" => "Русский",
            "en" => "Russian",
        ],
        [
            "short" => "lb",
            "name" => "Lëtzebuergesch",
            "en" => "Luxembourgish",
        ],
        [
            "short" => "zh",
            "name" => "汉语",
            "en" => "Chinese",
        ],
    );

    private static $confFileBase = "elements=body,bd,pict,img,div2,h1,p1,p2,p3\n"
        . "body=div\n"
        . "body-class=\n"
        . "body-content=\n"
        . "body-parent=\n"
        . "body-children=bd\n"
        . "bd=div\n"
        . "bd-class=presentationPage\n"
        . "bd-content=\n"
        . "bd-parent=body\n"
        . "bd-children=pict,div2\n"
        . "pict=picture\n"
        . "pict-class=\n"
        . "pict-content=\n"
        . "pict-parent=bd\n"
        . "pict-children=img\n"
        . "img=img\n"
        . "img-class=\n"
        . "img-content=\n"
        . "img-parent=pict\n"
        . "img-children=\n"
        . "img-src=mainIconBlack\n"
        . "img-srcType=media\n"
        . "div2=div\n"
        . "div2-class=whiteSpacing\n"
        . "div2-content=\n"
        . "div2-parent=bd\n"
        . "div2-children=h1,p1,p2,p3\n"
        . "h1=h1\n"
        . "h1-class=\n"
        . "h1-content=Nouvelle page <?=\" '\" . \$_SESSION['titlePage'] . \"'\"?>\n"
        . "h1-parent=div2\n"
        . "h1-children=\n"
        . "p1=p\n"
        . "p1-class=\n"
        . "p1-content=Bravo, vous avez créé une nouvelle page pour votre site\n"
        . "p1-parent=div2\n"
        . "p1-children=\n"
        . "p2=p\n"
        . "p2-class=\n"
        . "p2-content=Il vous est possible de la modifier dans votre espace de gestion du site\n"
        . "p2-parent=div2\n"
        . "p2-children=\n"
        . "p3=p\n"
        . "p3-class=\n"
        . "p3-content=ou directement via les dossier\n"
        . "p3-parent=div2\n"
        . "p3-children=\n";

    /*
        INITIALIZE HELPERS
    */

    function __construct() {}

    public static function setDB($db, $cf) {
        global $db, $cf;
        self::$db = $db;
        self::$cf = $cf;
    }

    /*
            UTILS FONCTIONS
    */

    private static function generateCid():string {
        global $db;
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-abcdefghijklmnopqrstuvwxyz";
        $res = "";
        for ($i = 0; $i < 255; $i++) {
            $res .= $chars[rand(0, strlen($chars) - 1)];
        }
        $exists = false;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_mailconfirm WHERE cid=?");
        $stm->execute(array($res));
        if ($stm->fetch())
            $exists = true;
        $db->disconnect();
        if ($exists == true)
            return self::generateCid();
        return $res;
    }

    public static function getMainUrl():string {
        $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

        if (self::startWith($currentUrl, "http://localhost") || self::startWith($currentUrl, "https://localhost")) {
            $explode = explode("/", $_SERVER['REQUEST_URI'], 3);
            return $currentUrl . "/" . $explode[1] . "/";
        }
        return $currentUrl;
    }

    public static function getCurrentUrl():string {
        $mainUrl = self::getMainUrl();
        if (self::startWith($mainUrl, "http://localhost") || self::startWith($mainUrl, "https://localhost")) {
            $explode = explode("/", $_SERVER['REQUEST_URI'], 3);
            return $mainUrl . $explode[2];
        }
        return "" . $mainUrl . $_SERVER['REQUEST_URI'];
    }

    public static function deleteAllFilePage() {
        $files = scandir("KW/public/pages");
        $filesCss = scandir("KW/public/ressources/css");
        $filesJs = scandir("KW/public/ressources/js");
        for ($i = 0; $i < count($files); $i++) {
            if ($files[$i] != "." && $files[$i] != ".." && $files[$i] != ".gitkeep") {
                unlink("KW/public/pages/" . $files[$i]);
            }
        }
        for ($i = 0; $i < count($filesCss); $i++) {
            if ($filesCss[$i] != "." && $filesCss[$i] != ".." && $files[$i] != ".gitkeep") {
                unlink("KW/public/ressources/css/" . $filesCss[$i]);
            }
        }
        for ($i = 0; $i < count($filesJs); $i++) {
            if ($filesJs[$i] != "." && $filesJs[$i] != ".." && $files[$i] != ".gitkeep") {
                unlink("KW/public/ressources/js/" . $filesJs[$i]);
            }
        }
    }

    public static function generateConfirmMail($email, $isSu) {
        global $db, $cf;
        $cid = self::generateCid();
        $obj = "Confirmation de votre mail pour le site " . $cf->getValueFromKeyConf($cf->getFilesConfig(), "website_name");
        $to = $email;
        $msg = "Bonjour,\nMerci de vous être inscrit sur notre site. Merci de confirmer de votre mail grâce à ce lien "
            . $cf->getValueFromKeyConf($cf->getFilesConfig(), "main_url") . "/confirmEmail/" . $cid
            . " .\n\n\nSi ce n'est pas vous veuillez cliquer sur ce lien" . " .\n" 
            . "Encore merci et à bientôt sur " . $cf->getValueFromKeyConf($cf->getFilesConfig(), "website_name");
        $header = "From: " . $cf->getValueFromKeyConf($cf->getFilesConfig(), "email-confirm-password");
        $connect = $db->connect();
        $stm = $connect->prepare("INSERT INTO kp_mailconfirm (cid, email, isSu, checked, changeMail) VALUES (?, ?, ?, ?, ?)");
        $stm->execute(array(
            $cid,
            $email,
            $isSu,
            0,
            0
        ));
        $db->disconnect();
        mail($to, $obj, $msg, $header);
    }

    public static function generateChangePwd($email, $isSu) {
        global $db;
        $cid = self::generateCid();
        $connect = $db->connect();
        $stm = $connect->prepare("INSERT INTO kp_mailconfirm (cid, email, isSu, checked, changeMail) VALUES (?, ?, ?, ?, ?)");
        $stm->execute(array(
            $cid,
            $email,
            $isSu,
            0,
            0
        ));
        $db->disconnect();
    }

    private static function getEmailFromCid($cid):string {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_mailconfirm WHERE cid=?");
        $stm->execute(array($cid));
        $resStm = $stm->fetch();
        $db->disconnect();
        if ($resStm) {
            return $resStm['email'];
        }
        return "";
    }

    private static function getIsSuFromCid($cid):int {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_mailconfirm WHERE cid=?");
        $stm->execute(array($cid));
        $resStm = $stm->fetch();
        $db->disconnect();
        if ($resStm) {
            return $resStm['isSu'];
        }
        return 0;
    }

    public static function validConfirmEmail($cid) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE kp_mailconfirm SET checked = '1' WHERE cid=?");
        $stm->execute(array($cid));
        $email = self::getEmailFromCid($cid);
        if (self::getIsSuFromCid($cid) == 1) {
            $stm = $connect->prepare("UPDATE su_users SET status = '1' WHERE email=?");
            $stm->execute(array($email));
        } else {
            $stm = $connect->prepare("UPDATE no_users SET status = '1' WHERE email=?");
            $stm->execute(array($email));
        }
        $db->disconnect();
    }

    public static function deleteAccountFromCid($cid) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE kp_mailconfirm SET checked = '1' WHERE cid=?");
        $stm->execute(array($cid));
        $email = self::getEmailFromCid($cid);
        if (self::getIsSuFromCid($cid) == 1) {
            $stm = $connect->prepare("UPDATE su_users SET deleted = '1' WHERE email=?");
            $stm->execute(array($email));
        } else {
            $stm = $connect->prepare("UPDATE no_users SET deleted = '1' WHERE email=?");
            $stm->execute(array($email));
        }
        $db->disconnect();
    }

    public static function cidExists($cid): bool {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_mailconfirm WHERE cid=?");
        $stm->execute(array($cid));
        $resStm = $stm->fetch();
        $db->disconnect();
        if ($resStm) {
            if ($resStm['checked'] == 0)
                return true;
        }
        return false;
    }

    public static function getLanguageFromId(int $id) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_languages WHERE id=?");
        $stm->execute(array($id));
        $resStm = $stm->fetch();
        $db->disconnect();
        if ($resStm) {
            return $resStm['name_en'];
        }
        return "Unknow";
    }

    public static function getLanguageShortFromId(int $id) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_languages WHERE id=?");
        $stm->execute(array($id));
        $resStm = $stm->fetch();
        $db->disconnect();
        if ($resStm) {
            return $resStm['name_short'];
        }
        return "";
    }

    public static function getLanguageIdFromShort(string $short):int {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_languages WHERE name_short=?");
        $stm->execute(array($short));
        $resStm = $stm->fetch();
        $db->disconnect();
        if ($resStm) {
            return $resStm['id'];
        }
        return 1;
    }

    public static function getLanguageList():array {
        global $db;
        $res = array();
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_languages WHERE 1");
        $stm->execute();
        while ($resStm = $stm->fetch()) {
            array_push($res, $resStm);
        }
        $db->disconnect();
        return $res;
    }

    public static function getLangWorldMainFileFromLanguage(string $lang, string $key, string $defaultResult = ""):string {
        global $cf;
        $path = "KW/kapweb_inits/ressources/languages/" . $lang . "Lang.conf";
        $res = $defaultResult;
        if (file_exists($path)) {
            $res = $cf->getValueFromKeyConf($path, $key);
        } else {
            $path = "KW/kapweb_inits/ressources/languages/enLang.conf";
            $res = $cf->getValueFromKeyConf($path, $key);
        }
        if ($res == "") {
            $res = $defaultResult;
        }
        return $res;
    }

    public static function getLangWorldMainFile(string $key, string $defaultResult = ""):string {
        if (!isset($_SESSION['language'])) {
            $_SESSION['language'] = "en";
        }
        return self::getLangWorldMainFileFromLanguage($_SESSION['language'], $key, $defaultResult);
    }

    public static function getIdMediaType(string $cateboryName):int {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_typemedias WHERE name=?");
        $stm->execute(array($cateboryName));
        $resStm = $stm->fetch();
        $db->disconnect();
        if ($resStm) {
            return $resStm['id'];
        }
        return -1;
    }

    public static function getMediaCatList(string $category):array {
        $res = array();
        global $db;
        $idMedia = self::getIdMediaType($category);
        if ($idMedia < 0) {
            return $res;
        }
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_medias WHERE type=?");
        $stm->execute(array($idMedia));
        while ($resStm = $stm->fetch()) {
            array_push($res, $resStm);
        }
        $db->disconnect();
        return $res;
    }

    public static function getIconsMedia(int $mediaId):string {
        $res = array();
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_typemedias WHERE id=?");
        $stm->execute(array($mediaId));
        $resStm = $stm->fetch();
        $db->disconnect();
        if ($resStm && isset($resStm['icon'])) {
            return $resStm['icon'];
        }
        return "bx bxs-error";
    }

    public static function getAllMedias():array {
        $res = array();
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_medias WHERE 1");
        $stm->execute(array());
        while ($resStm = $stm->fetch()) {
            array_push($res, $resStm);
        }
        $db->disconnect();
        return $res;
    }

    public static function getAllMediaTypes():array {
        $res = array();
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_typemedias WHERE 1");
        $stm->execute(array());
        while ($resStm = $stm->fetch()) {
            array_push($res, $resStm);
        }
        $db->disconnect();
        return $res;
    }

    public static function getPathMedia(string $name) {
        global $db, $cf;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_medias WHERE name=?");
        $stm->execute(array($name));
        $resStm = $stm->fetch();
        $db->disconnect();
        if ($resStm) {
            if ($cf->strStartWith($resStm['path'], "http")) {
                return $resStm['path'];
            } else {
                return self::getMainUrl() . "/" . str_replace(" ", "_", $resStm['path']);
            }
        } else {
            return "";
        }
    }

    public static function newMedia(string $name, int $enable, string $path, int $type, string $description) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("INSERT INTO kp_medias (name, type, description, deleted, path) VALUES (?, ?, ?, ?, ?)");
        $stm->execute(array(
            $name,
            $type,
            $description,
            $enable,
            $path
        ));
        $db->disconnect();
    }

    public static function enableMedia(int $id) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE kp_medias SET deleted=0 WHERE id=?");
        $stm->execute(array($id));
        while ($resStm = $stm->fetch()) {
            array_push($res, $resStm);
        }
        $db->disconnect();
    }

    public static function disbaleMedia(int $id) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE kp_medias SET deleted=1 WHERE id=?");
        $stm->execute(array($id));
        while ($resStm = $stm->fetch()) {
            array_push($res, $resStm);
        }
        $db->disconnect();
    }

    public static function deleteMedia(int $id) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("DELETE FROM kp_medias WHERE id=?");
        $stm->execute(array($id));
        while ($resStm = $stm->fetch()) {
            array_push($res, $resStm);
        }
        $db->disconnect();
    }

    /*
            DATABSE CONNECTION
    */
    public static function haveConnectionDbIntels():bool {
        global $cf;
        $vals = $cf->getValueFromKeyConf($cf->getDbConfig(), "db");
        if ($vals == "")
            return false;
        if (strlen($vals) == 0) {
            return false;
        }
        $all_vals = explode(",", $vals);
        if (count($all_vals) == 4) {
            return true;
        }
        return false;
    }

    public static function saveNewDb(array $dbConf) {
        global $cf;
        $f = fopen($cf->getDbConfig(), "r");
        if ($f) {
            $line_found = false;
            $ssize = $cf->getDbConfig();
            if ($ssize <= 0)
                $ssize = 1;
            $str = fread($f, $ssize);
            fclose($f);
            $lines = explode("\n", $str);
            for ($i = 0; $i < count($lines); $i++) {
                if (self::$cf->strStartWith($lines[$i], "db=")) {
                    $lines[$i] .= $dbConf['host'] . "," . $dbConf['dbName'] . "," . $dbConf['username'] . "," . $dbConf['passsword'];
                    $line_found = true;
                    break;
                }
            }
            if ($line_found == false) {
                $nline = "db=" . $dbConf['host'] . "," . $dbConf['dbName'] . "," . $dbConf['username'] . "," . $dbConf['passsword'];
                array_push($lines, $nline);
            }
            $nstr = "";
            for ($i = 0; $i < count($lines); $i++) {
                if ($nstr == "") {
                    $nstr = $lines[$i];
                } else {
                    $nstr .= "\n" . $lines[$i];
                }
            }
            $f = fopen($cf->getDbConfig(), "w");
            fwrite($f, $nstr, strlen($nstr));
            fclose($f);
        } else {
            fclose($f);
        }
    }

    public static function getConnectionConfig():array {
        global $cf;
        $res = array(
            "host" => "",
            "dbName" => "",
            "username" => "",
            "passsword" => "",
        );
        if (self::haveConnectionDbIntels() == false) {
            return $res;
        }
        $vals = $cf->getValueFromKeyConf($cf->getDbConfig(), "db");
        $all_vals = explode(",", $vals);
        $res['host'] = $all_vals[0];
        $res['dbName'] = $all_vals[1];
        $res['username'] = $all_vals[2];
        $res['passsword'] = $all_vals[3];
        return $res;
    }
    
    public static function getDeletedTables():array {
        global $db;
        $res = array();
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_tables WHERE deleted=1");
        $stm->execute();
        while ($resStm = $stm->fetch()) {
            array_push($res, $resStm);
        }
        $db->disconnect();
        return $res;
    }

    public static function getDbName(): string {
        global $cf;
        $res = "";
        $str = $cf->getValueFromKeyConf($cf->getDbConfig(), "db");
        $arr = explode(",", $str);
        if (count($arr) >= 2) {
            $res = $arr[1];
        }
        return $res;
    }
    
    /*
            TABLE MANAGING
    */

    public static function generateTablesNeeded() {
        global $db;
        $connect = $db->connect();
        $add_kp_tables = false;
        if ($db::tabelExists("kp_tables") == false) {
            $add_kp_tables = true;
            //ALTER TABLE `test` ADD `test` INT NOT NULL AUTO_INCREMENT  AFTER `yo`, ADD PRIMARY KEY (`test`); 
            $structure = ""
                . "ALTER TABLE kp_tables ADD COLUMN rows int(11) NOT NULL DEFAULT 0;"
                . "ALTER TABLE kp_tables ADD COLUMN types text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL;"
                . "ALTER TABLE kp_tables ADD COLUMN args text CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL;"
                . "ALTER TABLE kp_tables ADD COLUMN deleted tinyint(1) NOT NULL DEFAULT 0;"
                . "ALTER TABLE kp_tables ADD COLUMN hided tinyint(1) NOT NULL DEFAULT 0;"
                . "ALTER TABLE kp_tables ADD COLUMN editable_structure tinyint(1) NOT NULL DEFAULT 1;"
                . "ALTER TABLE kp_tables ADD COLUMN description text CHARACTER SET utf8 COLLATE utf8_general_ci;"
                . "ALTER TABLE kp_tables ADD COLUMN editable_content tinyint(1) NOT NULL DEFAULT 1;"
                . "ALTER TABLE kp_tables ADD COLUMN deletable tinyint(1) NOT NULL DEFAULT 1;";
            $stm = $connect->prepare("CREATE TABLE IF NOT EXISTS kp_tables (name varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL PRIMARY KEY)");
            $stm->execute();
            $stm = $connect->prepare($structure);
            $stm->execute();
        }
        if ($db::tabelExists("kp_languages") == false) {
            $structure = ""
                . "ALTER TABLE kp_languages ADD COLUMN name varchar(255) NOT NULL;"
                . "ALTER TABLE kp_languages ADD COLUMN name_en varchar(255) NOT NULL;"
                . "ALTER TABLE kp_languages ADD COLUMN name_short varchar(2) NOT NULL;";
            $stm = $connect->prepare("CREATE TABLE IF NOT EXISTS kp_languages (id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY)");
            $stm->execute();
            $stm = $connect->prepare($structure);
            $stm->execute();
            for ($i = 0; $i < count(self::$baseLang); $i++) {
                $stm = $connect->prepare("INSERT INTO kp_languages (name, name_en, name_short) VALUES (?, ?, ?)");
                $stm->execute(array(self::$baseLang[$i]['name'], self::$baseLang[$i]['en'], self::$baseLang[$i]['short']));
            }
        }
        if ($db::tabelExists("pages") == false) {
            $id_en = self::getLanguageIdFromShort("en");
            $structure = ""
                . "ALTER TABLE pages ADD COLUMN url varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;"
                . "ALTER TABLE pages ADD COLUMN title varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;"
                . "ALTER TABLE pages ADD COLUMN path varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;"
                . "ALTER TABLE pages ADD COLUMN deleted tinyint(1) NOT NULL DEFAULT 0;"
                . "ALTER TABLE pages ADD COLUMN hided tinyint(1) NOT NULL DEFAULT 0;"
                . "ALTER TABLE pages ADD COLUMN editable tinyint(1) NOT NULL DEFAULT 1;"
                . "ALTER TABLE pages ADD COLUMN pid int(11) NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (pid);"
                . "ALTER TABLE pages ADD COLUMN mainPage tinyint(1) NOT NULL DEFAULT 0;"
                . "ALTER TABLE pages ADD COLUMN pathCss varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL;"
                . "ALTER TABLE pages ADD COLUMN pathJs varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL;"
                . "ALTER TABLE pages ADD COLUMN ico varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL;"
                . "ALTER TABLE pages ADD COLUMN needConnect tinyint(1) NOT NULL DEFAULT 0;"
                . "ALTER TABLE pages ADD COLUMN needConnectSu tinyint(1) NOT NULL DEFAULT 0;"
                . "ALTER TABLE pages ADD COLUMN language int(11) NOT NULL DEFAULT " . $id_en . ";"
                . "ALTER TABLE pages ADD COLUMN builtin tinyint(1) NOT NULL DEFAULT 0;";
            $stm = $connect->prepare("CREATE TABLE IF NOT EXISTS pages (name varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL UNIQUE)");
            $stm->execute();
            $stm = $connect->prepare($structure);
            $stm->execute();
            //ajout des pages de base
            $stm = $connect->prepare("INSERT INTO pages (name, url, title, path, hided, editable, pathCss, ico, builtin) VALUES " .
                "(?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "connectKw",
                "/KW",
                "Connection to KW",
                "KW/kapweb_inits/pages/connect_kw.php",
                1,
                0,
                "/KW/kapweb_inits/ressources/css/kwconnect.css",
                "/KW/kapweb_inits/ressources/medias/kwLogoOrange.ico",
                1
            ));
            $stm = $connect->prepare("INSERT INTO pages (name, url, title, path, hided, editable, pathCss, pathJs, ico, needConnectSu, builtin) VALUES " .
                "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "managerKw",
                "/KW/manager",
                "KW Manager",
                "KW/kapweb_inits/pages/kwmanager.php",
                1,
                0,
                "/KW/kapweb_inits/ressources/css/kwmanager.css",
                "/KW/kapweb_inits/ressources/js/kwmanager.js",
                "/KW/kapweb_inits/ressources/medias/kwLogoOrange.ico",
                1,
                1
            ));
            $stm = $connect->prepare("INSERT INTO pages (name, url, title, path, hided, editable, pathCss, ico, builtin) VALUES " .
                "(?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "kwdbConnect",
                "/KW/dbConnect",
                "Connect to Database",
                "KW/kapweb_inits/pages/connection_db.php",
                1,
                0,
                "/KW/kapweb_inits/ressources/css/kwdbconnect.css",
                "/KW/kapweb_inits/ressources/medias/kwLogoOrange.ico",
                1
            ));
            $stm = $connect->prepare("INSERT INTO pages (name, url, title, path, hided, editable, pathCss, pathJs, ico, needConnectSu, builtin) VALUES " .
                "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "editPageKp",
                "/KW/editPage",
                "Edit page",
                "KW/kapweb_inits/pages/kpeditpage.php",
                1,
                0,
                "/KW/kapweb_inits/ressources/css/kweditpage.css",
                "/KW/kapweb_inits/ressources/js/kpeditpage.js",
                "/KW/kapweb_inits/ressources/medias/kwLogoOrange.ico",
                1,
                1
            ));
            $stm = $connect->prepare("INSERT INTO pages (name, url, title, path, hided, editable, pathCss, ico, builtin) VALUES " .
                "(?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "kppageNotFound",
                "/pageNotFound",
                "Page Not Found",
                "KW/kapweb_inits/pages/pageNotFound.php",
                1,
                0,
                "/KW/kapweb_inits/ressources/css/pageNotFound.css",
                "/KW/kapweb_inits/ressources/medias/kwLogoOrange.ico",
                1
            ));
            $stm = $connect->prepare("INSERT INTO pages (name, url, title, path, hided, editable, pathCss, pathJs, ico, builtin) VALUES " .
                "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "connectUserKw",
                "/connect",
                "Connect",
                "KW/kapweb_inits/pages/kp_userconnect.php",
                1,
                0,
                "/KW/kapweb_inits/ressources/css/kp_useraccount.css",
                "/KW/kapweb_inits/ressources/js/kp_userconnect.js",
                "/KW/kapweb_inits/ressources/medias/kwLogoOrange.ico",
                1
            ));
            $stm = $connect->prepare("INSERT INTO pages (name, url, title, path, hided, editable, pathCss, ico, needConnectSu, builtin) VALUES " .
                "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "editContentTable",
                "/KW/editContentTable",
                "Edit content table",
                "KW/kapweb_inits/pages/edit_content_table.php",
                1,
                0,
                "/KW/kapweb_inits/ressources/css/edit_content_table.css",
                "/KW/kapweb_inits/ressources/medias/kwLogoOrange.ico",
                1,
                1
            ));
            $stm = $connect->prepare("INSERT INTO pages (name, url, title, path, hided, editable, pathCss, ico, needConnectSu, builtin) VALUES " .
                "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "editStructureTable",
                "/KW/editStructureTable",
                "Edit structure table",
                "KW/kapweb_inits/pages/edit_structure_table.php",
                1,
                0,
                "/KW/kapweb_inits/ressources/css/edit_structure_table.css",
                "/KW/kapweb_inits/ressources/medias/kwLogoOrange.ico",
                1,
                1
            ));
            $stm = $connect->prepare("INSERT INTO pages (name, url, title, path, hided, editable, pathCss, ico, needConnectSu, builtin) VALUES " .
                "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "confirmEmail",
                "/confirmEmail",
                "Confirm your email",
                "KW/kapweb_inits/pages/kpconfirm_email.php",
                1,
                0,
                "/KW/kapweb_inits/ressources/css/kpconfirm_email.css",
                "/KW/kapweb_inits/ressources/medias/kwLogoOrange.ico",
                0,
                1
            ));
            $stm = $connect->prepare("INSERT INTO pages (name, url, title, path, hided, editable, pathCss, ico, needConnectSu, builtin) VALUES " .
                "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "changePwd",
                "/changePassword",
                "Change your password",
                "KW/kapweb_inits/pages/kpconfirm_email.php",
                1,
                0,
                "/KW/kapweb_inits/ressources/css/kpconfirm_email.css",
                "/KW/kapweb_inits/ressources/medias/kwLogoOrange.ico",
                0,
                1
            ));
            $stm = $connect->prepare("INSERT INTO pages (name, url, title, path, hided, editable, pathCss, ico, needConnectSu, builtin, mainPage) VALUES " .
                "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "uploadMedia",
                "/KW/uploadMedia",
                "Upload Media",
                "KW/kapweb_inits/pages/uploadMedia.php",
                1,
                0,
                "/KW/kapweb_inits/ressources/css/kpconfirm_email.css",
                "/KW/kapweb_inits/ressources/medias/kwLogoOrange.ico",
                0,
                1,
                1,
            ));
        }
        if ($db::tabelExists("kp_cookies") == false) {
            //ALTER TABLE `test` ADD `test` INT NOT NULL AUTO_INCREMENT  AFTER `yo`, ADD PRIMARY KEY (`test`); 
            $structure = ""
                . "ALTER TABLE kp_cookies ADD COLUMN description text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;"
                . "ALTER TABLE kp_cookies ADD COLUMN lifetime int(11) NOT NULL;"
                . "ALTER TABLE kp_cookies ADD COLUMN deleted tinyint(1) NOT NULL DEFAULT 0;"
                . "ALTER TABLE kp_cookies ADD COLUMN editable tinyint(1) NOT NULL DEFAULT 1;";
            $stm = $connect->prepare("CREATE TABLE IF NOT EXISTS kp_cookies (name varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL PRIMARY KEY)");
            $stm->execute();
            $stm = $connect->prepare($structure);
            $stm->execute();
        }
        if ($db::tabelExists("no_users") == false) {
            $structure = ""
                . "ALTER TABLE no_users ADD COLUMN cr_date int(11) NOT NULL;"
                . "ALTER TABLE no_users ADD COLUMN ls_mod int(11) NOT NULL;"
                . "ALTER TABLE no_users ADD COLUMN ls_con int(11) NOT NULL;"
                . "ALTER TABLE no_users ADD COLUMN ls_mod_uid int(11) NOT NULL;"
                . "ALTER TABLE no_users ADD COLUMN lname varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL;"
                . "ALTER TABLE no_users ADD COLUMN fname varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL;"
                . "ALTER TABLE no_users ADD COLUMN pseudo varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL;"
                . "ALTER TABLE no_users ADD COLUMN email varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;"
                . "ALTER TABLE no_users ADD COLUMN password varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;"
                . "ALTER TABLE no_users ADD COLUMN lang int(11) NOT NULL DEFAULT 1;"
                . "ALTER TABLE no_users ADD COLUMN status int(11) NOT NULL DEFAULT 0;"
                . "ALTER TABLE no_users ADD COLUMN baned tinyint(1) NOT NULL DEFAULT 0;"
                . "ALTER TABLE no_users ADD COLUMN deleted tinyint(1) NOT NULL DEFAULT 0;";
            $stm = $connect->prepare("CREATE TABLE IF NOT EXISTS no_users (uid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY)");
            $stm->execute();
            $stm = $connect->prepare($structure);
            $stm->execute();
        }
        if ($db::tabelExists("su_users") == false) {
            $structure = ""
                . "ALTER TABLE su_users ADD COLUMN cr_date int(11) NOT NULL;"
                . "ALTER TABLE su_users ADD COLUMN ls_mod int(11) NOT NULL;"
                . "ALTER TABLE su_users ADD COLUMN ls_con int(11) NOT NULL;"
                . "ALTER TABLE su_users ADD COLUMN ls_mod_uid int(11) NOT NULL;"
                . "ALTER TABLE su_users ADD COLUMN lname varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL;"
                . "ALTER TABLE su_users ADD COLUMN fname varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL;"
                . "ALTER TABLE su_users ADD COLUMN pseudo varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL;"
                . "ALTER TABLE su_users ADD COLUMN email varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;"
                . "ALTER TABLE su_users ADD COLUMN password varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;"
                . "ALTER TABLE su_users ADD COLUMN lang int(11) NOT NULL DEFAULT 1;"
                . "ALTER TABLE su_users ADD COLUMN status int(11) NOT NULL DEFAULT 0;"
                . "ALTER TABLE su_users ADD COLUMN access int(11) NOT NULL DEFAULT 1;"
                . "ALTER TABLE su_users ADD COLUMN baned tinyint(1) NOT NULL DEFAULT 0;"
                . "ALTER TABLE su_users ADD COLUMN deleted tinyint(1) NOT NULL DEFAULT 0;";
            $stm = $connect->prepare("CREATE TABLE IF NOT EXISTS su_users (uid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY)");
            $stm->execute();
            $stm = $connect->prepare($structure);
            $stm->execute();
        }
        if ($db::tabelExists("kp_mailconfirm") == false) {
            $structure = ""
                . "ALTER TABLE kp_mailconfirm ADD COLUMN email varchar(255) NOT NULL;"
                . "ALTER TABLE kp_mailconfirm ADD COLUMN uid int(11) NOT NULL DEFAULT -1;"
                . "ALTER TABLE kp_mailconfirm ADD COLUMN isSu tinyint(1) NOT NULL DEFAULT 0;"
                . "ALTER TABLE kp_mailconfirm ADD COLUMN checked tinyint(1) NOT NULL DEFAULT 0;"
                . "ALTER TABLE kp_mailconfirm ADD COLUMN changeMail tinyint(1) NOT NULL DEFAULT 0;";
            $stm = $connect->prepare("CREATE TABLE IF NOT EXISTS kp_mailconfirm (cid varchar(255) NOT NULL PRIMARY KEY)");
            $stm->execute();
            $stm = $connect->prepare($structure);
            $stm->execute();
        }
        if ($db::tabelExists("kp_access") == false) {
            $structure = ""
                . "ALTER TABLE kp_access ADD COLUMN name varchar(255) NOT NULL;"
                . "ALTER TABLE kp_access ADD COLUMN tableAccess text NOT NULL;";
            $stm = $connect->prepare("CREATE TABLE IF NOT EXISTS kp_access (aid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY)");
            $stm->execute();
            $stm = $connect->prepare($structure);
            $stm->execute();
            $stm = $connect->prepare("INSERT INTO kp_access (name, tableAccess) VALUES " .
                "(?, ?)");
            $stm->execute(array(
                "Super Admin",
                "*",
            ));
            $stm = $connect->prepare("INSERT INTO kp_access (name, tableAccess) VALUES " .
                "(?, ?)");
            $stm->execute(array(
                "restricted",
                "",
            ));
        }
        if ($db::tabelExists("kp_redirects") == false) {
            $structure = "ALTER TABLE kp_redirects ADD COLUMN last_path varchar(255) NOT NULL;"
                . "ALTER TABLE kp_redirects ADD COLUMN new_path varchar(255) NOT NULL;"
                . "ALTER TABLE kp_redirects ADD COLUMN deleted int(2) NOT NULL DEFAULT 0;"
                . "ALTER TABLE kp_redirects ADD COLUMN used int(11) NOT NULL DEFAULT 0;";
            $stm = $connect->prepare("CREATE TABLE IF NOT EXISTS kp_redirects (id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY)");
            $stm->execute();
            $stm = $connect->prepare($structure);
            $stm->execute();
        }
        if ($db::tabelExists("kp_medias") == false) {
            $structure = "ALTER TABLE kp_medias ADD COLUMN name varchar(255) NOT NULL;"
                . "ALTER TABLE kp_medias ADD COLUMN type int(11) NOT NULL DEFAULT 0;"
                . "ALTER TABLE kp_medias ADD COLUMN description text;"
                . "ALTER TABLE kp_medias ADD COLUMN deleted int(2) NOT NULL DEFAULT 0;"
                . "ALTER TABLE kp_medias ADD COLUMN path varchar(255);";
            $stm = $connect->prepare("CREATE TABLE IF NOT EXISTS kp_medias (id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY)");
            $stm->execute();
            $stm = $connect->prepare($structure);
            $stm->execute();
            $stm = $connect->prepare("INSERT INTO kp_medias (name, type, description, deleted, path) VALUES (?, ?, ?, ?, ?)");
            $stm->execute(array(
                "mainIcon",
                1,
                "",
                0,
                "KW/kapweb_inits/ressources/medias/kwLogoOrange.png"
            ));
            $stm = $connect->prepare("INSERT INTO kp_medias (name, type, description, deleted, path) VALUES (?, ?, ?, ?, ?)");
            $stm->execute(array(
                "mainIconBlack",
                1,
                "",
                0,
                "KW/kapweb_inits/ressources/medias/kwLogo.png"
            ));
        }
        if ($db::tabelExists("kp_typemedias") == false) {
            $structure = "ALTER TABLE kp_typemedias ADD COLUMN name varchar(255) NOT NULL;"
                . "ALTER TABLE kp_typemedias ADD COLUMN icon varchar(255) NOT NULL DEFAULT \"bx bx-image\";"
                . "ALTER TABLE kp_typemedias ADD COLUMN description text;";
            $stm = $connect->prepare("CREATE TABLE IF NOT EXISTS kp_typemedias (id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY)");
            $stm->execute();
            $stm = $connect->prepare($structure);
            $stm->execute();
            $stm = $connect->prepare("INSERT INTO kp_typemedias (name, description, icon) VALUES (?, ?, ?)");
            $stm->execute(array("images", "images of website", "bx bx-image"));
            $stm = $connect->prepare("INSERT INTO kp_typemedias (name, description, icon) VALUES (?, ?, ?)");
            $stm->execute(array("videos", "videos of website", "bx bxs-videos"));
            $stm = $connect->prepare("INSERT INTO kp_typemedias (name, description, icon) VALUES (?, ?, ?)");
            $stm->execute(array("audios", "audios of website", "bx bxs-music"));
        }
        if ($add_kp_tables == true) {
            $stm = $connect->prepare("INSERT INTO kp_tables (name, rows, types, args, hided, editable_structure, editable_content, deletable) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "kp_tables",
                9,
                "varchar,int,text,text,tinyint,tinyint,tinyint,text,tinyint,tinyint",
                "name,rows,types,args,deleted,hided,editable_structure,description,editable_content,deletable",
                1,
                0,
                0,
                0
            ));
            $stm = $connect->prepare("INSERT INTO kp_tables (name, rows, types, args, hided, editable_structure, editable_content, deletable) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "pages",
                16,
                "int,varchar,varchar,varchar,varchar,tinyint,tinyint,tinyint,varchar,varchar,tinyint,varchar,tinyint,tinyint,int,tinyint",
                "pid,name,url,title,path,deleted,hided,editable,pathCss,pathJs,mainPage,ico,needConnect,needConnectSu,langauge,builtin",
                1,
                0,
                0,
                0
            ));
            $stm = $connect->prepare("INSERT INTO kp_tables (name, rows, types, args, hided, editable_structure, editable_content, deletable) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "kp_cookies",
                5,
                "varchar,text,int,tinyint,tinyint,tinyint",
                "name,description,lifetime,deleted,editable",
                1,
                0,
                0,
                0
            ));
            $stm = $connect->prepare("INSERT INTO kp_tables (name, rows, types, args, hided, editable_structure, editable_content, deletable) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "no_users",
                13,
                "int,int,int,int,int,varchar,varchar,varchar,varchar,varchar,int,int,tinyint,tinyint",
                "uid,cr_date,ls_mod,ls_con,ls_mod_uid,lname,fname,pseudo,email,password,lang,status,banned,deleted",
                1,
                0,
                0,
                0
            ));
            $stm = $connect->prepare("INSERT INTO kp_tables (name, rows, types, args, hided, editable_structure, editable_content, deletable) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "su_users",
                14,
                "int,int,int,int,int,varchar,varchar,varchar,varchar,varchar,int,int,int,tinyint,tinyint",
                "uid,cr_date,ls_mod,ls_con,ls_mod_uid,lname,fname,pseudo,email,password,lang,status,access,banned,deleted",
                1,
                0,
                0,
                0
            ));
            $stm = $connect->prepare("INSERT INTO kp_tables (name, rows, types, args, hided, editable_structure, editable_content, deletable) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "kp_mailconfirm",
                6,
                "varchar,varchar,int,tinyint,tinyint,tinyint",
                "cid,email,uid,isSu,checked,changeMail",
                1,
                0,
                0,
                0
            ));
            $stm = $connect->prepare("INSERT INTO kp_tables (name, rows, types, args, hided, editable_structure, editable_content, deletable) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "kp_languages",
                3,
                "int,varchar,varchar",
                "id,name,name_short",
                1,
                0,
                0,
                0
            ));
            $stm = $connect->prepare("INSERT INTO kp_tables (name, rows, types, args, hided, editable_structure, editable_content, deletable) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "kp_access",
                3,
                "int,varchar,text",
                "aid,name,tableAcces",
                1,
                0,
                0,
                0
            ));
            $stm = $connect->prepare("INSERT INTO kp_tables (name, rows, types, args, hided, editable_structure, editable_content, deletable) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "kp_medias",
                6,
                "int,varchar,int,text,int,varchar",
                "id,name,type,description,deleted,path",
                1,
                0,
                0,
                0
            ));
            $stm = $connect->prepare("INSERT INTO kp_tables (name, rows, types, args, hided, editable_structure, editable_content, deletable) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stm->execute(array(
                "kp_typemedias",
                3,
                "int,varchar,text",
                "id,name,description",
                1,
                0,
                0,
                0
            ));
        }
        $db->disconnect();
    }

    public static function getAccessList():array {
        global $db;
        $res = array();

        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_access WHERE 1");
        $stm->execute();
        while ($resStm = $stm->fetch()) {
            $acc = $resStm['tableAccess'];
            if ($acc == "*") {
                $acc = "All";
            } else if ($acc == "") {
                $acc = "Nothing";
            }
            array_push($res, array("name" => $resStm['name'], "access" => $acc));
        }
        $db->disconnect();

        return $res;
    }

    public static function addNewAcces(array $intels) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("INSERT INTO kp_access (name, tableAccess) VALUES (?, ?)");
        $stm->execute(array($intels['name'], $intels['access']));
        $db->disconnect();
    }

    public static function haveAccesTo(string $nameTable):bool {
        if (self::isConnectedSu() == false)
            return false;
        global $db;
        $access = "";
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM su_users WHERE email=?");
        $stm->execute(array($_SESSION['suemail']));
        $resStm = $stm->fetch();
        $access = $resStm['access'];
        $stm = $connect->prepare("SELECT * FROM kp_access WHERE aid=?");
        $stm->execute(array($access));
        $resStm = $stm->fetch();
        $db->disconnect();
        $access = $resStm['tableAccess'];
        if ($access == "*")
                return true;
        $accesArr = explode(",", $access);
        for ($i = 0; $i < count($accesArr); $i++) {
            if ($accesArr[$i] == "*")
                return true;
            if ($accesArr[$i] == $nameTable)
                return true;
        }
        return false;
    }

    public static function getAccessListing():array {
        global $ext;
        $res = array(
            "Dashboard",
            "Administrors",
            "Users",
            "Access",
            "navMenuAdmin",
            "Pages",
            "Deleted Pages",
            "navMenuWebsite",
            "Cookies",
            "Database",
            "Deleted Database",
            "Extensions",
            "navMenuFiles",
            "Redirects",
            "Medias",
            "refreshMedias",
        );
        $extentions = $ext->getExtensionsBackList();
        for ($extId = 0; $extId < count($extentions); $extId++) {
            for ($backId = 0; $backId < count($extentions[$extId]); $backId++) {
                array_push($res, $extentions[$extId][$backId]['access']);
            }
        }
        return $res;
    }

    public static function haveExtensionAccesFromMainClass(string $mainClass):bool {
        global $ext;
        $extentions = $ext->getExtensionsBackList();
        for ($extId = 0; $extId < count($extentions); $extId++) {
            for ($backId = 0; $backId < count($extentions[$extId]); $backId++) {
                $backMainClass = $extentions[$extId][$backId]['button']['category'];
                if ($backMainClass == $mainClass) {
                    return self::haveAccesTo($extentions[$extId][$backId]['access']);
                }
            }
        }
        return false;
    }

    public static function getTables():array {
        global $db;
        $res = array();
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_tables WHERE deleted=0 AND hided=0");
        $stm->execute();
        while ($resStm = $stm->fetch()) {
            array_push($res, $resStm);
        }
        $db->disconnect();
        return $res;
    }

    public static function deleteTable($name) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE kp_tables SET deleted=1 WHERE name=?");
        $stm->execute(array($name));
        $db->disconnect();
    }

    public static function restoreTable($name) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE kp_tables SET deleted=0 WHERE name=?");
        $stm->execute(array($name));
        $db->disconnect();
    }

    public static function finalDeleteTable($name) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("DELETE FROM kp_tables WHERE name=?");
        $stm->execute(array($name));
        $stm = $connect->prepare("DROP TABLE " . $name);
        $stm->execute();
        $db->disconnect();
    }

    public static function deleteAllTables() {
        global $db;
        $allTables = array();
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_tables WHERE deletable = 1");
        $stm->execute();
        while ($resStm = $stm->fetch()) {
            array_push($allTables, $resStm['name']);
        }
        for ($i = 0; $i < count($allTables); $i++) {
            $stm = $connect->prepare("DELETE FROM kp_tables WHERE name=?");
            $stm->execute($allTables[$i]);
            $stm = $connect->prepare("DROP TABLE " . $allTables[$i]);
            $stm->execute();
        }
        $db->disconnect();
    }

    public static function addTable($tableName, $lineEdit, $argName, $description) {
        global $db;
        $finalSent = "CREATE TABLE IF NOT EXISTS " . $tableName . " (" . $lineEdit .");";
        $connect = $db->connect();
        $stm = $connect->prepare($finalSent);
        $stm->execute();
        $stm = $connect->prepare("INSERT INTO kp_tables (name, rows, args, deleted, hided, editable_structure, description, editable_content, deletable) VALUES " 
            . " (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stm->execute(array($tableName, 1, $argName, 0, 0, 1, $description, 1, 1));
        $db->disconnect();
    }

    public static function addTableArg($tableName, $lineEdit, $argName) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("ALTER TABLE " . $tableName . " ADD COLUMN " . $lineEdit);
        $stm->execute();
        $stm = $connect->prepare("SELECT * FROM kp_tables WHERE name=?");
        $stm->execute(array($tableName));
        $resStm = $stm->fetch();
        if ($resStm) {
            $nargs = $resStm['args'];
            if ($nargs == "") {
                $nargs = $argName;
            } else {
                $nargs .= "," . $argName;
            }
            $stm = $connect->prepare("UPDATE kp_tables SET args=? WHERE name=?");
            $stm->execute(array($nargs, $tableName));
        }
        $db->disconnect();
    }

    public static function getTable(string $name):array {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_tables WHERE name=?");
        $stm->execute(array($name));
        $res = $stm->fetch();
        if ($res)
            return $res;
        $db->disconnect();
        return array();
    }

    public static function getRowsTable(string $name):array {
        global $db;
        $resTot = array();
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM " . $name . " WHERE 1");
        $stm->execute(array($name));
        while ($res  = $stm->fetch()) {
            array_push($resTot, $res);
        }
        $db->disconnect();
        return $resTot;
    }

    /*
            PAGE MANAGING
    */

    public static function mainPageExists():bool {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM pages WHERE mainPage = 1");
        $stm->execute();
        $res = $stm->fetch();
        $db->disconnect();
        if ($res) {
            return true;
        }
        return false;
    }

    private static function neededPageFilesExists($name):bool {
        $res = true;
        $pathPhp = "KW/public/pages/" . $name . ".conf";
        $pathCss = "KW/public/ressources/css/" . $name . ".css";
        $pathJs = "KW/public/ressources/js/" . $name . ".js";
        $res = file_exists($pathPhp);
        if ($res == true) {
            $res = file_exists($pathCss);
        }
        if ($res == true) {
            $res = file_exists($pathJs);
        }
        return $res;
    }

    public static function pageExists($name):bool {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM pages WHERE deleted=0 AND name=?");
        $stm->execute(array($name));
        $resStm = $stm->fetch();
        $db->disconnect();
        if ($resStm) {
            if ($resStm['builtin'] == "0") {
                $fExists = self::neededPageFilesExists($resStm['name']);
                if ($fExists == false) {
                    self::createBasicsPagesFiles($resStm['name']);
                }
            }
            return true;
        }
        return false;
    }

    public static function getRedirectPage(string $urlStr):array {
        global $db;
        $connect = $db->connect();
        $res = array();
        $stm = $connect->prepare("SELECT * FROM kp_redirects WHERE new_path=? AND deleted=0");
        $stm->execute(array($urlStr));
        $resStm = $stm->fetch();
        $db->disconnect();
        if ($resStm) {
            $nNumberHits = $resStm['used'] + 1;
            $stm = $connect->prepare("UPDATE kp_redirects SET used=? WHERE new_path=?");
            $stm->execute(array($nNumberHits, $urlStr));
            $totNUrl = $resStm['last_path'];
            if ($totNUrl == "/") {
                return array("");
            }
            $resTmp = explode("/", $totNUrl);
            for ($i = 0; $i < count($resTmp); $i++) {
                if (isset($resTmp) && $resTmp[$i] != "") {
                    array_push($res, $resTmp[$i]);
                }
            }
        }
        return $res;
    }

    public static function reinitRedirectHits(int $idReidrect) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE kp_redirects SET used=0 WHERE id=?");
        $stm->execute(array($idReidrect));
        $db->disconnect();
    }

    public static function getPathPage($url):string {
        global $db, $cf;
        $_SESSION['titlePage'] = "Page not found";
        $res = self::getMainUrl() . "/KW/kapweb_inits/pages/pageNotFound.php";
        $totUrl = "";
        $nameGet = "";
        for ($i = 0; $i < count($url); $i++) {
            $totUrl .= "/" . $url[$i];
        }
        if (count($url) <= 0) {
            $totUrl = "/";
        }
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM pages WHERE url = ? AND deleted = 0");
        $stm->execute(array($totUrl));
        $resStm = $stm->fetch();
        unset($_SESSION['cssPath']);
        unset($_SESSION['jsPath']);
        unset($_SESSION['ico']);
        $_SESSION['titlePage'] = "Unknow";
        unset($_SESSION['pageName']);
        if ($resStm) {
            $res = $resStm['path'];
            $nameGet = $resStm['name'];
            $_SESSION['titlePage'] =  $resStm['title'];
            $_SESSION['language'] = self::getLanguageShortFromId($resStm['language']);
            if ($resStm['builtin'] == 0) {
                $_SESSION['pageName'] = $resStm['name'];
            }
            if ($resStm['needConnect']) {
                unset($_SESSION['pathAfterConnect']); 
                if (self::isConnected() == false) {
                    $_SESSION['pathAfterConnect'] = self::getMainUrl() . $totUrl;
                    return self::getPathPage(array("connect"));
                }
            }
            if ($resStm['needConnectSu']) {
                unset($_SESSION['pathAfterConnect']); 
                if (self::isConnected() == false) {
                    $_SESSION['pathAfterConnect'] = self::getMainUrl() . $totUrl;
                    if (isset($_SESSION['urlEdit'])) {
                        $_SESSION['pathAfterConnect'] = $_SESSION['pathAfterConnect'] . "/" . $_SESSION['urlEdit'];
                    }
                    return self::getPathPage(array("KW"));
                }
            }
            if ($resStm['pathCss'] != NULL) {
                $_SESSION['cssPath'] = self::getMainUrl() . $resStm['pathCss'];
            }
            if ($resStm['pathJs'] != NULL) {
                $_SESSION['jsPath'] = self::getMainUrl() . $resStm['pathJs'];
            }
            if ($resStm['ico'] != NULL) {
                $_SESSION['icoPage'] = self::getMainUrl() . $resStm['ico'];
            } else {
                $_SESSION['icoPage'] = $cf->sys_getMainIco();
            }
        } else {
            $redir = self::getRedirectPage($totUrl);
            if (count($redir) > 0) {
                return self::getPathPage($redir);
            }
            $connect = $db->connect();
            $stm = $connect->prepare("SELECT 1 from pages LIMIT 1");
            $stm->execute();
            $res = $stm->fetch();
            $db->disconnect();
            if ($res) {
                return self::getPathPage(array("pageNotFound"));
            } else {
                var_dump("oups2");
                $_SESSION['cssPath'] = self::getMainUrl() . "/KW/kapweb_inits/ressources/css/pageNotFound.css";
                return "KW/kapweb_inits/pages/pageNotFound.php";
            }
        }
        $db->disconnect();
        $haveToCr = self::haveToCreatePageFile($url);
        $haveToCrB = "false";
        if ($haveToCr == true)
            $haveToCrB = "true";
        if ($haveToCr == true && self::neededPageFilesExists($nameGet) == false && $nameGet != "") {
            self::createBasicsPagesFiles($nameGet);
        }
        return $res;
    }

    public static function haveToCreatePageFile(array $url):bool {
        if (count($url) >= 1 && $url[0] == "KW") {
            return false;
        }
        if (count($url) >= 1 && $url[0] == "confirmEmail")
            return false;
        if (count($url) >= 2 && $url[1] == "dbConnect")
            return false;
        if (count($url) >= 1 && $url[0] == "pageNotFound")
            return false;
        if (count($url) >= 1 && $url[0] == "connect")
            return false;
        return true;
    }

    public static function deleteAllPages() {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM pages WHERE 1");
        $stm->execute();
        while ($resStm = $stm->fetch()) {
            $resName = $resStm['name'];
            if ($resStm['builtin'] == "0") {
                $stm = $connect->prepare("DELETE FROM pages WHERE name = ?");
                $stm->execute(array($resName));
                unlink("KW/public/pages/" . $resName . ".conf");
                unlink("KW/public/ressources/css/" . $resName . ".css");
                unlink("KW/public/ressources/js/" . $resName . ".js");
            }
        }
        $db->disconnect();
    }

    public static function deletePage($name) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE pages SET deleted=1 WHERE name = ?");
        $stm->execute(array($name));
        $db->disconnect();
    }

    public static function finalDeletePages($name, $htmlPath, $cssPath, $jsPath) {
        global $db, $cf;
        $connect = $db->connect();
        $stm = $connect->prepare("DELETE FROM pages WHERE name=?");
        $stm->execute(array($name));
        $cssPath = $cf->getStrFromPos($cssPath, 1);
        $jsPath = $cf->getStrFromPos($jsPath, 1);
        unlink($cssPath);
        unlink($jsPath);
        unlink("KW/public/pages/" . $name . ".conf");
    }

    public static function restorePage($name) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE pages SET deleted=0 WHERE name = ?");
        $stm->execute(array($name));
        $db->disconnect();
    }

    public static function getPathPageFromName(string $namePage):string {
        global $db;

        $res = "/KW/kapweb_inits/pages/pageNotFound.php";
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM pages WHERE name=?");
        $stm->execute(array($namePage));
        $resStm = $stm->fetch();
        $db->disconnect();
        if ($resStm) {
            $res = $resStm['path'];
        }
        return $res;
    }

    public static function getUrlFromName(string $namePage):string {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM pages WHERE name=?");
        $stm->execute(array($namePage));
        $resStm = $stm->fetch();
        if ($resStm) {
            return $resStm['url'];
        }
        $db->disconnect();
        return "";
    }

    public static function getPages():array {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM pages WHERE deleted=0 AND hided=0 AND editable=1");
        $stm->execute();
        $res = array();
        while ($resStm = $stm->fetch()) {
            array_push($res, $resStm);
        }
        $db->disconnect();
        return $res;
    }

    public static function getDeletedPages():array {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM pages WHERE deleted=1 AND hided=0 AND editable=1");
        $stm->execute();
        $res = array();
        while ($resStm = $stm->fetch()) {
            array_push($res, $resStm);
        }
        $db->disconnect();
        return $res;
    }

    private static function createBasicsPagesFiles(string $name) {
        //$pathPhp = "KW/public/pages/" . $name . ".php";
        $pathConf = "KW/public/pages/" . $name . ".conf";
        $pathCss = "KW/public/ressources/css/" . $name . ".css";
        $pathJs = "KW/public/ressources/js/" . $name . ".js";
        //$f = fopen($pathPhp, "w");
        /*if ($f) {
            fwrite($f, self::$phpBaseContent, strlen(self::$phpBaseContent));
        }*/
        //fclose($f);
        $f = fopen($pathConf, "w");
        if ($f) {
            fwrite($f, self::$confFileBase, strlen(self::$confFileBase));
        }
        fclose($f);
        $f = fopen($pathCss, "w");
        if ($f) {
            fwrite($f, self::$cssBaseContent, strlen(self::$cssBaseContent));
        }
        fclose($f);
        $f = fopen($pathJs, "w");
        if ($f) {
            $jsMsg = self::$jsBaseContent . $name;
            fwrite($f, $jsMsg, strlen($jsMsg));
        }
        fclose($f);
    }

    public static function addPage(string $name, string $url, string $title, bool $mainPage, int $lang):bool {
        global $db, $cf;
        if (self::pageExists($name))
            return false;
        $connect = $db->connect();
        $isMAin = 1;
        if (self::mainPageExists() == true && $mainPage == true) {
            $stm = $connect->prepare("SELECT * FROM pages WHERE mainPage=1");
            $stm->execute();
            while ($resStm = $stm->fetch()) {
                $stm2 = $connect->prepare("UPDATE pages SET mainPage=0 WHERE name=?");
                $stm2->execute(array($resStm['name']));
            }
        } else if ($mainPage == false) {
            $isMain = 0;
        }
        $stm = $connect->prepare("INSERT INTO `pages` (`name`, `url`, `title`, `path`, `mainPage`, `pathCss`, `pathJs`, `ico`, language) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stm->execute(array(
            $name,
            $url,
            $title,
            "KW/public/displayPage.php",
            $isMAin,
            "/KW/public/ressources/css/" . $name . ".css",
            "/KW/public/ressources/js/" . $name . ".js",
            "/" . $cf->sys_getMainIco(),
            $lang,
        ));
        self::createBasicsPagesFiles($name);
        $db->disconnect();
        return true;
    }

    public static function getEditablePhpPart(string $namePage) {
        global $db;
        $res = "";
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM pages WHERE name=?");
        $stm->execute(array($namePage));
        $resStm = $stm->fetch();
        $db->disconnect();

        if ($resStm) {

        }
        return $res;
    }

    public static function getPageIntelsFromName(string $name):array {
        global $db;
        $res = array();
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM pages WHERE name = ?");
        $stm->execute(array($name));
        $resStm = $stm->fetch();
        if ($resStm)
            return $resStm;
        $db->disconnect();
        return $res;
    }

    public static function addStrinStrAtPos(string $str, string $add, int $pos) {
        $res = "";

        if (strlen($str) < $pos)
            $pos = strlen($str);
        for ($i = 0; $i < $pos; $i++) {
            $res .= $str[$i];
        }
        $res .= $add;
        for ($i = $pos; $i < strlen($str); $i++) {
            $res .= $str[$i];
        }

        return $res;
    }

    public static function getRedirectsList():array {
        global $db;
        $res = array();
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_redirects WHERE 1");
        $stm->execute();
        while ($resStm = $stm->fetch()) {
            array_push($res, $resStm);
        }
        $db->disconnect();
        return $res;
    }

    public static function urlIsRedirected($urlStr):bool {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_redirects WHERE last_path=? AND deleted=0");
        $stm->execute(array($urlStr));
        $resStm = $stm->fetch();
        $db->disconnect();
        if ($resStm) {
            return true;
        }
        return false;
    }

    public static function updateDeletedRedirect(int $id, int $value) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE kp_redirects SET deleted=? WHERE id=?");
        $stm->execute(array($value, $id));
        $db->disconnect();
    }

    public static function createRedirection(string $last_url, string $new_url):int {
        if (self::urlIsRedirected($last_url) == true) {
            return 1;
        }
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("INSERT INTO kp_redirects (last_path, new_path, deleted) VALUES (?, ?, ?)");
        $stm->execute(array($last_url, $new_url, 0));
        $db->disconnect();
        if (self::urlIsRedirected($last_url) == true) {
            return 0;
        }
        return 2;
    }

    /*
            ACCOUNT MANAGING
    */

    public static function suAccountExists():bool {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM su_users WHERE deleted=0");
        $stm->execute();
        $res = $stm->fetch();
        $db->disconnect();
        if ($res)
            return true;
        return false;
    }

    public static function createSuAccount($pseudo, $email, $pwd, $lname="", $fname="", $lang=1, $access=2):int {
        if (self::isEmailUsedInAccounts($email))
            return 0;
        global $db;
        $connect = $db->connect();
        $hash_opt = [
            "cost" => 11
        ];
        $ctime = time();
        $stm = $connect->prepare("INSERT INTO su_users (email, password, cr_date, ls_mod, ls_con, ls_mod_uid, access) VALUES (?, ?, ?, ?, ?, 0, ?)");
        $stm->execute(array(
            $email,
            password_hash($pwd,  PASSWORD_DEFAULT, $hash_opt),
            $ctime,
            $ctime,
            $ctime,
            $access
        ));
        if (isset($pseudo)) {
            $stm = $connect->prepare("UPDATE su_users SET pseudo=? WHERE email=?");
            $stm->execute(array($pseudo, $email));
        }
        if (isset($lname)) {
            $stm = $connect->prepare("UPDATE su_users SET lname=? WHERE email=?");
            $stm->execute(array($lname, $email));
        }
        if (isset($fname)) {
            $stm = $connect->prepare("UPDATE su_users SET fname=? WHERE email=?");
            $stm->execute(array($fname, $email));
        }
        if (isset($lang)) {
            $stm = $connect->prepare("UPDATE su_users SET lang=? WHERE email=?");
            $stm->execute(array($lang, $email));
        }
        $db->disconnect();
        if (self::isEmailUsedInAccounts($email)) {
            self::generateConfirmMail($email, 1);
            return 1;
        }
        return 2;
    }

    public static function isSuAccount($email, $pwd): int {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM su_users WHERE email=?");
        $stm->execute(array($email));
        $resStm = $stm->fetch();
        $db->disconnect();
        if ($resStm) {
            if ($resStm['deleted'])
                return 2;
            if ($resStm['baned'])
                return 3;
            if ($resStm['status'] == 0)
                return 5;
            if ($resStm['status'] == 2)
                return 6;
            if (password_verify($pwd, $resStm['password']))
                return 1;
            return 4;
        }
        return 0;
    }

    public static function isNoAccount($email, $pwd): int {
        global $db;

        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM no_users WHERE email=?");
        $stm->execute(array($email));
        $resStm = $stm->fetch();
        $db->disconnect();
        if ($resStm) {
            if ($resStm['deleted'])
                return 2;
            if ($resStm['baned'])
                return 3;
            if ($resStm['status'] == 0)
                return 5;
            if ($resStm['status'] == 0)
                return 6;
            if (password_verify($pwd, $resStm['password']))
                return 1;
            return 4;
        }
        return 0;
    }

    public static function getUidNoAccount($email, $pwd): int {
        global $db;
        $res = -1;

        if (self::isNoAccount($email, $pwd) == false)
            return $res;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM no_users WHERE email=?");
        $stm->execute(array($email));
        $resStm = $stm->fetch();
        $db->disconnect();
        if ($resStm) {
            if (password_verify($pwd, $resStm['password']))
                $res = $resStm['uid'];
        }
        return $res;
    }

    public static function disconnectSelf() {
        if (isset($_SESSION['suemail']))
            unset($_SESSION['suemail']);
        if (isset($_SESSION['supwd']))
            unset($_SESSION['supwd']);
        if (isset($_SESSION['no_pwd']))
            unset($_SESSION['no_pwd']);
        if (isset($_SESSION['no_email']))
            unset($_SESSION['no_email']);
    }

    public static function isConnectedSu():bool {
        $res = true;
        if (!isset($_SESSION['suemail']))
            $res = false;
        if (!isset($_SESSION['supwd']) && $res == true)
            $res = false;
        if ($res == true) {
            $res = self::isSuAccount($_SESSION['suemail'], $_SESSION['supwd']);
        }
        return $res;
    }

    public static function isConnectedNo():bool {
        $res = true;
        if (!isset($_SESSION['no_email']))
            $res = false;
        if (!isset($_SESSION['no_pwd']) && $res == true)
            $res = false;
        if ($res == true) {
            $res = self::isSuAccount($_SESSION['no_email'], $_SESSION['no_pwd']);
        }
        return $res;
    }

    public static function isConnected(): bool {
        if (self::isConnectedNo() == true || self::isConnectedSu() == true)
            return true;
        return false;
    }

    public static function clearCashAccount() {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("DELETE FROM su_users WHERE deleted=1");
        $stm->execute();
        $stm = $connect->prepare("DELETE FROM no_users WHERE deleted=1");
        $stm->execute();
        $db->disconnect();
    }

    public static function deleteSuUserAccount(string $email) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE su_users SET deleted=1 WHERE email=?");
        $stm->execute(array($email));
        $db->disconnect();
    }

    public static function restoreSuUserAccount(string $email) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE su_users SET deleted=0 WHERE email=?");
        $stm->execute(array($email));
        $db->disconnect();
    }

    public static function banSuUserAccount(string $email) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE su_users SET baned=1 WHERE email=?");
        $stm->execute(array($email));
        $db->disconnect();
    }

    public static function unbanSuUserAccount(string $email) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE su_users SET baned=0 WHERE email=?");
        $stm->execute(array($email));
        $db->disconnect();
    }

    public static function banNoUserAccount(string $email) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE no_users SET baned=1 WHERE email=?");
        $stm->execute(array($email));
        $db->disconnect();
    }

    public static function unbanNoUserAccount(string $email) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE no_users SET baned=0 WHERE email=?");
        $stm->execute(array($email));
        $db->disconnect();
    }
    
    public static function deleteNoUserAccount(string $email) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE no_users SET deleted=1 WHERE email=?");
        $stm->execute(array($email));
        $db->disconnect();
    }

    public static function restoreNoUserAccount(string $email) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE no_users SET deleted=0 WHERE email=?");
        $stm->execute(array($email));
        $db->disconnect();
    }

    public static function isEmailUsedInAccounts($email):bool {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM su_users WHERE email = ?");
        $stm->execute(array($email));
        $resStm = $stm->fetch();
        if ($resStm) {
            $db->disconnect();
            return true;
        }
        $stm = $connect->prepare("SELECT * FROM no_users WHERE email = ?");
        $stm->execute(array($email));
        $resStm = $stm->fetch();
        if ($resStm) {
            $db->disconnect();
            return true;
        }
        $db->disconnect();
        return false;
    }

    public static function createNoAccount($email, $pwd, $lname, $fname, $pseudo):int {
        if (self::isEmailUsedInAccounts($email))
            return 0;
        global $db;
        $hash_opt = [
            "cost" => 11
        ];
        $connect = $db->connect();
        $gpwd = password_hash($pwd, PASSWORD_DEFAULT, $hash_opt);
        $ctime = time();
        $stm = $connect->prepare("INSERT INTO no_users (cr_date, ls_mod, ls_con, ls_mod_uid, email, password, pseudo, fname, lname) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stm->execute(array(
            $ctime,
            $ctime,
            $ctime,
            0,
            $email,
            $gpwd,
            $pseudo,
            $fname,
            $lname
        ));
        $db->disconnect();
        $checkAccount = self::isNoAccount($email, $pwd);
        if ($checkAccount == 1 || $checkAccount == 5) {
            self::generateConfirmMail($email, 0);
            return 1;
        }
        return 2;
    }

    public static function getAdmins():array {
        global $db;
        $res = array();

        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM su_users WHERE 1");
        $stm->execute();
        while ($resStm = $stm->fetch()) {
            array_push($res, $resStm);
        }
        $db->disconnect();
        return $res;
    }

    public static function getUsers():array {
        global $db;
        $res = array();

        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM no_users WHERE 1");
        $stm->execute();
        while ($resStm = $stm->fetch()) {
            array_push($res, $resStm);
        }
        $db->disconnect();
        return $res;
    }

    public static function getMyAccountConnect():array {
        global $db;
        if (self::isConnectedSu()) {
            $res = array();
            $connect = $db->connect();
            $stm = $connect->prepare("SELECT * FROM su_users WHERE email=?");
            $stm->execute(array($_SESSION['suemail']));
            $resStm = $stm->fetch();
            if ($resStm)
                $res = $resStm;
            $db->disconnect();
            return $res;
        } else if (self::isConnectedNo()) {
            $res = array();
            $connect = $db->connect();
            $stm = $connect->prepare("SELECT * FROM no_users WHERE email=?");
            $stm->execute(array($_SESSION['no_email']));
            $resStm = $stm->fetch();
            if ($resStm)
                $res = $resStm;
            $db->disconnect();
            return $res;
        } else {
            return array();
        }
    }

    public static function deleteAllAcoounts() {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("DELETE FROM su_users WHERE 1");
        $stm->execute();
        $stm = $connect->prepare("DELETE FROM no_users WHERE 1");
        $stm->execute();
        $db->disconnect();
    }

    public static function getAccountIntels():array {
        global $db;
        if (self::isConnectedSu()) {
            $connect = $db->connect();
            $stm = $connect->prepare("SELECT * FROM su_users WHERE email=?");
            $stm->execute(array($_SESSION['suemail']));
            $resStm = $stm->fetch();
            $db->disconnect();
            if ($resStm) {
                return $resStm;
            }
        }
        if (self::isConnectedNo()) {
            $connect = $db->connect();
            $stm = $connect->prepare("SELECT * FROM no_users WHERE email=?");
            $stm->execute(array($_SESSION['no_email']));
            $resStm = $stm->fetch();
            $db->disconnect();
            if ($resStm) {
                return $resStm;
            }
        }
        return array();
    }

    public static function setLanguageToAccountLanguage() {
        global $db;
        if (self::isConnectedSu()) {
            $connect = $db->connect();
            $stm = $connect->prepare("SELECT lang FROM su_users WHERE email=?");
            $stm->execute(array($_SESSION['suemail']));
            $resStm = $stm->fetch();
            if ($resStm) {
                $lang = $resStm['lang'];
                $stm = $connect->prepare("SELECT * FROM kp_languages WHERE id=?");
                $stm->execute(array($lang));
                $resStm = $stm->fetch();
                if ($resStm) {
                    $_SESSION['language'] = $resStm['name_short'];
                }
            }
            $db->disconnect();
        }
        if (self::isConnectedNo()) {
            $connect = $db->connect();
            $stm = $connect->prepare("SELECT lang FROM no_users WHERE email=?");
            $stm->execute(array($_SESSION['no_email']));
            $resStm = $stm->fetch();
            if ($resStm) {
                $lang = $resStm['lang'];
                $stm = $connect->prepare("SELECT * FROM kp_languages WHERE id=?");
                $stm->execute(array($lang));
                $resStm = $stm->fetch();
                if ($resStm) {
                    $_SESSION['language'] = $resStm['name_short'];
                }
            }
            $db->disconnect();
        }
    }

    public static function changeSuAccountLanguage(string $lang, string $email) {
        global $db;
        $langId = self::getLanguageIdFromShort($lang);
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE su_users SET lang=? WHERE email=?");
        $stm->execute(array($langId, $email));
        $db->disconnect();
    }

    public static function changeNoAccountLanguage(string $lang, string $email) {
        global $db;
        $langId = self::getLanguageIdFromShort($lang);
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE no_users SET lang=? WHERE email=?");
        $stm->execute(array($langId, $email));
        $db->disconnect();
    }

    public static function disableSuAccount(int $uid) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE su_users SET status=2 WHERE uid=?");
        $stm->execute(array($uid));
        $db->disconnect();
    }

    public static function enableSuAccount(int $uid) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE su_users SET status=1 WHERE uid=?");
        $stm->execute(array($uid));
        $db->disconnect();
    }

    /*
            COOKIES MANAGING
    */

    public static function getCookies():array {
        $res = array();
        return $res;
    }

    public static function deleteCookie($name) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE kp_cookies SET deleted=1 WHERE name=?");
        $stm->execute(array($name));
        $db->disconnect();
    }

    public static function restoreCookie($name) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("UPDATE kp_cookies SET deleted=0 WHERE name=?");
        $stm->execute(array($name));
        $db->disconnect();
    }

    public static function finalDeleteCookie($name) {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("DELETE FROM kp_cookies WHERE name=?");
        $stm->execute(array($name));
        $db->disconnect();
    }

    public static function addCookie(string $name, string $description, int $lifetime):bool {
        if (self::cookieExists($name) == true)
            return false;
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("INSERT INTO kp_cookies (name, description, lifetime, deleted, editable) VALUES (?, ?, ?, ?, ?)");
        $stm->execute(array($name, $description, $lifetime, 0, 1));
        $db->disconnect();
        return true;
    }

    public static function cookieExists(string $name):bool {
        global $db;
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_cookies WHERE name=?");
        $stm->execute(array($name));
        if ($stm->fetch())
            return true;
        $db->disconnect();
        return false;
    }

    public static function refreshAllMed(int $type = -1, array $meds = array()) {
        if (count($meds) <= 0) {
            $meds = self::getAllMedias();
        }
        $sizeMeds = count($meds);
        if ($sizeMeds <= 0) {
            return;
        }
        foreach($meds as $media) {
            if ($type == -1 || $type == $media['type']) {
                if (file_exists($media['path']) == false) {
                    self::deleteMedia($media['id']);
                }
            }
        }
    }

    public static function refreshDb() {
        global $db, $ext, $cf;
        $extensions = $ext->getExtensionList();
        $connect = $db->connect();
        $stm = $connect->prepare("SELECT * FROM kp_tables WHERE hided=0");
        $stm->execute();
        while ($resStm = $stm->fetch()) {
            foreach ($extensions as $ext) {
                if ($ext['isDbExt'] == "true") {
                    $dbs = $cf->getValueFromKeyConf($ext['path'] . "/database/db.conf", "table-used");
                    $dbList = explode(",", $dbs);
                    foreach ($dbList as $dbTarget) {
                        if ($dbTarget != "" && $dbTarget == $resStm['name']) {
                            $stm2 = $connect->prepare("DELETE FROM kp_tables WHERE name=?");
                            $stm2->execute(array($dbTarget));
                            $stm2 = $connect->prepare("DROP TABLE $dbTarget");
                            $stm2->execute();
                        }
                    }
                }
            }
        }
        $db->disconnect();
    }
}

?>