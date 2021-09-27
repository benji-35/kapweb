<?php
    if ($hlp->isConnectedSu() == false)
        header("location: " . $hlp->getMainUrl() . "/KW");
    if (!isset($_SESSION['supseudo']) || !isset($_SESSION['supwd']))
        header("location: " . $hlp->getMainUrl() . "/KW");
    $pages = $hlp->getPages();
    $deletedPages = $hlp->getDeletedPages();
    $tables = $hlp->getTables();
    $dTables = $hlp->getDeletedTables();
    $admins = $hlp->getAdmins();
    $users = $hlp->getUsers();
    $myAccount = $hlp->getMyAccountConnect();
    $cookies = $hlp->getCookies();

    if (isset($_POST['createFirstPage'])) {
        unset($_SESSION['pageError']);
        $resCheck = $hlp->addPage($_POST['nameFirstPage'], "/", $_POST['titleFirstPage'], true);
        if ($resCheck == false)
            $_SESSION['pageError'] = "An error occured while create your first page";
        header("location: " . $hlp->getMainUrl() . "/KW/manager");
    }
    if (isset($_POST['disconnect'])) {
        header("location: " . $hlp->getMainUrl() . "/KW");
    }
    if (isset($_POST['newPage'])) {
        unset($_SESSION['pageError']);
        $nurl = $_POST['newPageUrl'];
        if ($nurl[0] != '/')
            $nurl = "/" . $nurl;
        $resCheck = $hlp->addPage($_POST['newPageName'], $nurl, $_POST['newTitlePage'], false);
        if ($resCheck == false)
            $_SESSION['pageError'] = "Page name already taken please get another name for your page";
        header("location: " . $hlp->getMainUrl() . "/KW/manager");
    }
    if (isset($_POST['deleteAllPages'])) {
        $hlp->deleteAllPages();
        header("location: " . $hlp->getMainUrl() . "/KW/manager");
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

    for ($i = 0; $i < count($admins); $i++) {
        if (isset($_POST['deleteSuUser-' . $admins[$i]['uid']])) {
            $hlp->deleteSuUserAccount($admins[$i]['email']);
            header("location: " . $hlp->getMainUrl() . "/KW");
        }
        if (isset($_POST['banSuUser-' . $admins[$i]['uid']])) {
            $hlp->banSuUserAccount($admins[$i]['email']);
            header("location: " . $hlp->getMainUrl() . "/KW");
        }
        if (isset($_POST['unbanSuUser-' . $admins[$i]['uid']])) {
            $hlp->unbanSuUserAccount($admins[$i]['email']);
            header("location: " . $hlp->getMainUrl() . "/KW");
        }
        if (isset($_POST['restoreSuUser-' . $admins[$i]['uid']])) {
            $hlp->restoreSuUserAccount($admins[$i]['email']);
            header("location: " . $hlp->getMainUrl() . "/KW");
        }
    }

    for ($i = 0; $i < count($users); $i++) {
        if (isset($_POST['deleteUser-' . $users[$i]['uid']])) {
            $hlp->deleteNoUserAccount($users[$i]['email']);
            header("location: " . $hlp->getMainUrl() . "/KW/manager");
        }
        if (isset($_POST['banUser-' . $users[$i]['uid']])) {
            $hlp->banNoUserAccount($users[$i]['email']);
            header("location: " . $hlp->getMainUrl() . "/KW/manager");
        }
        if (isset($_POST['unbanNoUser-' . $users[$i]['uid']])) {
            $hlp->unbanNoUserAccount($users[$i]['email']);
            header("location: " . $hlp->getMainUrl() . "/KW/manager");
        }
        if (isset($_POST['restoreNoUser-' . $users[$i]['uid']])) {
            $hlp->restoreNoUserAccount($users[$i]['email']);
            header("location: " . $hlp->getMainUrl() . "/KW/manager");
        }
    }

    for ($i = 0; $i < count($pages); $i++) {
        if (isset($_POST['deletePage-' . $pages[$i]['name']])) {
            $hlp->deletePage($pages[$i]['name']);
            header("location: " . $hlp->getMainUrl() . "/KW/manager");
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
            header("location: " . $hlp->getMainUrl() . "/KW/manager");
        }
    }
    for ($i = 0; $i < count($deletedPages); $i++) {
        if (isset($_POST['restorePage-' . $deletedPages[$i]['name']])) {
            $hlp->restorePage($deletedPages[$i]['name']);
            header("location: " . $hlp->getMainUrl() . "/KW/manager");
        }
        if (isset($_POST['finalDeletePage-' . $deletedPages[$i]['name']])) {
            $hlp->finalDeletePages($deletedPages[$i]['name'], $deletedPages[$i]['path'], $deletedPages[$i]['pathCss'], $deletedPages[$i]['pathJs']);
            header("location: " . $hlp->getMainUrl() . "/KW/manager");
        }
    }

    for ($i = 0; $i < count($tables); $i++) {
        if (isset($_POST['deleteTable-' . $tables[$i]['name']])) {
            $hlp->deleteTable($tables[$i]['name']);
            header("location: " . $hlp->getMainUrl() . "/KW/manager");
        }
    }

    for ($i = 0; $i < count($dTables); $i++) {
        if (isset($_POST['finalyDeleteTable-' . $dTables[$i]['name']])) {
            $hlp->finalDeleteTable($dTables[$i]['name']);
            header("location: " . $hlp->getMainUrl() . "/KW/manager");
        }
        if (isset($_POST['restoreTable-' . $dTables[$i]['name']])) {
            $hlp->restoreTable($dTables[$i]['name']);
            header("location: " . $hlp->getMainUrl() . "/KW/manager");
        }
    }

    for ($i = 0; $i < count($cookies); $i++) {
        if (isset($_POST['deleteCookie-' . $cookies[$i]['name']])) {
            $hlp->deleteCookie($cookies[$i]['name']);
        }
        if (isset($_POST['restoreCookie-' . $cookies[$i]['name']])) {
            $hlp->restoreCookie($cookies[$i]['name']);
        }
        if (isset($_POST['finalDeleteCookie-' . $cookies[$i]['name']])) {
            $hlp->finalDeleteCookie($cookies[$i]['name']);
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
        header("location: " . $hlp->getMainUrl() . "/KW/manager");
    }

    if (isset($_POST['newCookie'])) {
        $resCheck = $hlp->addCookie($_POST['newCookieName'], $_POST['newCookieDescription'], $_POST['newTitlePage']);
        unset($_SESSION['cookiesError']);
        if ($resCheck == false)
            $_SESSION['cookiesError'] = "The cookie name is already taken";
        header("location: " . $hlp->getMainUrl() . "/KW/manager");
    }

    if (isset($_POST['nawAdmin'])) {
        unset($_SESSION['addAdminError']);
        $errorsCreateAdmin = array(
            "This email is already taken in account",
            "You've create an account",
            "An error occured while creating the account"
        );
        if ($_POST['pwdNewAdmin'] == $_POST['confNewAdminPwd']) {
            $resCheck = $hlp->createSuAccount($_POST['pseudoNewAdmin'], $_POST['emailNewAdmin'], $_POST['pwdNewAdmin']);
            if ($resCheck != 1) {
                $_SESSION['addAdminError'] = $errorsCreateAdmin[$resCheck];
            }
        } else {
            $_SESSION['addAdminError'] = "Le mot de passe n'est pas le même que le mot de passe de confirmation";
        }
        header("location: " . $hlp->getMainUrl() . "/KW/manager");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
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
        <div class="content" id="maincontent">
            <div class="navMenu">
                <h3>Menu de navigation</h3>
                <button id="btnDashboard" class="btnNavMenu" onclick="displayContextMenu('dashboardContext', 'btnDashboard')"><i class="fa fa-house-user"></i><?=" Dashboard"?></button>
                <button class="btnNavMenu" onclick="displayNavMenu('navMenuAdmin', 'iconAdmin')"><i id="iconAdmin" class="far fa-arrow-alt-circle-down"></i><?=" Admin"?></button>
                <div class="closeMenuNav" id="navMenuAdmin">
                    <button class="btnNavMenu" id="btnAdmin" onclick="displayContextMenu('administratorsContext', 'btnAdmin')"><i class="fas fa-users-cog"></i><?=" Administrateurs"?></button>    
                    <button class="btnNavMenu" id="btnUsers" onclick="displayContextMenu('usersContext', 'btnUsers')"><i class="fa fa-users"></i><?=" Utilisateur"?></button>
                </div>
                <button class="btnNavMenu" onclick="displayNavMenu('navMenuWebsite', 'iconWebsite')"><i id="iconWebsite" class="far fa-arrow-alt-circle-down"></i><?=" Site Web"?></button>
                <div class="closeMenuNav" id="navMenuWebsite">
                    <button class="btnNavMenu" id="btnPages" onclick="displayContextMenu('pagesContext', 'btnPages')"><i id="iconWebsite" class="fa fa-file-word"></i><?=" Pages"?></button>
                    <button class="btnNavMenu" id="btnDeletedPages" onclick="displayContextMenu('deletedPagesContext', 'btnDeletedPages')"><i id="iconWebsite" class="fa fa-trash-alt"></i><?=" Deleted Pages"?></button>
                </div>
                <button class="btnNavMenu" onclick="displayNavMenu('navMenuFiles', 'iconFiles')"><i id="iconFiles" class="far fa-arrow-alt-circle-down"></i><?=" Fichiers"?></button>
                <div class="closeMenuNav" id="navMenuFiles">
                    <button  id="btnCookies"class="btnNavMenu" onclick="displayContextMenu('cookieContext', 'btnCookies')"><i class="fas fa-save"></i><?=" Cookies"?></button>
                    <button  id="btnDb"class="btnNavMenu" onclick="displayContextMenu('dbContext', 'btnDb')"><i class="fas fa-database"></i><?=" Database"?></button>
                    <button  id="btnDeletedDb"class="btnNavMenu" onclick="displayContextMenu('deletedTables', 'btnDeletedDb')"><i class="fas fa-trash-alt"></i><?=" Deleted Database"?></button>
                </div>
                <form class="deconnectForm" method="POST">
                    <input type="submit" value="disconnect" name="disconnect">
                </form>
            </div>
            <div class="contextMenu">
                <div class="contextDev" id="dashboardContext">
                    <h1 class="titleContextDev">Dashboard</h1>
                    <form class="contentContext" method="POST">
                        <h2>URL</h2>
                        <input value="<?=$hlp->getMainUrl()?>" readonly>  
                        <input type="submit" value="Supprimer toues les pages" name="deleteAllPages">
                        <input type="submit" value="Remettre le KW à zéro" name="toOKw">
                        <input type="submit" value="Tout réinitialiser" name="reinitAll">
                    </form>
                    <?php
                        if (count($myAccount) > 0) {
                    ?>
                        <h3>Votre compte :</h3>
                        <p><?="Pseudo : " . $myAccount['pseudo']?></p>
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
                                    for ($i = 0; $i < count($admins); $i++) {
                                        if ($admins[$i]['deleted'] == 0 && $admins[$i]['baned'] == 0) {
                                ?>
                                    <tr>
                                        <th><?=$admins[$i]['pseudo']?></th>
                                        <th><?=$admins[$i]['lname']?></th>
                                        <th><?=$admins[$i]['fname']?></th>
                                        <th><?=$admins[$i]['email']?></th>
                                        <th>
                                            <input class="deleteTable" type="submit" value="Delete" name="<?="deleteSuUser-" . $admins[$i]['uid']?>">
                                            <input class="deleteTable" type="submit" value="Ban" name="<?="banSuUser-" . $admins[$i]['uid']?>">
                                        </th>
                                    </tr>
                                <?php
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                        <h3>Administrateurs ban OU supprimés</h3>
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
                                    for ($i = 0; $i < count($admins); $i++) {
                                        if ($admins[$i]['baned'] == 1 || $admins[$i]['deleted'] == 1) {
                                            $status = "";
                                            $color = "red";
                                            if ($admins[$i]['baned'] == 1) {
                                                $status = "Baned";
                                            }
                                            if ($admins[$i]['deleted'] == 1) {
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
                                        <th><?=$admins[$i]['email']?></th>
                                        <th><?=$admins[$i]['pseudo']?></th>
                                        <th><?=$admins[$i]['lname']?></th>
                                        <th><?=$admins[$i]['fname']?></th>
                                        <th style="<?="color: " . $color . ";"?>"><?=$status?></th>
                                        <th>
                                            <?php
                                                if ($admins[$i]['deleted'] == 1) {
                                            ?>
                                            <input class="deleteTable" type="submit" value="Restore" name="<?="restoreSuUser-" . $admins[$i]['uid']?>">
                                            <?php
                                                }
                                                if ($admins[$i]['baned'] == 1) {
                                            ?>
                                            <input class="deleteTable" type="submit" value="Unban" name="<?="unbanSuUser-" . $admins[$i]['uid']?>">
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
                    <form class="formCreatePage" method="POST">
                        <picture>
                            <img src="<?=$hlp->getMainUrl() . "/" . $cf->getValueFromKeyConf($cf->getFilesConfig(), "main_icon_png")?>">
                        </picture>
                        <h3>Créer un nouveau compte admin</h3>
                        <div class="whiteDiv">
                            <input type="text" placeholder="Pseudo" name="pseudoNewAdmin" required>
                            <input type="email" placeholder="Email..." name="emailNewAdmin" required>
                            <input type="password" placeholder="Mot de passe.." name="pwdNewAdmin" required>
                            <input type="password" placeholder="Confirmez mot de passe..." name="confNewAdminPwd" required>
                            <input type="submit" value="créer" name="nawAdmin">
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
                                <th class="mainpageTablePage">Is main page</th>
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
                                $mainPageState = "no";
                                if ($pages[$i]['mainPage'] == "1")
                                    $mainPageState = "yes";
                    ?>
                        <tr>
                            <th class="nameTablePage"><?=$pages[$i]['name']?></th>
                            <th class="urlTablePage"><?=$hlp->getMainUrl() . $pages[$i]['url']?></th>
                            <th class="titleTablePage"><?=$pages[$i]['title']?></th>
                            <th class="mainpageTablePage"><?=$mainPageState?></th>
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
                            <th class="editTablePage"><a class="editPageUrl" href="<?=$urlPageEdit?>"><div class="dEditPageLink"><p>edit</p></div></a></th>
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
                            <h3>Créer la première page du site</h3>
                            <div class="whiteDiv">
                                <input type="text" placeholder="nom de la page..." name="nameFirstPage" required>
                                <input type="text" placeholder="titre de la page" name="titleFirstPage" required>
                                <input type="submit" value="créer" name="createFirstPage">
                                <?php
                                    if (isset($_SESSION['pageError'])) {
                                ?>
                                    <p class="errorMsg" style="background-color: rgba(255, 0, 0, 0.514);"><?=$_SESSION['pageError']?></p>
                                <?php
                                    }
                                ?>
                            </div>
                            <p>Vous pouvez créer votre première page internet en remplissant ce formulaire</p>
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
            </div>
        </div>
    </body>
</html>