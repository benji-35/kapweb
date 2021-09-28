<?php
    $hlp->disconnectSelf();
    $haveToConnect = $hlp->suAccountExists();
    $errorsConnect = array(
        "The account does not exists",
        "You are connected",
        "Your account is deleted",
        "Your account is baned",
        "Please try another password",
        "Please confirm your account in your email"
    );
    $errorsCreate = array(
        "This email is already taken in account",
        "You've create an account",
        "An error occured while creating the account"
    );

    if (isset($_POST['create'])) {
        unset($_SESSION['connectError']);
        $pseudo = NULL;
        $fname = NULL;
        $lname = NULL;
        if (isset($_POST['pseudoCreate'])) {
            $pseudo = $_POST['pseudoCreate'];
        }
        if (isset($_POST['lname'])) {
            $lname = $_POST['lname'];
        }
        if (isset($_POST['fname'])) {
            $fname = $_POST['fname'];
        }
        $resCheck = $hlp->createSuAccount($pseudo, $_POST['emailCreate'], $_POST['pwdCreate'], $lname, $fname);
        if ($resCheck == 1) {
            header("location: " . $hlp->getMainUrl() . "/KW");
        } else {
            $_SESSION['connectError'] = $errorsCreate[$resCheck];
            header("location: " . $hlp->getMainUrl() . "/KW");
        }
    }
    if (isset($_POST['connect'])) {
        unset($_SESSION['connectError']);
        $resCheck = $hlp->isSuAccount($_POST['email'], $_POST['pwd']);
        if ($resCheck == 1) {
            $_SESSION['suemail'] = $_POST['email'];
            $_SESSION['supwd'] = $_POST['pwd'];
            if (isset($_SESSION['pathAfterConnect'])) {
                header("location: " . $_SESSION['pathAfterConnect']);
            } else {
                header("location: " . $hlp->getMainUrl() . "/KW/manager/");
            }
        } else {
            $_SESSION['connectError'] = $errorsConnect[$resCheck];
            header("location: " . $hlp->getMainUrl() . "/KW");
        }
    }
    $need_ids = false;
    $need_pseudo = false;
    if ($cf->getValueFromKeyConf($cf->getFilesConfig(), "user-signin-ids") == "true")
        $need_ids = true;
    if ($cf->getValueFromKeyConf($cf->getFilesConfig(), "user-signin-pseudo") == "true")
        $need_pseudo = true;
?>
<!DOCTYPE html>
<html lang="<?=$_SESSION['lang']?>">
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
        <?php
            if ($haveToConnect == true) {
        ?>
            <form method="POST" class="formConnect">
                <picture>
                    <img src="<?=$hlp->getMainUrl() . "/" . $cf->getValueFromKeyConf($cf->getFilesConfig(), "main_icon_png")?>">
                </picture>
                <h3>Connection Administrator</h3>
                <div class="whiteInForm">
                    <input type="email" placeholder="  email..." name="email" required>
                    <input type="password" placeholder="  password..." name="pwd" required>
                    <input type="submit" value="se connecter" name="connect">
                    <a href="<?=$hlp->getMainUrl()?>"><div class="fakeBtn"><p>Forgot your password ?</p></div></a>
                    <a href="<?=$hlp->getMainUrl()?>"><div class="fakeBtn"><p>Back to main page</p></div></a>
                    <?php
                        if (isset($_SESSION['connectError'])) {
                    ?>
                        <p class="errorMsg" style="background-color: rgba(255, 0, 0, 0.514);"><?=$_SESSION['connectError']?></p>
                    <?php
                        }
                    ?>
                </div>
                <p>Ce passage est obligatoire pour accéder aux menus de gestion de Kapweb</p>
            </form>
        <?php
            } else {
        ?>
            <form method="POST" class="formConnect">
                <img src="<?=$hlp->getMainUrl() . "/" . $cf->getValueFromKeyConf($cf->getFilesConfig(), "main_icon_png")?>">
                <h3>Create account Administrator</h3>
                <div class="whiteInForm">
                    <?php
                        if ($need_pseudo) {
                    ?>
                        <input type="text" placeholder="pseudo..." name="pseudoCreate" required>
                    <?php
                        }
                    ?>
                        <input type="email" placeholder="email..." name="emailCreate" required>
                    <?php
                        if ($need_ids) {
                    ?>
                        <input type="text" placeholder="nom..." name="lname" required>
                        <input type="text" placeholder="prénom..." name="fname" required>
                    <?php
                        }
                    ?>
                    <input type="password" placeholder="password" name="pwdCreate" required>
                    <input type="submit" value="Créer un compte" name="create">
                    <a href="<?=$hlp->getMainUrl()?>"><div class="fakeBtn"><p>Back to main page</p></div></a>
                    <?php
                        if (isset($_SESSION['connectError'])) {
                    ?>
                        <p class="errorMsg" style="background-color: rgba(255, 0, 0, 0.514);"><?=$_SESSION['connectError']?></p>
                    <?php
                        }
                    ?>
                </div>
                <p>To get acces to kapweb interface, you need to create your super user account</p>
            </form>
        <?php
            }
        ?>
    </body>
</html>