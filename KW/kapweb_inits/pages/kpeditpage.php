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
	$elems = $ep->getHtmlEditor();
	if (isset($_POST['newElement'])) {
		$content = "";
		$class = "";
		if (isset($_POST['newContent'])) {
			$content = $_POST['newContent'];
		}
		if (isset($_POST['newClass'])) {
			$class = $_POST['newClass'];
		}
		$ep->addElement($_POST['newName'], $_POST['selectTypeElement'], "body", $class, $content);
		header("location: " . $hlp->getMainUrl() . "/KW/editPage/" . $_SESSION['urlEdit']);
	}
	for ($i = 0; $i < count($elems); $i++) {
		$balise = $elems[$i];
		if (isset($_POST['delete-' . $balise['name']])) {
			$ep->deleteElement($balise['name']);
			header("location: " . $hlp->getMainUrl() . "/KW/editPage/" . $_SESSION['urlEdit']);
		}
		if (isset($_POST['reset-' . $balise['name']])) {
			$ep->resetElement($balise['name']);
			header("location: " . $hlp->getMainUrl() . "/KW/editPage/" . $_SESSION['urlEdit']);
		}
		if (isset($_POST['addChild-' . $balise['name']])) {
			if (isset($_POST['addChildName-' . $balise['name']]) && isset($_POST['typeAddChild-' . $balise['name']])) {
				$ep->addElement($_POST['addChildName-' . $balise['name']], $_POST['typeAddChild-' . $balise['name']], $balise['name']);
				header("location: " . $hlp->getMainUrl() . "/KW/editPage/" . $_SESSION['urlEdit']);
			}
		}
		if (isset($_POST['save-' . $balise['name']])) {
			$contentChg = $balise['content'];
			$class = $balise['class'];
			$name = $balise['name'];
			$ph = "";
			$ival = "";
			$ireado = "";
			$src = "";
			$link = "";
			$target = "";
			if (isset($_POST['imgSrc-' . $balise['name']])) {
				$src = $_POST['imgSrc-' . $balise['name']];
			}
			if (isset($_POST['chgLink-' . $balise['name']])) {
				$link = $_POST['chgLink-' . $balise['name']];
			}
			if (isset($_POST["chgTarget-" . $balise['name']])) {
				$target = $_POST["chgTarget-" . $balise['name']];
			}
			if (isset($_POST["chgContent-" . $balise['name']])) {
				$contentChg = $_POST["chgContent-" . $balise['name']];
			}
			if (isset($_POST["chgClass-" . $balise['name']])) {
				$class = $_POST["chgClass-" . $balise['name']];
			}
			if (isset($_POST['name-' . $balise['name']])) {
				$name = $_POST['name-' . $balise['name']];
			}
			if ($balise['type'] == "input") {
				if (isset($_POST['chgPh-' . $balise['name']])) {
					$ph = $_POST['chgPh-' . $balise['name']];
				} else {
					$ph = $balise['placeholder'];
				}
				if (isset($_POST['readonly-' . $balise['name']])) {
					$ireado = $_POST['readonly-' . $balise['name']];
				} else {
					$ph = $balise['placeholder'];
				}
				if (isset($_POST['chgIVal-' . $balise['name']])) {
					$ival = $_POST['chgIVal-' . $balise['name']];
				} else {
					$ph = $balise['placeholder'];
				}
			}
			$arrUpdate = array(
				"name" => $name,
				"type" => $balise['type'],
				"content" => $contentChg,
				"class" => $class,
				"readonly" => $ireado,
				"placeholder" => $ph,
				"value" => $ival,
				"src" => $src,
				"link" => $link,
				"target" => $target,
			);
			$ep->updateElement($balise['name'], $arrUpdate);
			header("location: " . $hlp->getMainUrl() . "/KW/editPage/" . $_SESSION['urlEdit']);
		}
	}
	if (isset($_POST['saveCssJs'])) {
		$arr = array(
			"css" => $_POST['cssEdit'],
			"js" => $_POST['jsEdit']
		);
		$ep->saveCssJs($arr);
		header("location: " . $hlp->getMainUrl() . "/KW/editPage/" . $_SESSION['urlEdit']);
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
			<h1><a class="backButton" href="<?=$hlp->getMainUrl() . "/KW/manager"?>"><i class="far fa-caret-square-left"></i></a>Edition</h1>
			<div class="content">
				<table id="tblStocks" class="tablePage">
					<caption><h2>Page Elements</h2></caption>
					<thead>
						<tr>
							<th><p>Name (editable)</p></th>
							<th><p>Type</p></th>
							<th><p>Parent</p></th>
							<th><p>Class</p></th>
							<th><p>Content (editable)</p></th>
							<th><p>Special Edit (editable)</p></th>
							<th><p>Add Child</p></th>
							<th><p>Changes</p></th>
						</tr>
					</thead>
					<tbody id="bodyTableEdit">
						<?php
							for ($i = 0; $i < count($elems); $i++) {
								$balise = $elems[$i];
						?>
							<tr>
								<td><input type="text" value="<?=$balise['name']?>" name="<?="name-" . $balise['name']?>"></td>
								<td><input type="text" value="<?=$balise['type']?>" readonly></td>
								<td><input type="text" value="<?=$balise['parent']?>" readonly></td>
								<td><input type="text" value="<?=$balise['class']?>" name="<?="chgClass-" . $balise['name']?>"></td>
								<td><textarea name="<?="chgContent-" . $balise['name']?>"><?=$balise['content']?></textarea></td>
								<td>
									<?php
										if ($balise['type'] == "input") {
									?>
										<label>Readonly :</label>
										<input type="checkbox" name="<?="readonly-" . $balise['name']?>">
										<input type="text" placeholder="Placeholder..." value="<?=$balise['placeholder']?>" name="<?="chgPh-" . $balise['name']?>">
										<input type="text" placeholder="Value..." value="<?=$balise['value']?>" name="<?="chgIVal-" . $balise['name']?>">
									<?php
										} else if ($balise['type'] == "img") {
									?>
										<input type="text" placeholder="Source of image..." value="<?=str_replace("\"", "'", $balise['src'])?>" name="<?="imgSrc-" . $balise['name']?>">
									<?php
										} else if ($balise['type'] == "source") {
									?>
										<input type="text" placeholder="Source..." value="<?=str_replace("\"", "'", $balise['src'])?>" name="<?="imgSrc-" . $balise['name']?>">
									<?php
										}else if ($balise['type'] == "a") {
									?>
										<input type="text" placeholder="Link..." value="<?=str_replace("\"", "'", $balise['link'])?>" name="<?="chgLink-" . $balise['name']?>">
										<input type="text" placeholder="Target..." value="<?=str_replace("\"", "'", $balise['target'])?>" name="<?="chgTarget-" . $balise['name']?>">
									<?php
										}
									?>
								</td>
								<td>
									<?php
										if ($ep->isBaliseAutoClose($balise['type']) == false) {
									?>
									<input type="text" placeholder="Name..." name="<?="addChildName-" . $balise['name']?>">
									<select name="<?="typeAddChild-" . $balise['name']?>">
										<?=$ep->getSelectAdded($balise['type'])?>
									</select>
									<input type="submit" value="Add Child" name="<?="addChild-" . $balise['name']?>">
									<?php
										}
									?>
								</td>
								<td>
									<?php
										if ($balise['name'] != "body") {
									?>
										<input type="submit" value="Save" name="<?="save-" . $balise['name']?>">
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
		</form>
		<form class="formCreatePage" method="POST">
            <picture>
                <img src="<?=$hlp->getMainUrl() . "/" . $cf->getValueFromKeyConf($cf->getFilesConfig(), "main_icon_png")?>">
            </picture>
            <h3>Create new element</h3>
            <div class="whiteDiv">
                <input type="text" placeholder="Name element..." name="newName" required>
				<input type="text" placeholder="Class name..." name="newClass">
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
		<div class="backend">
			<form method="POST">
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
				<input type="submit" value="Save CSS JS" name="saveCssJs">
			</form>
		</div>
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
