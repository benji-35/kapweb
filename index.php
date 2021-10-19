<?php

session_start();
header("Access-Control-Allow-Origin: https://kit.fontawesome.com/a076d05399.js");

use Application\Database;
use Application\ConfFiles;
use Application\Helpers;
use Application\EditorPage;
use Application\Extensions;

$db_class_name = "Application\Database";

$autoloader_db = function ($db_class_name) {
    // on prépare le terrain : on remplace le séparteur d'espace de nom par le séparateur de répertoires du système
    $name = str_replace('\\', DIRECTORY_SEPARATOR, $db_class_name);
    // on construit le chemin complet du fichier à inclure :
    // il faut que l'autoloader soit toujours à la racine du site : tout part de là avec __DIR__
    $path = __DIR__ . DIRECTORY_SEPARATOR . $name . '.php';

    // on vérfie que le fichier existe et on l'inclut
    // sinon on passe la main à une autre autoloader (return false)
    if (is_file($path)) {
        include $path;
    } else {
        return false;
    }
};

$hlp_class_name = "Application\Helpers";

$autoloader_hlp = function ($hlp_class_name) {
    // on prépare le terrain : on remplace le séparteur d'espace de nom par le séparateur de répertoires du système
    $name = str_replace('\\', DIRECTORY_SEPARATOR, $hlp_class_name);
    // on construit le chemin complet du fichier à inclure :
    // il faut que l'autoloader soit toujours à la racine du site : tout part de là avec __DIR__
    $path = __DIR__ . DIRECTORY_SEPARATOR . $name . '.php';

    // on vérfie que le fichier existe et on l'inclut
    // sinon on passe la main à une autre autoloader (return false)
    if (is_file($path)) {
        include $path;
    } else {
        return false;
    }
};

$cf_class_name = "Application\ConfFiles";

$autoloader_cf = function ($cf_class_name) {
    // on prépare le terrain : on remplace le séparteur d'espace de nom par le séparateur de répertoires du système
    $name = str_replace('\\', DIRECTORY_SEPARATOR, $cf_class_name);
    // on construit le chemin complet du fichier à inclure :
    // il faut que l'autoloader soit toujours à la racine du site : tout part de là avec __DIR__
    $path = __DIR__ . DIRECTORY_SEPARATOR . $name . '.php';

    // on vérfie que le fichier existe et on l'inclut
    // sinon on passe la main à une autre autoloader (return false)
    if (is_file($path)) {
        include $path;
    } else {
        return false;
    }
};

$ep_class_name = "Application\EditorPage";

$autoloader_ep = function ($ep_class_name) {
    // on prépare le terrain : on remplace le séparteur d'espace de nom par le séparateur de répertoires du système
    $name = str_replace('\\', DIRECTORY_SEPARATOR, $ep_class_name);
    // on construit le chemin complet du fichier à inclure :
    // il faut que l'autoloader soit toujours à la racine du site : tout part de là avec __DIR__
    $path = __DIR__ . DIRECTORY_SEPARATOR . $name . '.php';

    // on vérfie que le fichier existe et on l'inclut
    // sinon on passe la main à une autre autoloader (return false)
    if (is_file($path)) {
        include $path;
    } else {
        return false;
    }
};

$ext_class_name = "Application\Extensions";

$autoloader_ext = function ($ext_class_name) {
    // on prépare le terrain : on remplace le séparteur d'espace de nom par le séparateur de répertoires du système
    $name = str_replace('\\', DIRECTORY_SEPARATOR, $ext_class_name);
    // on construit le chemin complet du fichier à inclure :
    // il faut que l'autoloader soit toujours à la racine du site : tout part de là avec __DIR__
    $path = __DIR__ . DIRECTORY_SEPARATOR . $name . '.php';

    // on vérfie que le fichier existe et on l'inclut
    // sinon on passe la main à une autre autoloader (return false)
    if (is_file($path)) {
        include $path;
    } else {
        return false;
    }
};

spl_autoload_register($autoloader_cf);
spl_autoload_register($autoloader_ep);
spl_autoload_register($autoloader_db);
spl_autoload_register($autoloader_hlp);
spl_autoload_register($autoloader_ext);

$db = new Application\Database();
$db = new Database();

$cf = new Application\ConfFiles();
$cf = new ConfFiles();

$ep = new Application\EditorPage();
$ep = new EditorPage();

$hlp = new Application\Helpers();
$hlp = new Helpers();

$ext = new Application\Extensions();
$ext = new Extensions();

$hlp->setDB($db, $cf);
$ep->initEditor($cf, $hlp);
$db->initDb($hlp->getConnectionConfig());
$canConnect = $db->canConnect();
$ext->init_extensions();

