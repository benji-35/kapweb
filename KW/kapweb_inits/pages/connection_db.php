<?php
    if (!isset($db)) {
        header("location: " . $hlp->getMainUrl());
        return;
    }
    if (!isset($_SESSION['stepDbConnection']))
        $_SESSION['stepDbConnection'] = 0;
    if ($_SESSION['stepDbConnection'] == 1) {
        if (isset($_SESSION['tampDbCreation'])) {
            $gerArr = explode(",", $_SESSION['tampDbCreation']);
            $connArr = array(
                "host" => $gerArr[0],
                "dbName" => "mysql",
                "username" => $gerArr[1],
                "passsword" => $gerArr[2],
            );
            $db->initDb($connArr);
        } else {
            $_SESSION['stepDbConnection'] = 0;
            header("location: " . $hlp->getMainUrl());
        }
    }
    if ($_SESSION['stepDbConnection'] >= 3) {
        $_SESSION['stepDbConnection'] = 0;
    }
    if (isset($_POST['connect'])) {
        unset($_SESSION['c_errors']);
        $_SESSION['tampDbCreation'] = $_POST['host'] . "," . $_POST['uname'] . "," . $_POST['pwd'];
        $_SESSION['stepDbConnection'] = 1;
        header("location: " . $hlp->getMainUrl());
    }
    if (isset($_POST['finalizeDb'])) {
        unset($_SESSION['c_errors']);
        if ($_POST['selectMethod'] == "1" && $_POST['dbSelector'] != "-1") {
            $gerArr = explode(",", $_SESSION['tampDbCreation']);
            $connArr = array(
                "host" => $gerArr[0],
                "dbName" => $_POST['dbSelector'],
                "username" => $gerArr[1],
                "passsword" => $gerArr[2],
            );
            if ($db->canConnectWithArray($connArr) == false) {
                $_SESSION['stepDbConnection'] = 0;
                header("location: " . $hlp->getMainUrl());
            }
            $db->initDb($connArr);
            $hlp->saveNewDb($connArr);
            $hlp->generateTablesNeeded();
            unset($_SESSION['stepDbConnection']);
            header("location: " . $hlp->getMainUrl() . "");
        } else if ($_POST['selectMethod'] == "2") {
            if ($db->isDbExists($_POST['dbName']) == true) {
                $_SESSION['c_errors'] = "";
                header("location: " . $hlp->getMainUrl());
            } else {
                $gerArr = explode(",", $_SESSION['tampDbCreation']);
                $connArr = array(
                    "host" => $gerArr[0],
                    "dbName" => $_POST['dbName'],
                    "username" => $gerArr[1],
                    "passsword" => $gerArr[2],
                );
                if ($db->createNewDb($_POST['dbName']) == true) {
                    $db->initDb($connArr);
                    $hlp->saveNewDb($connArr);
                    $hlp->generateTablesNeeded();
                }
                unset($_SESSION['stepDbConnection']);
                header("location: " . $hlp->getMainUrl() . "");
            }
        } else {
            header("location: " . $hlp->getMainUrl());
        }
    }
    if (isset($_POST['cancelSteptwo'])) {
        $_SESSION['stepDbConnection'] = 0;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Connection Database</title>
        <link href="<?=$hlp->getMainUrl() . "/KW/kapweb_inits/ressources/css/kwdbconnect.css"?>" rel="stylesheet">
        <link rel="icon" href="<?=$hlp->getMainUrl() . "/KW/kapweb_inits/ressources/medias/kwLogoOrange.ico"?>">
    </head>
    <body>
        <?php
            if ($_SESSION['stepDbConnection'] == 0) {
        ?>
            <form class="forms" id="form1" method="POST">
                <picture>
                    <img src="<?=$hlp->getMainUrl() . "/" . "KW/kapweb_inits/ressources/medias/kwLogo.png"?>">
                </picture>
                <h3>PhpMyAdmin Intels</h3>
                <div class="whiteInForm">
                    <div class="headerLineBlack">
                        <div class="lineBlack"></div>
                        <h3>Database</h3>
                    </div>
                    <input type="text" placeholder="host..." required name="host">
                    <input type="text" placeholder="username..." name="uname">
                    <input type="password" placeholder="password..." name="pwd">
                    <input type="submit" value="Se connecter" name="connect">
                    <?php
                        if (isset($_SESSION['c_errors'])) {
                    ?>
                        <p class="errors"><?=$_SESSION['c_errors']?></p>
                    <?php
                        }
                    ?>
                </div>
                <p>Toutes les étapes qui vont suivre sont obligatoire pour utiliser cette interface.Si vous n'avez pas phpMyAdmin on vous demande de l'installer</p>
            </form>
        <?php
            } else {
                $emptiesBd = $db->getEmptyDb();
        ?>
            <form class="forms" id="form2" method="POST">
                <picture>
                    <img src="<?=$hlp->getMainUrl() . "/" . "KW/kapweb_inits/ressources/medias/kwLogo.png"?>">
                </picture>
                <h3>Choose Database</h3>
                <div class="whiteInForm">
                    <select id="selectMethod" onchange="displayGoodInterface()" name="selectMethod">
                        <option value="0" hidden>Select method</option>
                        <?php
                            if (count($emptiesBd) > 0) {
                        ?>
                        <option value="1">Sélectionner une base de donnée</option>
                        <?php
                            }
                        ?>
                        <option value="2">Créer une base de donnée</option>
                    </select>
                    <div id="existing">
                        <label>Prendre une base de donné existante</label>
                        <select name="dbSelector">
                            <?php
                                for ($i = 0; $i < count($emptiesBd); $i++) {
                            ?>
                                <option value="<?=$emptiesBd[$i]?>"><?=$emptiesBd[$i]?></option>
                            <?php
                                }
                                if (count($emptiesBd) <= 0) {
                            ?>
                                <option value="-1" hidden>Pas de DB vide</option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                    <div id="creating">
                        <label>Créer une nouvelle base de donnée</label>
                        <input type="text" placeholder="name..." name="dbName">
                    </div>
                    <input type="submit" name="finalizeDb">
                    <input type="submit" value="cancel" name="cancelSteptwo">
                    <?php
                        if (isset($_SESSION['c_errors'])) {
                    ?>
                        <p class="errors"><?=$_SESSION['c_errors']?></p>
                    <?php
                        }
                    ?>
                </div>
                <p>Cette étape une fois terminé, vous redirigera vers la création de votre compte super utilisateur</p>
            </form>
        <?php
            }
        ?>
        <script>
            function displayGoodInterface() {
                var selectValue = document.getElementById("selectMethod").value;

                console.log("value : ", selectValue);
                if (selectValue == 1) {
                    document.getElementById("existing").style.display = "block";
                    document.getElementById("creating").style.display = "none";
                } else if (selectValue == 2) {
                    document.getElementById("creating").style.display = "block";
                    document.getElementById("existing").style.display = "none";
                } else {
                    document.getElementById("creating").style.display = "none";
                    document.getElementById("existing").style.display = "none";
                }
            }
        </script>
    </body>
</html>