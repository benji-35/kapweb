<?php
	$arrfinal = array(
		"css" => null,
		"js" => null
	);
	$pageExists = true;
	if ($hlp->isConnectedSu() == false)
		header("location: " . $hlp->getMainUrl() . "/KW");
	if (!isset($_SESSION['suemail']) || !isset($_SESSION['supwd']) || !isset($_SESSION['urlEdit']))
		header("location: " . $hlp->getMainUrl() . "/KW");
	if ($hlp->pageExists($_SESSION['urlEdit'])) {
		$textToEdit = $ep->getHtmlEditable($_SESSION['urlEdit']);
		$cssJs = $ep->getAllCssJsContent($_SESSION['urlEdit']);
		if (isset($_POST['save'])) {
			$ntext = $_POST['phpEdit'];
			$ep->saveHtmlEditable($_SESSION['urlEdit'], $ntext);
			if ($cssJs['css'] != null) {
				$arrfinal['css'] = $_POST['cssEdit'];
			}
			if ($cssJs['js'] != null) {
				$arrfinal['js'] = $_POST['jsEdit'];
			}
			$ep->saveAllCssJsContent($_SESSION['urlEdit'], $arrfinal);
			header("location: " . $hlp->getMainUrl() . "/KW/editPage/" . $_SESSION['urlEdit']);
		}
	} else {
		$pageExists = false;
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
			if ($pageExists == true) {
		?>
		<form method="POST">
			<h1>Edition</h1>
			<a class="fakeButtonA" href="<?=$hlp->getMainUrl() . "/KW/manager"?>"><div class="fakeButton"><p>Back to manager</p></div></a>
			<div class="frontend">
				<h3>HTML</h3>
				<textarea id="inputHtml" class="phpEdit" name="phpEdit"><?=$textToEdit?></textarea>
			</div>
			<div class="backend">
				<div class="leftBackend">
					<h3>CSS</h3>
					<?php
						if ($cssJs['css'] != null) {
					?>
						<textarea id="inputCss" class="cssEdit" name="cssEdit"><?=$cssJs['css']?></textarea>
					<?php
						} else {
					?>
						<textarea class="cssEdit" name="cssEditNo" readonly>Pas de css pour cette page</textarea>
					<?php
						}
					?>
				</div>
				<div class="rightBackend">
					<h3>JS</h3>
					<?php
						if ($cssJs['js'] != null) {
					?>
						<textarea id="inputJs" class="jsEdit" name="jsEdit"><?=$cssJs['js']?></textarea>
					<?php
						} else {
					?>
						<textarea class="jsEdit" name="jsEditNo" readonly>Pas de js pour cette page</textarea>
					<?php
						}
					?>
				</div>
			</div>
			<div class="frontend">
				<input type="submit" name="save" value="Save Changes">
				<a class="fakeButtonA" target="_blank" href="<?=$hlp->getMainUrl() . $hlp->getUrlFromName($_SESSION['urlEdit'])?>"><div class="fakeButton"><p>View Page</p></div></a>
			</div>
		</form>
		<?php
			} else {
		?>
			<h1>Page does not exists</h1>
			<a href="<?=$hlp->getMainUrl()?>">Back to main page</a>
		<?php
			}
		?>
    </body>
</html>