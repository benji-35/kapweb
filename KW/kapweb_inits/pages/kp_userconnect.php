<?php
    $hlp->disconnectSelf();

    $errorConnect = array(
        "The account does not exists",
        "You are connected",
        "Your account is deleted",
        "Your account is baned",
        "Please try another password",
        "Please confirm your account in your email",
        "Please connect with an account knows",
    );
    $errorsCreate = array(
        "This email is already taken in account",
        "You've create an account",
        "An error occured while creating the account"
    );

    if (isset($_POST['connect'])) {
        unset($_SESSION['c_errors']);
        $uid = $hlp->getUidNoAccount($_POST['connectEmail'], $_POST['connectPwd']);
        $suid = $hlp->isSuAccount($_POST['connectEmail'], $_POST['connectPwd']);
        if ($uid == -1) {
            if ($suid == true) {
                unset($_SESSION['c_errors']);
                $_SESSION['suemail'] = $_POST['connectEmail'];
                $_SESSION['supwd'] = $_POST['connectPwd'];
                if (isset($_SESSION['pathAfterConnect'])) {
                    header("location: " . $_SESSION['pathAfterConnect']);
                } else {
                    header("location: " . $hlp->getMainUrl());
                }
            } else {
                $_SESSION['c_errors'] = "Le compte est innexistant ou le mot de passe n'est pas bon.<br>";
            }
        } else {
            unset($_SESSION['c_errors']);
            $resCheck = $hlp->isNoAccount($_POST['connectEmail'], $_POST['connectPwd']);
            if ($resCheck == 1) {
                $_SESSION['no_pwd'] = $_POST['connectPwd'];
                $_SESSION['no_email'] = $_POST['connectEmail'];
                if (isset($_SESSION['pathAfterConnect'])) {
                    header("location: " . $_SESSION['pathAfterConnect']);
                } else {
                    header("location: " . $hlp->getMainUrl());
                }
            } else {
                $_SESSION['c_errors'] = $errorConnect[$resCheck];
                header("location: " . $hlp->getMainUrl() . "/connect");
            }
        }
    }
    if (isset($_POST['create'])) {
        unset($_SESSION['c_errors']);
        if ($_POST['creationPwd'] == $_POST['confirmPwd']) {
            $lname = "";
            $fname = "";
            $pseudo = "";
            if (isset($_POST['creaetPseudoUser'])) {
                $pseudo = $_POST['creaetPseudoUser'];
            }
            if (isset($_POST['createLnameUser'])) {
                $lname = $_POST['createLnameUser'];
            }
            if (isset($_POST['createFnameUser'])) {
                $fname = $_POST['createFnameUser'];
            }
            $res = $hlp->createNoAccount($_POST['creationEmail'], $_POST['creationPwd'], $lname, $fname, $pseudo);
            if ($res == 1) {
                header("location: " . $hlp->getMainUrl() . "/connect");
            } else {
                $_SESSION['c_errors'] = $errorsCreate[$res];
                header("location: " . $hlp->getMainUrl() . "/connect");
            }
        } else {
            $_SESSION['c_errors'] = "The password and the confirm password are differents";
        }
    }
    $need_ids = false;
    $need_pseudo = false;
    $canDisplayCreation = false;
    if ($cf->getValueFromKeyConf($cf->getFilesConfig(), "user-signin-ids") == "true")
        $need_ids = true;
    if ($cf->getValueFromKeyConf($cf->getFilesConfig(), "user-signin-pseudo") == "true")
        $need_pseudo = true;
    if ($cf->getValueFromKeyConf($cf->getFilesConfig(), "user-signin-enable") == "true") {
        $canDisplayCreation = true;
    }

?>
<!DOCTYPE html>
<html  lang="<?=$_SESSION['language']?>">
    <head>
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
				<script type="text/javascript" src="<?=$_SESSION['jsPath']?>"></script>
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
        <div id="connection" class="formConnect">
            <form method="POST">
                <picture>
                        <img src="<?=$hlp->getMainUrl() . "/KW/kapweb_inits/ressources/medias/" . $cf->getValueFromKeyConf($cf->getFilesConfig(), "img-no-connection")?>">
                </picture>
                <h3>Connexion</h3>
                <div class="whiteInForm">
                    <input type="email" placeholder="Email..." name="connectEmail" required>
                    <input type="password" placeholder="Password..." name="connectPwd" required>
                    <input type="submit" value="Se connecter" name="connect">
                    <?php
                        if (isset($_SESSION['c_errors'])) {
                    ?>
                        <p class="errorMsg" style="background-color: rgba(255, 0, 0, 0.514);font-size: small;"><?=$_SESSION['c_errors']?></p>
                    <?php
                        }
                    ?>
                    <div class="forgetPassword">
                        <a href="<?=$hlp->getMainUrl()?>"><p>Forgot your password ?</p></a>
                    </div>
                </div>
                <?php
                    if ($canDisplayCreation == true) {
                ?>
                <p>Pas de compte ? Cliquez sur le bouton ci-dessous.</p>
                <button type="button" onclick="openClosePannel('creation', 'connection')">Créer un compte</button>
                <?php
                    } else {
                ?>
                <p>Le site ne vous permet pas de créer de compte.</p>
                <?php
                    }
                ?>
            </form>
        </div>
        <?php
            if ($canDisplayCreation == true) {
        ?>
        <div id="creation" class="formConnect">
            <form method="POST">
                <picture>
                    <img src="<?=$hlp->getMainUrl() . "/KW/kapweb_inits/ressources/medias/" . $cf->getValueFromKeyConf($cf->getFilesConfig(), "img-no-createAccount")?>">
                </picture>
                <h3>Création de compte</h3>
                <div class="whiteInForm">
                    <?php
                        if ($need_pseudo) {
                    ?>
                        <input type="text" placeholder="Pseudo..." name="creaetPseudoUser" required>
                    <?php
                        }
                    ?>
                    <input type="email" placeholder="Email..." name="creationEmail" required>
                    <?php
                     if ($need_ids == true) {
                    ?>
                        <input type="text" placeholder="First name..." name="createFnameUser" required>
                        <input type="text" placeholder="Last name..." name="createLnameUser" required>
                    <?php
                        }
                    ?>
                    <input type="password" placeholder="Password..." name="creationPwd" required>
                    <input type="password" placeholder="Confirm password..." name="confirmPwd" required>
                    <input type="submit" value="Créer le compte" name="create">
                    <?php
                        if (isset($_SESSION['c_errors'])) {
                    ?>
                        <p class="errorMsg" style="background-color: rgba(255, 0, 0, 0.514);font-size: small;"><?=$_SESSION['c_errors']?></p>
                    <?php
                        }
                    ?>
                </div>
                <p>Vous avez déjà un compte ? Cliquez sur le bouton ci-dessous.</p>
            </form>
            <button onclick="openClosePannel('connection', 'creation')">Déjà un compte ?</button>
        </div>
        <?php
            }
        ?>
    </body>
</html>