if ($hlp->haveConnectionDbIntels() == false || $canConnect == false) {
    require "KW/kapweb_inits/pages/connection_db.php";
} else {
    if (!$hlp->mainPageExists()) {
        $url = array();
        if (isset($_GET['url'])) {
            $url = explode('/', $_GET['url']);
        } else {
            array_push($url, "");
        }
        for ($i = count($url) - 1; $i >= 0; $i--){
            if ($url[$i] == "")
                unset($url[$i]);
        }
        if (count($url) > 0 && $url[0] == "KW") {
            if (count($url) >= 2 && $url[0] == "KW" && $url[1] == "editPage") {
                unset($_SESSION['urlEdit']);
                if (count($url) == 3 && isset($url[2])) {
                    $_SESSION['urlEdit'] = $url[2];
                    unset($url[2]);
                    require $hlp->getPathPage($url);
                } else {
                    require $hlp->getPathPage(array("pageNotFound")); 
                }
            } else if (count($url) >= 2 && $url[0] == "KW" && $url[1] == "editContentTable") {
                unset($_SESSION['tableTarget']);
                if (count($url) == 3 && isset($url[2])) {
                    $_SESSION['tableTarget'] = $url[2];
                    unset($url[2]);
                    require $hlp->getPathPage($url);
                } else {
                    require $hlp->getPathPage(array("pageNotFound")); 
                }
            } else if (count($url) >= 2 && $url[0] == "KW" && $url[1] == "editStructureTable") {
                unset($_SESSION['tableTarget']);
                if (count($url) == 3 && isset($url[2])) {
                    $_SESSION['tableTarget'] = $url[2];
                    unset($url[2]);
                    require $hlp->getPathPage($url);
                } else {
                    require $hlp->getPathPage(array("pageNotFound")); 
                }
            } else {
                require $hlp->getPathPage($url);
            }
        } else if (count($url) == 2 && $url[0] == "confirmEmail") {
            unset($_SESSION['confEmail']);
            $_SESSION['confEmail'] = $url[1];
            unset($url[1]);
            require $hlp->getPathPage($url);
        } else if (count($url) == 2 && $url[0] == "changePassword") {
            unset($_SESSION['chgPwd']);
            $_SESSION['chgPwd'] = $url[1];
            unset($url[1]);
            require $hlp->getPathPage($url);
        } else if (count($url) >= 1 && $url[0] == "pageNotFound") {
            require $hlp->getPathPage($url);
        } else {
            require $hlp->getPathPage(array("pageNotFound"));
        }
    } else {
        $url = array();
        if (isset($_GET['url'])) {
            $url = explode('/', $_GET['url']);
        } else {
            array_push($url, "");
        }
        for ($i = count($url) - 1; $i >= 0; $i--){
            if ($url[$i] == "")
                unset($url[$i]);
        }
        if (count($url) > 0 && $url[0] == "KW" && count($url) < 2) {
            require $hlp->getPathPage(array("KW"));
        } else {
            if (count($url) >= 2 && $url[0] == "KW" && $url[1] == "editPage") {
                unset($_SESSION['urlEdit']);
                if (count($url) == 3 && isset($url[2])) {
                    $_SESSION['urlEdit'] = $url[2];
                    unset($url[2]);
                    require $hlp->getPathPage($url);
                } else {
                    require $hlp->getPathPage(array("pageNotFound")); 
                }
            } else if (count($url) >= 2 && $url[0] == "KW" && $url[1] == "editContentTable") {
                unset($_SESSION['tableTarget']);
                if (count($url) == 3 && isset($url[2])) {
                    $_SESSION['tableTarget'] = $url[2];
                    unset($url[2]);
                    require $hlp->getPathPage($url);
                } else {
                    require $hlp->getPathPage(array("pageNotFound")); 
                }
            } else if (count($url) >= 2 && $url[0] == "KW" && $url[1] == "editStructureTable") {
                unset($_SESSION['tableTarget']);
                if (count($url) == 3 && isset($url[2])) {
                    $_SESSION['tableTarget'] = $url[2];
                    unset($url[2]);
                    require $hlp->getPathPage($url);
                } else {
                    require $hlp->getPathPage(array("pageNotFound")); 
                }
            } else if (count($url) == 2 && $url[0] == "confirmEmail") {
                unset($_SESSION['confEmail']);
                $_SESSION['confEmail'] = $url[1];
                unset($url[1]);
                require $hlp->getPathPage($url);
            } else if (count($url) == 2 && $url[0] == "changePassword") {
                unset($_SESSION['chgPwd']);
                $_SESSION['chgPwd'] = $url[1];
                unset($url[1]);
                require $hlp->getPathPage($url);
            } else{
                unset($_SESSION['chgPwd']);
                unset($_SESSION['confEmail']);
                unset($_SESSION['urlEdit']);
                unset($_SESSION['tableTarget']);
                require $hlp->getPathPage($url);
            }
        }
    }
}