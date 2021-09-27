<?php
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?=$_SESSION['titlePage']?></title>
		<meta charset="utf-8">
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
		<div class="presentationPage">
			<picture>
				<img src=<?=$hlp->getMainUrl() . "/" . $cf->getValueFromKeyConf($cf->getFilesConfig(), "main_iconBlack")?>>
			</picture>
			<div class="whiteSpacing">
				<h1>Nouvelle page <?=" '" . $_SESSION['titlePage'] . "'"?></h1>
				<p>Bravo, vous avez créé une nouvelle page pour votre site</p>
				<p>Il vous est possible de la modifier dans votre espace de gestion du site</p>
				<p>ou directement via les dossier</p>
			</div>
		</div>
	</body>
</html>