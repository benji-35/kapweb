<?php
	$arrfinal = array(
		"css" => NULL,
		"js" => NULL
	);
	$pageExists = true;
	if ($hlp->isConnectedSu() == false)
		header("location: " . $hlp->getMainUrl() . "/KW");
	if (!isset($_SESSION['suemail']) || !isset($_SESSION['supwd']) || !isset($_SESSION['urlEdit']))
		header("location: " . $hlp->getMainUrl() . "/KW/manager");
	if ($hlp->pageExists($_SESSION['urlEdit'])) {
		$textToEdit = "";
		$cssJs = $ep->getAllCssJsContent($_SESSION['urlEdit']);
		if (isset($_POST['save'])) {
			$ntext = $_POST['phpEdit'];
			$ep->saveHtmlEditable($_SESSION['urlEdit'], $ntext);
			if ($cssJs['css'] != NULL) {
				$arrfinal['css'] = $_POST['cssEdit'];
			}
			if ($cssJs['js'] != NULL) {
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
<html  lang="<?=$hlp->getLanguageShortFromId($_SESSION['lang'])?>">
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
			<div class="content">
				<table id="tblStocks" class="tablePage">
					<caption><h2>Page Elements</h2></caption>
					<thead>
						<tr>
							<th><p>Name (editable)</p></th>
							<th><p>Type</p></th>
							<th><p>Parent</p></th>
							<th><p>Content (editable)</p></th>
							<th><p>Special Edit (editable)</p></th>
							<th><p>Add Child</p></th>
							<th><p>Changes</p></th>
						</tr>
					</thead>
					<tbody id="bodyTableEdit">
						<?php
							$elems = $ep->getHtmlEditor();
							for ($i = 0; $i < count($elems); $i++) {
								$balise = $elems[$i];
						?>
							<tr>
								<td><input type="text" value="<?=$balise['name']?>" name="<?="name-" . $balise['name']?>"></td>
								<td><input type="text" value="<?=$balise['type']?>" readonly></td>
								<td><input type="text" value="<?=$balise['parent']?>" readonly></td>
								<td><textarea><?=$balise['content']?></textarea></td>
								<td>
									<?php
										if ($balise['type'] == "input") {
									?>
										<label>Readonly :</label>
										<input type="checkbox" name="<?="readonly-" . $balise['name']?>">
										<input type="text" placeholder="Placeholder..." value="<?=$balise['placeholder']?>">
										<?php
										
										?>
										<?php
										
										?>
										<input type="text" placeholder="Value..." value="<?=$balise['value']?>">
									<?php
										}
									?>
								</td>
								<td>
									<select name="<?="typeAddChild-" . $balise['name']?>">
										<?=$ep->getSelectAdded($balise['type'])?>
									</select>
									<input type="submit" value="Add Child" name="<?="addChild-" . $balise['name']?>">
								</td>
								<td>
									<input type="submit" value="Save" name="<?="save-" . $balise['name']?>">
									<?php
										if ($balise['name'] != "body") {
									?>
										<input type="submit" value="Delete" name="<?="delete-" . $balise['name']?>">
									<?php
										}
									?>
									<input type="submit" value="Reset" name="<?="reset-" . $balise['name']?>">
								</td>
							</tr>
						<?php
							}
						?>
					</tbody>
				</table>
			</div>
			<div class="backend">
				<!--
				<div class="leftBackend">
					<h3>CSS</h3>
					<?php
						if ($cssJs['css'] != NULL) {
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
						if ($cssJs['js'] != NULL) {
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
				-->
			</div>
		</form>
		<form class="formCreatePage" method="POST">
            <picture>
                <img src="<?=$hlp->getMainUrl() . "/" . $cf->getValueFromKeyConf($cf->getFilesConfig(), "main_icon_png")?>">
            </picture>
            <h3>Create new element</h3>
            <div class="whiteDiv">
                <input type="text" placeholder="Name element..." name="newName" required>
				<input type="text" placeholder="Class name..." name="newClass" required>
				<input type="text" placeholder="Content..." name="newContent">
                <select name="selectTypeElement" required>
                    <?php
                        echo $ep->getSelectAdded("div");
                    ?>
                </select>
                <input type="submit" value="Create" name="newElement">
                <?php
                    if (isset($_SESSION['newElementError'])) {
                ?>
                    <p class="errorMsg" style="background-color: rgba(255, 0, 0, 0.514);"><?=$_SESSION['newElementError']?></p>
                <?php
                    }
                ?>
            </div>
            <p>Vous pouvez créer d'autres pages internet en remplissant ce formulaire</p>
        </form>
		<?php
			} else {
		?>
			<h1>Page does not exists</h1>
			<a href="<?=$hlp->getMainUrl() . "/KW/manager"?>">Back to main page</a>
		<?php
			}
		?>
    </body>
</html>
