<?php
    $_SESSION['editName'] =  $_SESSION['titlePage'];
?>

<!DOCTYPE html>
<html lang="<?=$hlp->getLanguageShortFromId($_SESSION['lang'])?>">
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
</html>