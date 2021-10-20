<?php
    global $db, $hlp, $cf, $ext, $ep;

    if ($hlp->isConnectedSu() == false)
        header("location: " . $hlp->getMainUrl() . "/KW");
    if (!isset($_SESSION['suemail']) || !isset($_SESSION['supwd']))
        header("location: " . $hlp->getMainUrl() . "/KW");
    $pages = $hlp->getPages();
    $deletedPages = $hlp->getDeletedPages();
    $tables = $hlp->getTables();
    $dTables = $hlp->getDeletedTables();
    $admins = $hlp->getAdmins();
    $users = $hlp->getUsers();
    $myAccount = $hlp->getMyAccountConnect();
    $cookies = $hlp->getCookies();
    $languages = $hlp->getRowsTable("kp_languages");
    $accountIntels = $hlp->getAccountIntels();
    $hlp->setLanguageToAccountLanguage();
    $mediaListing = $hlp->getAllMedias();

    if (isset($_POST['createFirstPage'])) {
        unset($_SESSION['pageError']);
        $resCheck = $hlp->addPage($_POST['nameFirstPage'], "/", $_POST['titleFirstPage'], true, $_POST['selectLangPageFirst']);
        if ($resCheck == false)
            $_SESSION['pageError'] = "An error occured while create your first page";
        header("Refresh:" . 0, $_SERVER['PHP_SELF']);
    }
    if (isset($_POST['disconnect'])) {
        $hlp->disconnectSelf();
        header("location: " . $hlp->getMainUrl());
    }
    if (isset($_POST['newPage'])) {
        unset($_SESSION['pageError']);
        $nurl = $_POST['newPageUrl'];
        if ($nurl[0] != '/')
            $nurl = "/" . $nurl;
        $resCheck = $hlp->addPage($_POST['newPageName'], $nurl, $_POST['newTitlePage'], false, $_POST['selectLangPageCreation']);
        if ($resCheck == false)
            $_SESSION['pageError'] = "Page name already taken please get another name for your page";
        header("Refresh:" . 0, $_SERVER['PHP_SELF']);
    }
    if (isset($_POST['deleteAllPages'])) {
        $hlp->deleteAllPages();
        header("Refresh:" . 0, $_SERVER['PHP_SELF']);
    }
    if (isset($_POST['toOKw'])) {
        $hlp->deleteAllPages();
        $hlp->deleteAllAcoounts();
        $hlp->deleteAllTables();
        $hlp->deleteAllFilePage();
        header("location: " . $hlp->getMainUrl());
    }
    if (isset($_POST['reinitAll'])) {
        $hlp->deleteAllPages();
        $hlp->deleteAllAcoounts();
        $hlp->deleteAllTables();
        $hlp->deleteAllFilePage();
        $connect = $db->connect();
        $stm = $connect->prepare("DROP DATABASE " . $hlp->getDbName());
        $stm->execute();
        $db->disconnect();
        $cf->addValueFormKeyConf($cf->getDbConfig(), "db", "");
        header("location: " . $hlp->getMainUrl());
    }
    if (isset($_POST['changeNameWebsite'])) {
        $cf->addValueFormKeyConf($cf->getFilesConfig(), "website_name", $_POST['newWebsiteName']);
        header("Refresh:" . 0, $_SERVER['PHP_SELF']);
    }
    if (isset($_POST['chgLanguage'])) {
        $_SESSION['language'] = $_POST['chgLanguage'];
        $hlp->changeSuAccountLanguage($_POST['chgLanguage'], $_SESSION['suemail']);
        header("Refresh:" . 0, $_SERVER['PHP_SELF']);
    }

    for ($i = 0; $i < count($admins); $i++) {
        if (isset($_POST['deleteSuAccount-' . $admins[$i]['uid']])) {
            $hlp->deleteSuUserAccount($admins[$i]['email']);
            header("location: " . $hlp->getMainUrl() . "/KW");
        }
        if (isset($_POST['banSuAccount-' . $admins[$i]['uid']])) {
            $hlp->banSuUserAccount($admins[$i]['email']);
            header("location: " . $hlp->getMainUrl() . "/KW");
        }
        if (isset($_POST['unbanSuAccount-' . $admins[$i]['uid']])) {
            $hlp->unbanSuUserAccount($admins[$i]['email']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
        if (isset($_POST['restoreSuAccount-' . $admins[$i]['uid']])) {
            $hlp->restoreSuUserAccount($admins[$i]['email']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
        if (isset($_POST['disableSuAccount-' . $admins[$i]['uid']])) {
            $hlp->disableSuAccount($admins[$i]['uid']);
            header("location: " . $hlp->getMainUrl() . "/KW");
        }
        if (isset($_POST['enableSuAccount-' . $admins[$i]['uid']])) {
            $hlp->enableSuAccount($admins[$i]['uid']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
    }

    for ($i = 0; $i < count($users); $i++) {
        if (isset($_POST['deleteUser-' . $users[$i]['uid']])) {
            $hlp->deleteNoUserAccount($users[$i]['email']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
        if (isset($_POST['banUser-' . $users[$i]['uid']])) {
            $hlp->banNoUserAccount($users[$i]['email']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
        if (isset($_POST['unbanNoUser-' . $users[$i]['uid']])) {
            $hlp->unbanNoUserAccount($users[$i]['email']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
        if (isset($_POST['restoreNoUser-' . $users[$i]['uid']])) {
            $hlp->restoreNoUserAccount($users[$i]['email']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
    }

    for ($i = 0; $i < count($pages); $i++) {
        if (isset($_POST['deletePage-' . $pages[$i]['name']])) {
            $hlp->deletePage($pages[$i]['name']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
        if (isset($_POST['savePage-' . $pages[$i]['name']])) {
            if ($_POST['connectionType-' . $pages[$i]['name']] == 0 && ($pages[$i]['needConnect'] == 1 || $pages[$i]['needConnectSu'] == 1)) {
                $connect = $db->connect();
                $stm = $connect->prepare("UPDATE pages SET needConnect=0, needConnectSu=0 WHERE name=?");
                $stm->execute(array($pages[$i]['name']));
                $db->disconnect();
            } else if ($_POST['connectionType-' . $pages[$i]['name']] == 1 && $pages[$i]['needConnect'] == 0) {
                $connect = $db->connect();
                $stm = $connect->prepare("UPDATE pages SET needConnect=1, needConnectSu=0 WHERE name=?");
                $stm->execute(array($pages[$i]['name']));
                $db->disconnect();
            } else if ($_POST['connectionType-' . $pages[$i]['name']] == 2 && $pages[$i]['needConnectSu'] == 0) {
                $connect = $db->connect();
                $stm = $connect->prepare("UPDATE pages SET needConnect=0, needConnectSu=1 WHERE name=?");
                $stm->execute(array($pages[$i]['name']));
                $db->disconnect();
            }
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
    }
    for ($i = 0; $i < count($deletedPages); $i++) {
        if (isset($_POST['restorePage-' . $deletedPages[$i]['name']])) {
            $hlp->restorePage($deletedPages[$i]['name']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
        if (isset($_POST['finalDeletePage-' . $deletedPages[$i]['name']])) {
            $hlp->finalDeletePages($deletedPages[$i]['name'], $deletedPages[$i]['path'], $deletedPages[$i]['pathCss'], $deletedPages[$i]['pathJs']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
    }

    for ($i = 0; $i < count($tables); $i++) {
        if (isset($_POST['deleteTable-' . $tables[$i]['name']])) {
            $hlp->deleteTable($tables[$i]['name']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
    }

    for ($i = 0; $i < count($dTables); $i++) {
        if (isset($_POST['finalyDeleteTable-' . $dTables[$i]['name']])) {
            $hlp->finalDeleteTable($dTables[$i]['name']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
        if (isset($_POST['restoreTable-' . $dTables[$i]['name']])) {
            $hlp->restoreTable($dTables[$i]['name']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
    }

    for ($i = 0; $i < count($cookies); $i++) {
        if (isset($_POST['deleteCookie-' . $cookies[$i]['name']])) {
            $hlp->deleteCookie($cookies[$i]['name']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
        if (isset($_POST['restoreCookie-' . $cookies[$i]['name']])) {
            $hlp->restoreCookie($cookies[$i]['name']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
        if (isset($_POST['finalDeleteCookie-' . $cookies[$i]['name']])) {
            $hlp->finalDeleteCookie($cookies[$i]['name']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
    }

    if (isset($_POST['newTable'])) {
        $nameTable = $_POST['newTableName'];
        $nameArg = $_POST['tableFirst'];
        $nullable = " NOT NULL";
        $opts = "";
        $description = "";
        if (isset($_POST['newTableDesciption'])) {
            $description = $_POST['newTableDesciption'];
        }
        if (isset($_POST['nullableFirstTable'])) {
            $nullable = "";
        }

        if (isset($_POST['aiFirstTable'])) {
            $nullable .= " AUTO_INCREMENT";
        }

        if ($_POST['options'] != "") {
            $opts = $_POST['options'];
        }

        $editLine = $nameArg . " " . $_POST['tableFirstValue'] . $nullable . $opts;
        $hlp->addTable($nameTable, $editLine, $nameArg, $description);
        header("Refresh:" . 0, $_SERVER['PHP_SELF']);
    }

    if (isset($_POST['newCookie'])) {
        $resCheck = $hlp->addCookie($_POST['newCookieName'], $_POST['newCookieDescription'], $_POST['newTitlePage']);
        unset($_SESSION['cookiesError']);
        if ($resCheck == false)
            $_SESSION['cookiesError'] = "The cookie name is already taken";
        header("Refresh:" . 0, $_SERVER['PHP_SELF']);
    }

    if (isset($_POST['newAdminSubmit'])) {
        unset($_SESSION['addAdminError']);
        $errorsCreateAdmin = array(
            "This email is already taken in account",
            "You've create an account",
            "An error occured while creating the account"
        );
        $pseudoAdm = NULL;
        $lnameAdm = NULL;
        $fnameAdm = NULL;
        $accesNAdmin = 0;
        if ($_POST['accessNewAdmin'] <= 0) {
            $_SESSION['addAdminError'] = "Please select an access for the new administrator";
            header("location: " . $hlp->getMainUrl() . "/KW/manager");
        }
        if (isset($_POST['pseudoNewAdmin']))
            $pseudoAdm = $_POST['pseudoNewAdmin'];
        if (isset($_POST['lnameNewAdmin']))
            $lnameAdm = $_POST['lnameNewAdmin'];
        if (isset($_POST['fnameNewAdmin']))
            $fnameAdm = $_POST['fnameNewAdmin'];
        if ($_POST['pwdNewAdmin'] == $_POST['confNewAdminPwd']) {
            $resCheck = $hlp->createSuAccount($pseudoAdm, $_POST['emailNewAdmin'], $_POST['pwdNewAdmin'], $lnameAdm, $fnameAdm, $_POST['accessNewAdmin']);
            if ($resCheck != 1) {
                $_SESSION['addAdminError'] = $errorsCreateAdmin[$resCheck];
            }
        } else {
            $_SESSION['addAdminError'] = "Le mot de passe n'est pas le même que le mot de passe de confirmation";
        }
        header("Refresh:" . 0, $_SERVER['PHP_SELF']);
    }

    if (isset($_POST['saveCurrUrl'])) {
        $cf->addValueFormKeyConf($cf->getFilesConfig(), "main_url", $_POST['currUrl']);
        header("Refresh:" . 0, $_SERVER['PHP_SELF']);
    }

    $need_ids = false;
    $need_pseudo = false;
    if ($cf->strStartWith($cf->getValueFromKeyConf($cf->getFilesConfig(), "user-signin-ids"), "true"))
        $need_ids = true;
    if ($cf->strStartWith($cf->getValueFromKeyConf($cf->getFilesConfig(), "user-signin-pseudo"), "true"))
        $need_pseudo = true;
    $listAccess = $hlp->getAccessList();
    $extensionsList = $ext->getExtensionList();

    for ($i = 0; $i < count($extensionsList); $i++) {
        $extension = $extensionsList[$i];
        if (isset($_POST['extStop-' . $extension['folder']])) {
            $ext->stopExtension($extension['name']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
        if (isset($_POST['extStart-' . $extension['folder']])) {
            $ext->startExtension($extension['name']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
        if (isset($_POST['extDelete-' . $extension['folder']])) {
            $ext->removeExtensionFromUsingList($extension['name']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
    }

    $accessListing = $hlp->getAccessListing();
    if (isset($_POST['addNewAccess'])) {
        $strAccess = "";
        for ($i = 0; $i < count($accessListing); $i++) {
            if (isset($_POST['access-' . $i])) {
                if ($strAccess == "") {
                    $strAccess .= $accessListing[$i];
                } else {
                    $strAccess .= "," . $accessListing[$i];
                }
            }
        }
        $newAccessIntel = array("name" => $_POST['nameNewAccess'], "access" => $strAccess);
        $hlp->addNewAcces($newAccessIntel);
        header("Refresh:" . 0, $_SERVER['PHP_SELF']);
    }

    $ext->getPhpExtensionManager();
    $allRedirects = $hlp->getRedirectsList();
    if (isset($_POST['creaeteRedirect'])) {
        if ($hlp->urlIsRedirected($_POST['lastRedirect']) == true) {

        } else {
            $hlp->createRedirection($_POST['lastRedirect'], $_POST['newRedirect']);
        }
        header("Refresh:" . 0, $_SERVER['PHP_SELF']);
    }

    for ($i = 0; $i < count($allRedirects); $i++) {
        if (isset($_POST['deleteRedirect-' . $allRedirects[$i]['id']])) {
            $hlp->updateDeletedRedirect($allRedirects[$i]['id'], 1);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
        if (isset($_POST['restoreRedirect-' . $allRedirects[$i]['id']])) {
            $hlp->updateDeletedRedirect($allRedirects[$i]['id'], 0);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
        if (isset($_POST['reinitHistRedirect-' . $allRedirects[$i]['id']])) {
            $hlp->reinitRedirectHits($allRedirects[$i]['id']);
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
    }

    if (isset($_POST['addImageEdit'])) {

    }
?>

<!DOCTYPE html>
<html>
    <script>
        window.addEventListener("load", function () {
            const loader = this.document.getElementById("loaderPage");
            loader.classList.add("hidden");
        })
    </script>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        <script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
        <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
        <?php
            echo $ext->getCssAddedExtensionManager();
            echo $ext->getJsAddedExtensionManager();
        ?>
        <meta charset="utf-8">
        <title><?=$_SESSION['titlePage']?></title>
        <?php
			if (isset($_SESSION['cssPath'])) {
		?>
				<link href="<?=$_SESSION['cssPath']?>" rel="stylesheet">
		<?php
			}
            if (isset($_SESSION['jsPath'])) {
		?>
				<script type="text/javascript" src="<?=$_SESSION['jsPath']?>">initHeightSize();</script>
		<?php
			}
            if (isset($_SESSION['icoPage'])) {
		?>
            <link rel="icon" href="<?=$_SESSION['icoPage']?>">
        <?php
            }
        ?>
    </head>
    <body>
        <div id="loaderPage">
            <i class='bx bx-loader-circle bx-spin bx-lg'></i>
            <h2 class="cantSelectText">Loading...</h2>
        </div>
        <div class="optionsBar">
            <div class="optionsBarButtons">
                <img src="<?=$hlp->getMainUrl() . "/KW/kapweb_inits/ressources/imgs/" . $cf->getValueFromKeyConf($cf->getFilesConfig(), "img-website-icon")?>" style="height: 50px;width: auto;">
                <p class="cantSelectText"><?=$cf->getValueFromKeyConf($cf->getFilesConfig(), "website_name")?></p>
            </div>
            <div class="optionsBarConnect">
                <?php
                    if ($hlp->haveAccesTo("refreshMedias")) {
                ?>
                <button class="buttonRefreshMedias" title="<?=$hlp->getLangWorldMainFile("refreshMedias")?>" onclick="hideShowRefreshMedias()">
                    <i class='bx bxs-bolt bx-sm' style='color:#ffffff'></i>
                </button>
                <?php
                    }
                ?>
                <div class="connectIntels">
                    <button class="btnAccount" onclick="hideShowSoftwareIntels()">
                        <i class='bx bxs-user bx-sm' style='color:#ffffff;position: relative;transform: translateY(20%);'></i>
                        <p><?=$accountIntels['pseudo']?></p>
                    </button>
                </div>
                <form method="POST">
                    <button type="submit" name="disconnect" class="logoutButton" title="<?=$hlp->getLangWorldMainFile("Logout")?>">
                        <i class='bx bxs-log-out'></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="content" id="maincontent">
            <div class="navMenu">
                <h3>Menu de navigation</h3>
                <?php
                    if ($hlp->haveAccesTo("Dashboard")) {
                ?>
                    <button id="btnDashboard" class="btnNavMenu" onclick="displayContextMenu('dashboardContext', 'btnDashboard')"><i class='bx bxs-dashboard' style="background-color: #0a0080;padding: 5px;border-radius: 5px"></i><?=" " . $hlp->getLangWorldMainFile("dashboard")?></button>
                <?php
                    }
                ?>
                <?php
                    $accesAdmin = $hlp->haveAccesTo("Administrors");
                    $accesUsers = $hlp->haveAccesTo("Users");
                    if ($hlp->haveExtensionAccesFromMainClass("navMenuAdmin") || $accesAdmin || $accesUsers) {
                ?>
                    <button class="btnNavMenu" id="navMenu0" onclick="displayNavMenu('navMenuAdmin', 'iconAdmin', 'navMenu0')"><i class='bx bx-rocket'></i><?=" " . $hlp->getLangWorldMainFile("adminMain")?><i id="iconAdmin" class="bx bx-down-arrow iconDirectory"></i></button>
                <?php
                    }
                ?>
                <div class="closeMenuNav" id="navMenuAdmin">
                    <?php
                        if ($accesAdmin) {
                    ?>
                        <button class="btnNavMenu" id="btnAdmin" onclick="displayContextMenu('administratorsContext', 'btnAdmin')"><i class='bx bxs-user-badge' style="background-color: green;padding: 5px;border-radius: 5px;"></i><?=" " . $hlp->getLangWorldMainFile("admin1")?></button>    
                    <?php
                        }
                        if ($hlp->haveAccesTo("Users")) {
                    ?>
                        <button class="btnNavMenu" id="btnUsers" onclick="displayContextMenu('usersContext', 'btnUsers')"><i class="bx bx-user" style="background-color: red;padding: 5px;border-radius: 5px;"></i><?=" " . $hlp->getLangWorldMainFile("admin2")?></button>
                    <?php
                        }
                        echo $ext->getButtonFromCat("navMenuAdmin");
                    ?>
                </div>
                <?php
                    $accessPage = $hlp->haveAccesTo("Pages");
                    $accesDeletedPage = $hlp->haveAccesTo("Deleted Pages");
                    $accesRedirect = $hlp->haveAccesTo("Redirects");
                    if ($accessPage || $accesDeletedPage || $hlp->haveExtensionAccesFromMainClass("navMenuWebsite")) {
                ?>
                <button class="btnNavMenu" id="navMenu1" onclick="displayNavMenu('navMenuWebsite', 'iconWebsite', 'navMenu1')"><i class='bx bx-world' ></i><?=" " . $hlp->getLangWorldMainFile("webSiteMain")?><i id="iconWebsite" class="bx bx-down-arrow iconDirectory"></i></button>
                <?php
                    }
                ?>
                <div class="closeMenuNav" id="navMenuWebsite">
                    <?php
                        if ($accessPage) {
                    ?>
                        <button class="btnNavMenu" id="btnPages" onclick="displayContextMenu('pagesContext', 'btnPages')"><i id="iconWebsite" class="bx bx-world" style="background-color: #bd5f0c;padding: 5px;border-radius: 5px;"></i><?=" " . $hlp->getLangWorldMainFile("pages")?></button>
                    <?php
                        }
                        if ($accesDeletedPage) {
                    ?>
                        <button class="btnNavMenu" id="btnDeletedPages" onclick="displayContextMenu('deletedPagesContext', 'btnDeletedPages')"><i id="iconWebsite" class="bx bx-trash" style="padding: 5px;border-radius: 5px;background-color: #08839f;"></i><?=" " . $hlp->getLangWorldMainFile("delPages")?></button>
                    <?php
                        }
                        if ($accesRedirect) {
                    ?>
                        <button class="btnNavMenu" id="btnRedirects" onclick="displayContextMenu('redirectsManager', 'btnRedirects')"><i class='bx bx-git-merge' style='color:#ffffff;padding: 5px;border-radius: 5px;background-color: #08839f;'></i><?=" " . $hlp->getLangWorldMainFile("redirectsBtnNavMenu")?></button>
                    <?php
                        }
                        echo $ext->getButtonFromCat("navMenuWebsite");
                    ?>
                </div>
                <?php
                    $accessDb = $hlp->haveAccesTo("Database");
                    $accessDeletedDB = $hlp->haveAccesTo("Deleted Database");
                    $accessMedias = $hlp->haveAccesTo("Medias");
                    if ($accessMedias || $accessDb || $accessDeletedDB || $hlp->haveExtensionAccesFromMainClass("navMenuFiles")) {
                ?>
                <button class="btnNavMenu" id="navMenu2" onclick="displayNavMenu('navMenuFiles', 'iconFiles', 'navMenu2')"><i class='bx bx-image-alt' ></i><?=" " . $hlp->getLangWorldMainFile("filesMain")?><i id="iconFiles" class="bx bx-down-arrow iconDirectory"></i></button>
                <?php
                    }
                ?>
                <div class="closeMenuNav" id="navMenuFiles">
                    <?php
                        if ($accessMedias) {
                    ?>
                        <button id="btnImages"class="btnNavMenu" onclick="displayContextMenu('imagesContext', 'btnImages')"><i class="bx bx-image-alt" style="padding: 5px;border-radius: 5px;background-color: #8d11a7;"></i><?=" " . $hlp->getLangWorldMainFile("w-medias")?></button>
                    <?php
                        }
                        if ($accessDb) {
                    ?>
                        <button  id="btnDb"class="btnNavMenu" onclick="displayContextMenu('dbContext', 'btnDb')"><i class="bx bx-data" style="padding: 5px;border-radius: 5px;background-color: #b02379;"></i><?=" " . $hlp->getLangWorldMainFile("database")?></button>
                    <?php
                        }
                        if ($accessDeletedDB) {
                    ?>
                        <button  id="btnDeletedDb"class="btnNavMenu" onclick="displayContextMenu('deletedTables', 'btnDeletedDb')"><i class="bx bx-trash" style="padding: 5px;border-radius: 5px;background-color: #9d1313;"></i><?=" " . $hlp->getLangWorldMainFile("delDatabase")?></button>
                    <?php
                        }
                    ?>
                    <?php
                        echo $ext->getButtonFromCat("navMenuFiles");
                    ?>
                </div>
                <?php
                    $accesAccess = $hlp->haveAccesTo("Access");
                    $accessCookies = $hlp->haveAccesTo("Cookies");
                    $accessExt = $hlp->haveAccesTo("Extensions");

                    if ($accessExt || $accessCookies || $accesAccess || $hlp->haveExtensionAccesFromMainClass("navMenuSystem")) {
                ?>
                    <button class="btnNavMenu" id="navMenu3" onclick="displayNavMenu('navMenuSystem', 'iconSystem', 'navMenu3')"><i class='bx bx-microchip'></i><?=" " . $hlp->getLangWorldMainFile("w-system")?><i id="iconSystem" class="bx bx-down-arrow iconDirectory"></i></button>
                <?php
                    }
                ?>
                <div class="closeMenuNav" id="navMenuSystem">
                    <?php
                        if ($accesAccess) {
                    ?>
                        <button class="btnNavMenu" id="btnAccess" onclick="displayContextMenu('access', 'btnAccess')"><i class="bx bx-user" style="background-color: blue;padding: 5px;border-radius: 5px;"></i><?=" " . $hlp->getLangWorldMainFile("admin3")?></button>
                    <?php
                        } if ($accessExt) {
                    ?>
                        <button  id="btnExtensionList"class="btnNavMenu" onclick="displayContextMenu('extensionListing', 'btnExtensionList')"><i class="bx bx-git-branch" style="padding: 5px;border-radius: 5px;background-color: #477b2a;"></i><?=" " . $hlp->getLangWorldMainFile("extensions")?></button>
                    <?php
                        } if ($accessCookies) {
                    ?>
                        <button  id="btnCookies"class="btnNavMenu" onclick="displayContextMenu('cookieContext', 'btnCookies')"><i class="bx bx-cookie" style="padding: 5px;border-radius: 5px;background-color: #b07423;"></i><?=" " . $hlp->getLangWorldMainFile("cookies")?></button>
                    <?php
                        }
                    ?>
                </div>
                <?php
                    echo $ext->otherButtonAcces();
                ?>
                <form class="deconnectForm" method="POST">
                    <input type="submit" value="disconnect" name="disconnect">
                </form>
            </div>
            <div class="contextMenu">
                <div class="contextDev" id="dashboardContext">
                    <h1 class="titleContextDev">Dashboard</h1>
                    <table class="tableDashBoard">
                        <thead>
                            <tr>
                                <th class="urlTd">Theoric url</th>
                                <th class="urlTd">Website name</th>
                                <th class="actionsTd">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="urlTd">
                                    <input id="currUrl" value="<?=$hlp->getMainUrl()?>" readonly>
                                    <button id="modifCurrUrl" onclick="displayEditCurrUrl('modifCurrUrl', 'currUrl', 'currUrlEdit', 'saveCurrUrl')">Modify Value</button>
                                    <form method="POST" class="modifUrlForm">
                                        <input id="currUrlEdit" type="text" value="<?=$hlp->getMainUrl()?>" placeholder="current main url..." name="currUrl">
                                        <input id="saveCurrUrl" type="submit" value="Sauvegarder" name="saveCurrUrl">
                                    </form>
                                </td>
                                <td class="urlTd">
                                    <form method="POST">
                                        <input type="text" name="newWebsiteName" placeholder="Website name..." value="<?=$cf->getValueFromKeyConf($cf->getFilesConfig(), "website_name")?>">
                                        <input type="submit" name="changeNameWebsite" value="Sauvegarder">
                                    </form>
                                </td>
                                <td class="actionsTd">
                                    <form method="POST" class="modifUrlForm">
                                        <input type="submit" value="Supprimer toues les pages" name="deleteAllPages">
                                        <input type="submit" value="Remettre le KW à zéro" name="toOKw">
                                        <input type="submit" value="Tout réinitialiser" name="reinitAll">
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                        if (count($myAccount) > 0) {
                    ?>
                        <h3>Votre compte :</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Status</th>
                                    <th>Mot de passe</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?=$myAccount['email']?></td>
                                    <td><?=$myAccount['lname']?></td>
                                    <td><?=$myAccount['fname']?></td>
                                    <td><?=$myAccount['status']?></td>
                                    <td><?="******************"?></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><?=" "?></td>
                                    <td><?=" "?></td>
                                    <td><?=" "?></td>
                                    <td><?=" "?></td>
                                    <td><?=" "?></td>
                                    <td><?=" "?></td>
                                </tr>
                                <tr>
                                    <form>
                                        <td>
                                            <input type="email" value="<?=$myAccount['email']?>" placeholder="Email...">
                                        </td>
                                        <td>
                                            <input type="text" placeholder="Last name..." value="<?=$myAccount['lname']?>">
                                        </td>
                                        <td>
                                            <input type="text" placeholder="First name..." value="<?=$myAccount['fname']?>">
                                        </td>
                                        <td></td>
                                        <td>
                                            <input type="password" placeholder="Current password...">
                                            <input type="password" placeholder="New password...">
                                            <input type="password" placeholder="Confirm new password...">
                                        </td>
                                        <td>
                                            <input type="submit" value="save">
                                            <input type="submit" value="delete your account">
                                        </td>
                                    </form>
                                </tr>
                            </tbody>
                        </table>
                    <?php
                        } else {
                    ?>
                        <h3>Erreur lors du chargement de votre compte</h3>
                    <?php
                        }
                    ?>
                </div>
                <div class="contextDev" id="administratorsContext">
                    <h1 class="titleContextDev">Administrateurs</h1>
                    <form method="POST">
                        <table class="tableAdminListing">
                            <thead>
                                <tr>
                                    <th class="icons_adminslist"></th>
                                    <th class="nameFname_adminslist"><?=$hlp->getLangWorldMainFile("adminUsersTable-listing")?></th>
                                    <th class="lastLogin_adminList"><?=$hlp->getLangWorldMainFile("lastLoginTable-adminsListing")?></th>
                                    <th class="actions_adminsList"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    for ($i = 0; $i < count($admins); $i++) {
                                        $date = date_create();
                                        $lst_con = date_timestamp_set($date, $admins[$i]['ls_con'])->format("d/m/Y");
                                ?>
                                    <tr>
                                        <th class="icons_adminslist">
                                            <i class='bx bx-user bx-md' undefined ></i>
                                            <?php
                                                if ($admins[$i]['deleted'] == 1) {
                                            ?>
                                                <i title="<?=$hlp->getLangWorldMainFile("w-deleted")?>" class='bx bxs-error-circle bx-sm' style='color:#ff0000;position: absolute;transform: translate(-105%, 50%);'></i>
                                            <?php
                                                } else if ($admins[$i]['baned'] == 1) {
                                            ?>
                                                <i title="<?=$hlp->getLangWorldMainFile("w-baned")?>" class='bx bxs-error bx-sm' style='color:#ff8000;position: absolute;transform: translate(-105%, 50%);'></i>
                                            <?php
                                                }
                                            ?>
                                        </th>
                                        <th class="nameFname_adminslist"><?=$admins[$i]['fname'] . " " . $admins[$i]['lname']?></th>
                                        <th class="lastLogin_adminList"><?=$lst_con?></th>
                                        <th>
                                            <div class="btn-block">
                                                <button title="<?=$hlp->getLangWorldMainFile("adminListingTitle-edit")?>" name="<?="editSuAccount-" . $admins[$i]['uid']?>"><i class='bx bx-edit-alt bx-md'></i></button>
                                                <?php
                                                    if ($admins[$i]['status'] == 0) {
                                                ?>
                                                    <button class="cantUseBtn" disabled><i class='bx bx-message-square bx-md' style='color:rgba(0,0,0,0)'  ></i></button>
                                                <?php
                                                    } else if ($admins[$i]['status'] == 1) {
                                                ?>
                                                    <button title="<?=$hlp->getLangWorldMainFile("adminListingTitle-disable")?>" name="<?="disableSuAccount-" . $admins[$i]['uid']?>"><i class='bx bxs-toggle-right bx-md'></i></button>
                                                <?php
                                                    } else {
                                                ?>
                                                    <button title="<?=$hlp->getLangWorldMainFile("adminListingTitle-enable")?>" name="<?="enableSuAccount-" . $admins[$i]['uid']?>"><i class='bx bx-toggle-left bx-md'></i></button>
                                                <?php
                                                    }
                                                ?>
                                                <?php
                                                    if ($admins[$i]['deleted'] == 1) {
                                                ?>
                                                    <button class="cantUseBtn" disabled><i class='bx bx-message-square' style='color:rgba(0,0,0,0)'  ></i></button>
                                                <?php
                                                    } else {
                                                ?>
                                                    <button title="<?=$hlp->getLangWorldMainFile("adminListingTitle-delete")?>" name="<?="deleteSuAccount-" . $admins[$i]['uid']?>"><i class='bx bx-trash bx-md'></i></button>
                                                <?php
                                                    }
                                                ?>
                                                <?php
                                                    if ($admins[$i]['baned'] == 1) {
                                                ?>
                                                    <button class="cantUseBtn" disabled><i class='bx bx-message-square' style='color:rgba(0,0,0,0)'  ></i></button>
                                                <?php
                                                    } else {
                                                ?>
                                                    <button title="<?=$hlp->getLangWorldMainFile("adminListingTitle-ban")?>" name="<?="banSuAccount-" . $admins[$i]['uid']?>"><i class='bx bxs-no-entry bx-md'></i></button>
                                                <?php
                                                    }
                                                ?>
                                                
                                            </div>
                                            <div class="btn-block">
                                                <button title="<?=$hlp->getLangWorldMainFile("adminListingTitle-details")?>" name="<?="editSuAccount-" . $admins[$i]['uid']?>"><i class='bx bx-detail bx-md'></i></button>
                                                <button title="<?=$hlp->getLangWorldMainFile("adminListingTitle-information")?>" name="<?="editSuAccount-" . $admins[$i]['uid']?>"><i class='bx bx-info-circle bx-md'></i></button>
                                            </div>
                                            <div class="btn-block">
                                                <?php
                                                    if ($admins[$i]['deleted'] == 1) {
                                                ?>
                                                    <button title="<?=$hlp->getLangWorldMainFile("adminListingTitle-restore")?>" name="<?="restoreSuAccount-" . $admins[$i]['uid']?>"><i class='bx bx-copy-alt bx-md'></i></button>
                                                <?php
                                                    } else {
                                                ?>
                                                    <button class="cantUseBtn" disabled><i class='bx bx-message-square' style='color:rgba(0,0,0,0)'  ></i></button>
                                                <?php
                                                    }
                                                ?>
                                                <?php
                                                    if ($admins[$i]['baned'] == 1) {
                                                ?>
                                                    <button title="<?=$hlp->getLangWorldMainFile("adminListingTitle-unban")?>" name="<?="unbanSuAccount-" . $admins[$i]['uid']?>"><i class='bx bxs-lock-open bx-md'></i></button>
                                                <?php
                                                    } else {
                                                ?>
                                                    <button class="cantUseBtn" disabled><i class='bx bx-message-square' style='color:rgba(0,0,0,0)'  ></i></button>
                                                <?php
                                                    }
                                                ?>
                                            </div>
                                        </th>
                                    </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </form>
                    <form class="formCreatePage" method="POST">
                        <picture>
                            <img src="<?=$hlp->getMainUrl() . "/" . $cf->getValueFromKeyConf($cf->getFilesConfig(), "main_icon_png")?>">
                        </picture>
                        <h3>Créer un nouveau compte admin</h3>
                        <div class="whiteDiv">
                            <input type="text" placeholder="Pseudo..." name="pseudoNewAdmin" required>
                            <input type="email" placeholder="Email..." name="emailNewAdmin" required>
                            <input type="text" placeholder="Nom..." name="lnameNewAdmin" required>
                            <input type="text" placeholder="Prénom..." name="fnameNewAdmin" required>
                            <input type="password" placeholder="Mot de passe.." name="pwdNewAdmin" required>
                            <input type="password" placeholder="Confirmez mot de passe..." name="confNewAdminPwd" required>

                            <select name="accessNewAdmin">
                                <option value="0" hidden>Select administrator access</option>
                                <?php
                                    for ($i = 0; $i < count($listAccess); $i++) {
                                ?>
                                    <option value="<?=$i+1?>"><?=$listAccess[$i]['name']?></option>
                                <?php
                                    }
                                ?>
                            </select>
                            <input type="submit" value="créer" name="newAdminSubmit">
                            <?php
                                if (isset($_SESSION['addAdminError'])) {
                            ?>
                                <p class="errorMsg" style="background-color: rgba(255, 0, 0, 0.514);"><?=$_SESSION['addAdminError']?></p>
                            <?php
                                }
                            ?>
                        </div>
                        <p>Vous pouvez créer d'autres comptes administrateur en remplissant ce formulaire</p>
                    </form>
                </div>
                <div class="contextDev" id="usersContext">
                    <h1 class="titleContextDev">Utilisateurs</h1>
                    <form method="POST">
                        <table class="tablePages">
                            <thead>
                                <tr>
                                    <th>Pseudo</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    for ($i = 0; $i < count($users); $i++) {
                                        if ($users[$i]['baned'] == 0 && $users[$i]['deleted'] == 0) {
                                ?>
                                    <tr>
                                        <th><?=$users[$i]['pseudo']?></th>
                                        <th><?=$users[$i]['lname']?></th>
                                        <th><?=$users[$i]['fname']?></th>
                                        <th><?=$users[$i]['email']?></th>
                                        <th>
                                            <input class="deleteTable" type="submit" value="Delete" name="<?="deleteUser-" . $users[$i]['uid']?>">
                                            <input class="deleteTable" type="submit" value="Ban" name="<?="banUser-" . $users[$i]['uid']?>">
                                        </th>
                                    </tr>
                                <?php
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                        <h3>Utilisateurs ban OU supprimés</h3>
                        <table class="tablePages">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Pseudo</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    for ($i = 0; $i < count($users); $i++) {
                                        if ($users[$i]['baned'] == 1 || $users[$i]['deleted'] == 1) {
                                            $status = "";
                                            $color = "red";
                                            if ($users[$i]['baned'] == 1) {
                                                $status = "Baned";
                                            }
                                            if ($users[$i]['deleted'] == 1) {
                                                if ($status == "") {
                                                    $status = "deleted";
                                                    $color = "orange";
                                                } else {
                                                    $status .= ", deleted";
                                                    $color = "purple";
                                                }
                                            }
                                ?>
                                    <tr>
                                        <th><?=$users[$i]['email']?></th>
                                        <th><?=$users[$i]['pseudo']?></th>
                                        <th><?=$users[$i]['lname']?></th>
                                        <th><?=$users[$i]['fname']?></th>
                                        <th style="<?="color: " . $color . ";"?>"><?=$status?></th>
                                        <th>
                                            <?php
                                                if ($users[$i]['deleted'] == 1) {
                                            ?>
                                            <input class="deleteTable" type="submit" value="Restore" name="<?="restoreNoUser-" . $users[$i]['uid']?>">
                                            <?php
                                                }
                                                if ($users[$i]['baned'] == 1) {
                                            ?>
                                            <input class="deleteTable" type="submit" value="Unban" name="<?="unbanNoUser-" . $users[$i]['uid']?>">
                                            <?php
                                                }
                                            ?>
                                        </th>
                                    </tr>
                                <?php
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="contextDev" id="pagesContext">
                    <h1 class="titleContextDev">Pages</h1>
                    <?php
                        if (count($pages) > 0) {
                    ?>
                    <form method="POST">
                    <table class="tablePages">
                        <thead>
                            <tr>
                                <th class="nameTablePage">Name</th>
                                <th class="urlTablePage">Url</th>
                                <th class="titleTablePage">Title</th>
                                <th class="mainpageTablePage">Language</th>
                                <th class="iconTablePage">icon</th>
                                <th class="deleteTablePage">delete</th>
                                <th class="connectTablePage">Connection type</th>
                                <th class="editTablePage">Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php
                            for ($i = 0; $i < count($pages); $i++) {
                                $urlPageEdit = $hlp->getMainUrl() . "/KW/editPage/" . $pages[$i]['name'];
                                $lang = $hlp->getLanguageFromId($pages[$i]['language']);
                    ?>
                        <tr>
                            <th class="nameTablePage"><?=$pages[$i]['name']?></th>
                            <th class="urlTablePage"><?=$hlp->getMainUrl() . $pages[$i]['url']?></th>
                            <th class="titleTablePage"><?=$pages[$i]['title']?></th>
                            <th class="mainpageTablePage"><?=$lang?></th>
                            <th class="iconTablePage"><img src="<?=$hlp->getMainUrl() . $pages[$i]['ico']?>"></th>
                            <th class="deleteTablePage"><input type="submit" value="deleted" name="<?="deletePage-" . $pages[$i]['name']?>"></th>
                            <th class="connectTablePage">
                                <select name="<?="connectionType-" . $pages[$i]['name']?>">
                                    <?php
                                        if ($pages[$i]['needConnect'] == 0 && $pages[$i]['needConnectSu'] == 0) {
                                    ?>
                                        <option value="0" selected>No connection need (currently use)</option>
                                    <?php
                                        } else {
                                    ?>
                                        <option value="0">No connection need</option>
                                    <?php
                                        }
                                    ?>
                                    <?php
                                        if ($pages[$i]['needConnect'] == 1) {
                                    ?>
                                        <option value="1" selected>Need user connection (currently use)</option>
                                    <?php
                                        } else {
                                    ?>
                                        <option value="1">Need user connection</option>
                                    <?php
                                        }
                                    ?>
                                    <?php
                                        if ($pages[$i]['needConnectSu'] == 1) {
                                    ?>
                                        <option value="2" selected>Need admin connection (currently use)</option>
                                    <?php
                                        } else {
                                    ?>
                                        <option value="2">Need admin connection</option>
                                    <?php
                                        }
                                    ?>
                                </select>
                                <input type="submit" value="Save" name="<?="savePage-" . $pages[$i]['name']?>">
                            </th>
                            <th class="editTablePage"><a class="editPageUrl" href="<?=$urlPageEdit?>"><div class="dEditPageLink"><i class="bx bx-edit-alt"></i></div></a></th>
                        </tr>
                    <?php
                            }
                    ?>
                        </tbody>
                    </table>
                    </form>
                    <form class="formCreatePage" method="POST">
                        <picture>
                            <img src="<?=$hlp->getMainUrl() . "/" . $cf->getValueFromKeyConf($cf->getFilesConfig(), "main_icon_png")?>">
                        </picture>
                        <h3>Créer une nouvelle page</h3>
                        <div class="whiteDiv">
                            <input type="text" placeholder="page name..." name="newPageName" required>
                            <input type="text" placeholder="url..." name="newPageUrl" required>
                            <input type="text" placeholder="title..." name="newTitlePage" required>
                            <select name="selectLangPageCreation" required>
                                <option value="1" hidden>Selctionner la langue</option>
                                <?php
                                    for ($i = 0; $i < count($languages); $i++) {
                                ?>
                                    <option value="<?=$languages[$i]['id']?>"><?=$languages[$i]['name_en']?></option>
                                <?php
                                    }
                                ?>
                            </select>
                            <input type="submit" value="créer" name="newPage">
                            <?php
                                if (isset($_SESSION['pageError'])) {
                            ?>
                                <p class="errorMsg" style="background-color: rgba(255, 0, 0, 0.514);"><?=$_SESSION['pageError']?></p>
                            <?php
                                }
                            ?>
                        </div>
                        <p>Vous pouvez créer d'autres pages internet en remplissant ce formulaire</p>
                    </form>
                    <?php
                        } else {
                    ?>
                        <form class="formCreatePage" method="POST">
                            <picture>
                                <img src="<?=$hlp->getMainUrl() . "/" . $cf->getValueFromKeyConf($cf->getFilesConfig(), "main_icon_png")?>">
                            </picture>
                            <h3><?=$hlp->getLangWorldMainFile("createfirstPage-title")?></h3>
                            <div class="whiteDiv">
                                <input type="text" placeholder="<?=$hlp->getLangWorldMainFile("namePage-placeholder")?>" name="nameFirstPage" required>
                                <input type="text" placeholder="<?=$hlp->getLangWorldMainFile("titlePage-placeholder")?>" name="titleFirstPage" required>
                                <select name="selectLangPageFirst" required>
                                    <option value="1" hidden><?=$hlp->getLangWorldMainFile("selectLang-selectTitle")?></option>
                                    <?php
                                        for ($i = 0; $i < count($languages); $i++) {
                                    ?>
                                        <option value="<?=$languages[$i]['id']?>"><?=$languages[$i]['name_en']?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                                <input type="submit" value="<?=$hlp->getLangWorldMainFile("w-create")?>" name="createFirstPage">
                                <?php
                                    if (isset($_SESSION['pageError'])) {
                                ?>
                                    <p class="errorMsg" style="background-color: rgba(255, 0, 0, 0.514);"><?=$_SESSION['pageError']?></p>
                                <?php
                                    }
                                ?>
                            </div>
                            <p><?=$hlp->getLangWorldMainFile("sentenceFooter-createFPage")?></p>
                        </form>
                    <?php
                        }
                    ?>
                </div>
                <div class="contextDev" id="deletedPagesContext">
                    <h1>Deleted Pages</h1>
                    <form method="POST">
                        <table class="tableDeletedPages">
                            <thead>
                                <tr>
                                    <th class="nameTablePage">Name</th>
                                    <th class="urlTablePage">Url</th>
                                    <th>Edition</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    for ($i = 0; $i < count($deletedPages); $i++) {
                                ?>
                                    <tr>
                                        <th class="nameTablePage"><?=$deletedPages[$i]['name']?></th>
                                        <th class="urlTablePage"><?=$deletedPages[$i]['url']?></th>
                                        <th>
                                            <input class="restorePage" type="submit" value="Restore" name="<?="restorePage-" . $deletedPages[$i]['name']?>">
                                            <input class="deleteFinalPage" type="submit" value="Delete" name="<?="finalDeletePage-" . $deletedPages[$i]['name']?>">
                                        </th>
                                    </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="contextDev" id="cookieContext">
                    <h1 class="titleContextDev">Cookies</h1>
                    <form method="POST">
                        <table class="tableDeletedPages">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Lifetime (second)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    for($i = 0; $i < count($cookies); $i++) {
                                        if ($cookies[$i]['deleted'] == 0) {
                                ?>
                                    <tr>
                                        <th><?=$cookies[$i]['name']?></th>
                                        <th><textarea><?=$cookies[$i]['description']?></textarea></th>
                                        <th><?=$cookies[$i]['lifetime']?></th>
                                        <th>
                                            <input type="submit" value="Deleted" name="<?="deleteCookie-" . $cookies[$i]['name']?>">
                                        </th>
                                    </tr>
                                <?php
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                        <h3>Cookies Deleted</h3>
                        <table class="tableDeletedPages">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Lifetime (second)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    for($i = 0; $i < count($cookies); $i++) {
                                        if ($cookies[$i]['deleted'] == 0) {
                                ?>
                                    <tr>
                                        <th><?=$cookies[$i]['name']?></th>
                                        <th><textarea><?=$cookies[$i]['description']?></textarea></th>
                                        <th><?=$cookies[$i]['lifetime']?></th>
                                        <th>
                                            <input type="submit" value="Restore" name="<?="restoreCookie-" . $cookies[$i]['name']?>">
                                            <input type="submit" value="Delete" name="<?="finalDeleteCookie-" . $cookies[$i]['name']?>">
                                        </th>
                                    </tr>
                                <?php
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </form>
                    <form class="formCreatePage" method="POST">
                        <picture>
                            <img src="<?=$hlp->getMainUrl() . "/" . $cf->getValueFromKeyConf($cf->getFilesConfig(), "main_icon_png")?>">
                        </picture>
                        <h3>Créer un nouveau cookie</h3>
                        <div class="whiteDiv">
                            <input type="text" placeholder="Cookie name..." name="newCookieName" required>
                            <input type="text" placeholder="Description..." name="newCookieDescription" required>
                            <input type="number" placeholder="lifetime..." name="newTitlePage" min="1" required>
                            <input type="submit" value="créer" name="newCookie">
                            <?php
                                if (isset($_SESSION['cookiesError'])) {
                            ?>
                                <p class="errorMsg" style="background-color: rgba(255, 0, 0, 0.514);"><?=$_SESSION['cookiesError']?></p>
                            <?php
                                }
                            ?>
                        </div>
                        <p>Vous pouvez créer d'autres cookies en remplissant ce formulaire</p>
                    </form>
                </div>
                <div class="contextDev" id="dbContext">
                    <h1 class="titleContextDev">Base de donnée</h1>
                    <form method="POST">
                        <table class="tableTables">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Nb Args</th>
                                    <th>Args</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    for ($i = 0; $i < count($tables); $i++) {
                                        $args_table = explode(",", $tables[$i]['args']);
                                ?>
                                    <tr>
                                        <th><?=$tables[$i]['name']?></th>
                                        <th><?=$tables[$i]['rows']?></th>
                                        <th>
                                            <select class="argsTable">
                                                <?php
                                                    for ($x = 0; $x < count($args_table); $x++) {
                                                ?>
                                                    <option><?=$args_table[$x]?></option>
                                                <?php
                                                    }
                                                ?>
                                            </select>    
                                        </th>
                                        <th><textarea class="descriptionTable" readonly><?=$tables[$i]['description']?></textarea></th>
                                        <th>
                                            <?php
                                                $currPropose = 0;
                                                if ($tables[$i]['deletable'] == 1) {
                                                    $currPropose++;
                                            ?>
                                                <input class="deleteTable" type="submit" value="supprimer" name="<?="deleteTable-" . $tables[$i]['name']?>">
                                            <?php
                                                }
                                                if ($tables[$i]['editable_structure']) {
                                                    $currPropose++;
                                            ?>
                                                <a class="editPageUrl" href="<?=$hlp->getMainUrl() . "/KW/editStructureTable/" . $tables[$i]['name']?>"><div class="dEditPageLink"><p>Edit the structure</p></div></a>
                                            <?php
                                                }
                                                if ($tables[$i]['editable_content']) {
                                                    $currPropose++;
                                            ?>
                                                <a class="editPageUrl" href="<?=$hlp->getMainUrl() . "/KW/editContentTable/" . $tables[$i]['name']?>"><div class="dEditPageLink"><p>Edit the content</p></div></a>
                                            <?php
                                                }
                                                if ($currPropose == 0) {
                                            ?>
                                                <p>No actions possible</p>
                                            <?php
                                                }
                                            ?>
                                        </th>
                                    </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </form>
                    <form class="formCreatePage" method="POST">
                        <picture>
                            <img src="<?=$hlp->getMainUrl() . "/" . $cf->getValueFromKeyConf($cf->getFilesConfig(), "main_icon_png")?>">
                        </picture>
                        <h3>Nouvelle table</h3>
                        <div class="whiteDiv">
                            <input type="text" placeholder="table name..." name="newTableName" required>
                            <input type="text" placeholder="first args name..." name="tableFirst" required>
                            <label>Type of value</label>
                            <select class="selectTypeArg" name="tableFirstValue" required>
                                <option value="int(11)">INT</option>
                                <option value="varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci">VARCHAR</option>
                                <option value="tinyint(1)">BOOLEAN</option>
                                <option value="text CHARACTER SET utf8 COLLATE utf8_general_ci">TEXT</option>
                                <option value="float">FLOAT</option>
                                <option value="date">DATE</option>
                                <option value="timestamp">TIMESTAMP</option>
                            </select>
                            <label>Can Be Null</label>
                            <input class="checkCanBeNullTable" type="checkbox" name="nullableFirstTable">
                            <label>Auto Increment</label>
                            <input class="checkCanBeNullTable" type="checkbox" name="aiFirstTable">
                            <label>Other things</label>
                            <select class="selectTypeArg" name="options">
                                <option value=""></option>
                                <option value=" PRIMARY KEY">Primary</option>
                                <option value=" UNIQUE">Unique</option>
                                <option value=" INDEX">Index</option>
                            </select>
                            <input type="text" placeholder="description de la table..." name="newTableDesciption">
                            <input type="submit" value="Create table" name="newTable">
                        </div>
                        <p>Vous pouvez créer d'autres tables en remplissant ce formulaire</p>
                    </form>
                </div>
                <div class="contextDev" id="deletedTables">
                    <h1 class="titleContextDev">Base de donnée supprimées</h1>
                    <form method="POST">
                        <table class="tablePages">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Nb args</th>
                                    <th>Args</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    for ($i = 0; $i < count($dTables); $i++) {
                                        $args_table = explode(",", $dTables[$i]['args']);
                                ?>
                                    <tr>
                                        <th><?=$dTables[$i]['name']?></th>
                                        <th><?=$dTables[$i]['rows']?></th>
                                        <th>
                                            <select class="argsTable">
                                                <?php
                                                    for ($x = 0; $x < count($args_table); $x++) {
                                                ?>
                                                    <option><?=$args_table[$x]?></option>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                        </th>
                                        <th>
                                            <input type="submit" name="<?="restoreTable-" . $dTables[$i]['name']?>" value="Restore">
                                            <input type="submit" name="<?="finalyDeleteTable-" . $dTables[$i]['name']?>" value="Delete">
                                        </th>
                                    </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="contextDev" id="access">
                    <table class="tablePages">
                        <caption><h1>All access types</h1></caption>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Tables acces</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                for ($i = 0; $i < count($listAccess); $i++) {
                            ?>
                                <tr>
                                    <td><?=$listAccess[$i]['name']?></td>
                                    <td><?=$listAccess[$i]['access']?></td>
                                </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                    <form method="POST" class="NAccessDiv">
                        <input type="text" name="nameNewAccess" placeholder="Name of access...">
                        <table class="tableNAccess">
                            <!--<button type="button" onclick="selectAllAccessNew(<?='\'' . count($accessListing)?> . '\'')">Select all</button>
                            <button type="button" onclick="unselectAllAccessNew(<?='\'' . count($accessListing)?> . '\''?>)">Unselect all</button>
                            -->
                            <thead>
                                <tr>
                                    <th>Access name</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                            <tbody id="tblBodyNAccess">
                                <?php
                                    for ($i = 0; $i < count($accessListing); $i++) {
                                ?>
                                    <tr>
                                        <th><?=$accessListing[$i]?></th>
                                        <th><input type="checkbox" name="<?="access-" . $i?>" checked></th>
                                    </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <input type="submit" name="addNewAccess" value="New Access">
                    </form>
                </div>
                <div class="contextDev" id="extensionListing">
                    <table class="tablePages">
                        <caption><h1>Extensions</h1></caption>
                        <thead>
                            <tr>
                                <th style="width:   12px !important;"><?=$hlp->getLangWorldMainFile("extensionListing-title-icon")?></th>
                                <th style="width: 250px !important;"><?=$hlp->getLangWorldMainFile("extensionListing-title-name")?></th>
                                <th style="width: 12px !important;"><?=$hlp->getLangWorldMainFile("extensionListing-title-author")?></th>
                                <th style="width: 200px !important;"><?=$hlp->getLangWorldMainFile("extensionListing-title-description")?></th>
                                <th style="width: 12px !important;"><?=$hlp->getLangWorldMainFile("extensionListing-title-category")?></th>
                                <th><?=$hlp->getLangWorldMainFile("extensionListing-title-actions")?></th>
                                <th style="width: 12px !important;"><?=$hlp->getLangWorldMainFile("extensionListing-title-used")?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                for ($i = 0; $i < count($extensionsList); $i++) {
                                    $extension = $extensionsList[$i];
                                    $categoriesExtension = array();
                                    if ($extension['isFront'] == "true") {
                                        array_push($categoriesExtension, "Front");
                                    }
                                    if ($extension['isBack'] == "true") {
                                        array_push($categoriesExtension, "Back");
                                    }
                                    if ($extension['isDbExt'] == "true") {
                                        array_push($categoriesExtension, "Database");
                                    }
                            ?>
                                <tr>
                                    <td>
                                        <picture>
                                            <img src="<?=$extension['icon']?>">
                                        </picture>
                                    </td>
                                    <td><p><?=$extension['name']?></p></td>
                                    <td><p><?=$extension['author']?></p></td>
                                    <td><p><?=$extension['description']?></p></td>
                                    <td>
                                    <?php
                                        for ($cat = 0; $cat < count($categoriesExtension); $cat++) {
                                    ?>
                                        <div class="categorieExtension" id="<?="catExt-" . $categoriesExtension[$cat]?>">
                                            <p><?=$categoriesExtension[$cat]?></p>
                                        </div>
                                    <?php
                                        }
                                    ?>
                                    </td>
                                    <td>
                                        <form method="POST" id="<?="extAction-" . $extension['folder']?>">
                                            <?php
                                                if ($extension['use'] == "true") {
                                            ?>
                                                <button type="submit" form="<?="extAction-" . $extension['folder']?>" class="stopUsingExtension" title="<?=$hlp->getLangWorldMainFile("extensionListing-disbale")?>" name="<?="extStop-" . $extension['folder']?>"><box-icon  color="red" size="lg" type='solid' flip="horizontal" name='message-square-x'></box-icon></i></button>
                                            <?php
                                                } else {
                                            ?>
                                                <button type="submit" form="<?="extAction-" . $extension['folder']?>" class="stopUsingExtension" title="<?=$hlp->getLangWorldMainFile("extensionListing-enable")?>" name="<?="extStart-" . $extension['folder']?>"><box-icon color="green" size="lg" name='message-square-check' type='solid' ></box-icon></i></button>
                                            <?php
                                                }
                                            ?>
                                            <button type="submit" form="<?="extAction-" . $extension['folder']?>" class="stopUsingExtension" title="<?=$hlp->getLangWorldMainFile("extensionListing-delete")?>" name="<?="extDelete-" . $extension['folder']?>"><box-icon size="lg" type='solid' name='trash'></box-icon></button>
                                        </form>
                                    </td>
                                    <td>
                                        <?php
                                            if ($extension['use'] == "true") {
                                        ?>
                                            <box-icon title="<?=$hlp->getLangWorldMainFile("extensionListing-enabled")?>" type='solid' size="lg" color="green" name='check-circle'></box-icon>
                                        <?php
                                            } else {
                                        ?>
                                            <box-icon title="<?=$hlp->getLangWorldMainFile("extensionListing-disbaled")?>" color="red" size="lg" type='solid' name='x-circle'></box-icon>
                                        <?php
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="contextDev" id="redirectsManager">
                    <table class="redirectsTable">
                        <thead>
                            <tr>
                                <th class="iconListingRedirects"></th>
                                <th class="lastListingRedirects"><?=$hlp->getLangWorldMainFile("listingRedirects-last")?></th>
                                <th class="newListingRedirects"><?=$hlp->getLangWorldMainFile("listingRedirects-new")?></th>
                                <th class="useListingRedirects"><?=$hlp->getLangWorldMainFile("listingRedirects-use")?></th>
                                <th class="actionListRedirects"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                for ($i = 0; $i < count($allRedirects); $i++) {
                            ?>
                                <tr>
                                    <td class="iconListingRedirects">
                                        <i class='bx bx-exclude bx-md'></i>
                                        <?php
                                            if ($allRedirects[$i]['deleted'] == 1) {
                                        ?>
                                            <i class='bx bxs-error-circle bx-sm' style='color:#ff0000;position: absolute;transform: translate(-105%, 50%);' title="<?=$hlp->getLangWorldMainFile("w-deleted")?>"></i>
                                        <?php
                                            }
                                        ?>
                                    </td>
                                    <td class="lastListingRedirects"><?=$allRedirects[$i]['last_path']?></td>
                                    <td class="newListingRedirects"><?=$allRedirects[$i]['new_path']?></td>
                                    <td class="useListingRedirects"><?=$allRedirects[$i]['used'] . " " . strtolower($hlp->getLangWorldMainFile("listingRedirects-use"))?></td>
                                    <td class="actionListRedirects">
                                        <form method="POST">
                                            <div class="btn-block">
                                                <?php
                                                    if ($allRedirects[$i]['deleted'] == 0) {
                                                ?>
                                                <button name="<?="deleteRedirect-" . $allRedirects[$i]['id']?>" title="<?=$hlp->getLangWorldMainFile("listingRedirects-delete")?>"><i class='bx bx-trash bx-md'></i></button>
                                                <?php
                                                    } else {
                                                ?>
                                                <button name="<?="restoreRedirect-" . $allRedirects[$i]['id']?>" title="<?=$hlp->getLangWorldMainFile("listingRedirects-restore")?>"><i class='bx bx-copy-alt bx-md'></i></button>
                                                <?php
                                                    }
                                                ?>
                                                <button name="<?="reinitHistRedirect-" . $allRedirects[$i]['id']?>" title="<?=$hlp->getLangWorldMainFile("listingRedirects-reinit")?>"><i class='bx bxs-exit bx-md'></i></button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                    <form method="POST">
                        <input name="lastRedirect" placeholder="<?=$hlp->getLangWorldMainFile("creaetRedirect-last")?>">
                        <input name="newRedirect" placeholder="<?=$hlp->getLangWorldMainFile("creaetRedirect-new")?>">
                        <input type="submit" value="<?=$hlp->getLangWorldMainFile("creaetRedirect")?>" name="creaeteRedirect">
                    </form>
                </div>
                <div class="contextDev" id="imagesContext">
                    <div id="listImages-medias">
                        <table class="tableMedia">
                            <caption><?=$hlp->getLangWorldMainFile("w-medias")?></caption>
                            <thead>
                                <tr>
                                    <th class="tableMedia-icon"></th>
                                    <th class="tableMedia-img"></th>
                                    <th class="tableMedia-name">Name</th>
                                    <th class="tableMedia-action"></th>
                                    <th class="tableMedia-description">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="tableMedia-icon"><button id="btnaddImage-media" onclick="displayNavMenu('newImage-medias', null, 'btnaddImage-media', 'listImages-medias')" title="<?=$hlp->getLangWorldMainFile("add-image")?>"><i class='bx bx-plus-medical'></i></button></td>
                                    <td class="tableMedia-img"><?=$hlp->getLangWorldMainFile("add-image")?></td>
                                    <td class="tableMedia-name"></td>
                                    <td class="tableMedia-action"></td>
                                    <td class="tableMedia-description"></td>
                                </tr>
                                <?php
                                    for ($i = 0; $i < count($mediaListing); $i++) {
                                        $imageSite = $mediaListing[$i];
                                ?>
                                    <tr>
                                        <td class="tableMedia-icon">
                                            <i class='<?=$hlp->getIconsMedia($imageSite['type'])?> bx-md'></i>
                                        </td>
                                        <td class="tableMedia-img"><img src="<?=$hlp->getPathMedia($imageSite['name'])?>"></td>
                                        <td class="tableMedia-name"><?=$imageSite['name']?></td>
                                        <td class="tableMedia-action">
                                            <form class="btn-block" method="POST">
                                                <button name="<?="editMedia-" . $imageSite['id']?>" title="<?=$hlp->getLangWorldMainFile("w-edit")?>"><i class='bx bx-edit-alt bx-sm'></i></button>
                                                <button type="button" title="<?="Id : " . $imageSite['id']?>"><i class='bx bx-dots-horizontal-rounded bx-sm'></i></button>
                                                <?php
                                                    if ($imageSite['deleted']) {
                                                ?>
                                                    <button name="<?="enableMedia-" . $imageSite['id']?>" title="<?=$hlp->getLangWorldMainFile("w-enable")?>"><i class='bx bxs-toggle-right bx-sm'></i></button>
                                                <?php
                                                    } else {
                                                ?>
                                                    <button name="<?="disableMedia-" . $imageSite['id']?>" title="<?=$hlp->getLangWorldMainFile("w-disable")?>"><i class='bx bx-toggle-left bx-sm'></i></button>
                                                <?php
                                                    }
                                                ?>
                                                <button name="<?="deleteMedia-" . $imageSite['id']?>" title="<?=$hlp->getLangWorldMainFile("w-delete")?>"><i class='bx bx-trash bx-sm'></i></button>
                                            </form>
                                        </td>
                                        <td class="tableMedia-description"><?=$imageSite['description']?></td>
                                    </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <form method="POST" id="newImage-medias" style="display: none;">
                        <div class="barAction-addMedia">
                            <button type="button" onclick="abortAddImage('btnaddImage-media', 'listImages-medias', 'newImage-medias')" title="<?=$hlp->getLangWorldMainFile("w-abort")?>"><i class='bx bx-arrow-back bx-sm'></i></button>
                            <button name="addImageEdit" type="submit" title="<?=$hlp->getLangWorldMainFile("w-save")?>"><i class='bx bx-save bx-sm'></i></button>
                        </div>
                        <div class="addContent-media">
                            <div class="navBarMenu-mediaAdd">
                                <button id="btnImageEdit1" type="button" onclick="changeMenuEdit('btnImageEdit1', 'editImage1')" class="generalButton-edit"><?=$hlp->getLangWorldMainFile("w-general")?></button>
                                <button id="btnImageEdit2" type="button" onclick="changeMenuEdit('btnImageEdit2', 'editImage2')" class="generalButton-edit">Notes</button>
                            </div>
                            <div class="generalMenu-edit" id="editImage1">
                                <h3><?=$hlp->getLangWorldMainFile("w-enable")?></h3>
                                <input id="valueEnableImageAdd" type="checkbox" name="isEnableImage-add" hidden>
                                <button class="ownToggle" type="button" onclick="ownToggleButton('valueEnableImageAdd', 'valueEnableImageAddOwn')"><i id="valueEnableImageAddOwn" class='bx bx-toggle-left bx-lg'></i></button>
                                <h3><?=$hlp->getLangWorldMainFile("extensionListing-title-name")?></h3>
                                <input type="text" placeholder="<?=$hlp->getLangWorldMainFile("extensionListing-title-name") . "..."?>">
                                <h3><?="Type url"?></h3>
                                <input id="valueTypeImageAdd" type="checkbox" name="isEnableImage-add" hidden>
                                <button class="ownToggle" type="button" onclick="ownToggleButton('valueTypeImageAdd', 'valueTypeImageAddOwn'); imageCheckIsUrlEdit()"><i id="valueTypeImageAddOwn" class='bx bx-toggle-left bx-lg'></i></button>
                                <div id="urlEditImage">
                                    <h3>Insert url</h3>
                                    <input type="url" placeholder="Image url..." name="imgUrl-edit">
                                </div>
                                <div id="insertEditImage">
                                    <h3>Insert file</h3>
                                    <input type="file">
                                </div>
                            </div>
                            <div class="generalMenu-edit" id="editImage2">
                                <h3>Description</h3>
                                <textarea class="descriptionEdit"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <?php
                    echo $ext->getHtmlAddedExtensionManager();
                ?>
            </div>
        </div>
        <div id="softwareIntels" style="display: none;">
            <div class="contentSoftwareIntels">
                <p><?=$hlp->getLangWorldMainFile("softwareVersion") . $cf->getValueFromKeyConf($cf->getFilesConfig(), "software-version")?></p>
                <a href="<?=$cf->getValueFromKeyConf($cf->getFilesConfig(), "help-link")?>" style="color: #a1a1a1;" target="_blank"><?=$hlp->getLangWorldMainFile("w-help")?></a>
                <p><?=$hlp->getLangWorldMainFile("emailUsed") . $_SESSION['suemail']?></p>
                <form method="POST" class="formChangeLang">
                    <select name="chgLanguage" class="selectLang" onchange="this.form.submit()">
                        <option hidden selected><?=strtoupper($_SESSION['language'])?></option>
                        <?php
                            for ($i = 0; $i < count($languages); $i++) {
                        ?>
                            <option value="<?=strtolower($languages[$i]['name_short'])?>"><?=strtoupper($languages[$i]['name_short'])?></option>
                        <?php
                            }
                        ?>
                    </select>
                </form>
            </div>
        </div>
        <div id="refreshMedias" style="display: none;">
            <div class="contentSoftwareIntels">
                <p><?=$hlp->getLangWorldMainFile("refreshMediaTitle")?></p>
                <button class="btnRefreshMedia"><i class='bx bxs-error-alt bx-sm' style='color:#ffcc00'></i><?=$hlp->getLangWorldMainFile("refreshImages")?></button>
                <button class="btnRefreshMedia"><i class='bx bxs-error-circle bx-sm' style='color:#ff7600'></i><?=$hlp->getLangWorldMainFile("refreshVideos")?></button>
                <button class="btnRefreshMedia"><i class='bx bxs-error bx-sm' style='color:#ff0000'></i><?=$hlp->getLangWorldMainFile("refreshAudios")?></button>
                <button class="btnRefreshMedia"><i class='bx bxs-radiation bx-sm' style='color:#ff00e0'></i><?=$hlp->getLangWorldMainFile("refreshAll")?></button>
            </div>
        </div>
        <?php
            if (isset($_GET['pageBtn']) && $_GET['pageBtn'] != "") {
        ?>
            <script>
                document.getElementById('<?=$_GET['pageBtn']?>').click();
            </script>
        <?php
            }
            if (isset($_GET['navMenu']) && $_GET['navMenu'] != "") {
        ?>
            <script type="text/javascript">
                var navMenuValue = "<?=$_GET['navMenu']?>";
                navsMenu = navMenuValue.split(",");
                for (var i = 0; i < navsMenu.length; i++) {
                    if (navsMenu[i] != "") {
                        document.getElementById(navsMenu[i]).click();
                    }
                }
            </script>
        <?php
            }
        ?>
    </body>
</html>
