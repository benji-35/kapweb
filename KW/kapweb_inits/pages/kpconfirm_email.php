<?php
    $displayConfirm = false;
    $displayChangePwd = false;

    if (isset($_SESSION['confEmail']) && $hlp->cidExists($_SESSION['confEmail']) == true) {
        $displayConfirm = true;
    }
    if (isset($_SESSION['chgPwd']) && $hlp->cidExists($_SESSION['chgPwd']) == true) {
        $displayChangePwd = true;
    }
    if ($displayConfirm == false && $displayChangePwd == false) {
        $hdr = "location: " . $hlp->getMainUrl() . "/pageNotFound";
        header($hdr);
    }
    if (isset($_POST['validEmail'])) {
        $hlp->validConfirmEmail($_SESSION['confEmail']);
        header("location: " . $hlp->getMainUrl());
    }
    if (isset($_POST['deleteAccount'])) {
        $hlp->deleteAccountFromCid($_SESSION['confEmail']);
        header("location: " . $hlp->getMainUrl());
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
        <?php
            if ($displayChangePwd) {
        ?>
            <form class="chgPwd" method="POST">
                <picture>
                    <img src="<?=$hlp->getMainUrl() . "/" . $cf->getValueFromKeyConf($cf->getFilesConfig(), "main_icon_png")?>">
                </picture>
                <h3>Changer le mot de passe</h3>
                <div class="whiteSpacing">
                    <input type="password" placeholder="Nouveau mot de passe..." name="newPwd" required>
                    <input type="password" placeholder="Confirmez le mot de passe..." name="confPwd" required>
                    <input type="submit" value="Changer votre mot de passe" name="chgPwd">
                </div>
            </form>
        <?php
            } else if ($displayConfirm) {
        ?>
        <form class="chgPwd" method="POST">
                <picture>
                    <img src="<?=$hlp->getMainUrl() . "/" . $cf->getValueFromKeyConf($cf->getFilesConfig(), "main_icon_png")?>">
                </picture>
                <h3>Valider son email</h3>
                <div class="whiteSpacing">
                    <p>Merci de vous être inscrit sur notre site. Appuyez sur le bouton "Valider l'email" pour valider votre email. Si vous souhaitez supprimer votre compte cliquez sur le bouton "Supprimer le compte"</p>
                    <input type="submit" value="Valider l'email" name="validEmail">
                    <input type="submit" value="Supprimer le compte" name="deleteAccount">
                </div>
            </form>
        <?php
            }
        ?>
    </body>
</html>