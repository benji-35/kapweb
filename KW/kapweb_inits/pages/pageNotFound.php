<?php
	if (isset($_POST['back'])) {
		header("location: " . $hlp->getMainUrl());
	}
?>
<!DOCTYPE html>
<html  lang="<?=$hlp->getLanguageShortFromId($_SESSION['lang'])?>">
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
		<form class="content" method="POST">
			<h1>Page Not Found</h1>
			<p>Sorry the page you research is not found. You can go back in main page by this button</p>
			<input type="submit" value="Back main page" name="back">
		</form>
    </body>
</html>