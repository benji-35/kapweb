<?php
	global $ep, $hlp;
	$cssJs = array(
		"css" => NULL,
		"js" => NULL
	);
	$pageExists = true;
	if ($hlp->isConnectedSu() == false)
		header("location: " . $hlp->getMainUrl() . "/KW");
	if (!isset($_SESSION['suemail']) || !isset($_SESSION['supwd']) || !isset($_SESSION['urlEdit']))
		header("location: " . $hlp->getMainUrl() . "/KW/manager");
	if ($hlp->pageExists($_SESSION['urlEdit'])) {
		$_SESSION['editName'] = $_SESSION['urlEdit'];
		$cssJs = $ep->getAllCssJsContent($_SESSION['editName']);
	} else {
		$pageExists = false;
	}
	if ($hlp->pageExists($_SESSION['urlEdit'])) {
		$elems = $ep->getHtmlEditor();
	} else {
		$elems = array();
	}
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
		$nameNoSpacing = str_replace(" ", "_", $balise['name']);
		if (isset($_POST['delete-' . $nameNoSpacing])) {
			$ep->deleteElement($balise['name']);
			header("location: " . $hlp->getMainUrl() . "/KW/editPage/" . $_SESSION['urlEdit']);
		}
		if (isset($_POST['reset-' . $nameNoSpacing])) {
			$ep->resetElement($balise['name']);
			header("location: " . $hlp->getMainUrl() . "/KW/editPage/" . $_SESSION['urlEdit']);
		}
		if (isset($_POST['addChild-' . $nameNoSpacing])) {
			if (isset($_POST['addChildName-' . $nameNoSpacing]) && isset($_POST['typeAddChild-' . $nameNoSpacing])) {
				$ep->addElement($_POST['addChildName-' . $nameNoSpacing], $_POST['typeAddChild-' . $nameNoSpacing], $balise['name'], "", "", $_POST['positionAddChild-' . $nameNoSpacing]);
				header("location: " . $hlp->getMainUrl() . "/KW/editPage/" . $_SESSION['urlEdit']);
			}
		}
		if (isset($_POST['newClass-' . $nameNoSpacing])) {
			if (isset($_POST['nameNClass-' . $nameNoSpacing])) {
				$ep->addClassElement($balise, $_POST['nameNClass-' . $nameNoSpacing]);
				header("location:");
			}
		}
		if (isset($_POST['save-' . $nameNoSpacing])) {
			$contentChg = $balise['content'];
			$class = $balise['class'];
			$name = $balise['name'];
			$ph = "";
			$ival = "";
			$ireado = "";
			$src = "";
			$link = "";
			$target = "";
			if (isset($_POST['imgSrc-' . $nameNoSpacing])) {
				$src = $_POST['imgSrc-' . $nameNoSpacing];
			}
			if (isset($_POST['chgLink-' . $nameNoSpacing])) {
				$link = $_POST['chgLink-' . $nameNoSpacing];
			}
			if (isset($_POST["chgTarget-" . $nameNoSpacing])) {
				$target = $_POST["chgTarget-" . $nameNoSpacing];
			}
			if (isset($_POST["chgContent-" . $nameNoSpacing])) {
				$contentChg = $_POST["chgContent-" . $nameNoSpacing];
			}
			if (isset($_POST["chgClass-" . $nameNoSpacing])) {
				$class = $_POST["chgClass-" . $nameNoSpacing];
			}
			if (isset($_POST['name-' . $nameNoSpacing])) {
				$name = $_POST['name-' . $nameNoSpacing];
			}
			if ($balise['type'] == "input") {
				if (isset($_POST['chgPh-' . $nameNoSpacing])) {
					$ph = $_POST['chgPh-' . $nameNoSpacing];
				} else {
					$ph = $balise['placeholder'];
				}
				if (isset($_POST['readonly-' . $nameNoSpacing])) {
					$ireado = $_POST['readonly-' . $nameNoSpacing];
				} else {
					$ph = $balise['placeholder'];
				}
				if (isset($_POST['chgIVal-' . $nameNoSpacing])) {
					$ival = $_POST['chgIVal-' . $nameNoSpacing];
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
			$arrUpdate = $ep->updateArraySaveFromExtensions($arrUpdate, $balise);
			$ep->updateElement($balise['name'], $arrUpdate);
			header("location: " . $hlp->getMainUrl() . "/KW/editPage/" . $_SESSION['urlEdit']);
		}
	}
	if (isset($_POST['saveCssJs'])) {
		$arr = array(
			"css" => $cssJs['css'],
			"js" => $cssJs['js']
		);
		if (isset($_POST['cssEdit'])) {
			$arr['css'] = $_POST['cssEdit'];
		}
		if (isset($_POST['jsEdit'])) {
			$arr['js'] = $_POST['jsEdit'];
		}
		$ep->saveCssJs($arr);
		header("location: " . $hlp->getMainUrl() . "/KW/editPage/" . $_SESSION['urlEdit']);
	}

	$editMenus = $ep->getAllEditMenus($elems);
	$navEditMenu = $ep->sortElemsAndGetHtml($elems);
?>

<!DOCTYPE html>
<html  lang="<?=$_SESSION['language']?>">
    <head>
		<script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
        <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
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
		<div class="upMenu">
			<div class="contentUpMenu">
				<a href="<?=$hlp->getMainUrl() . "/KW/manager"?>" class="linkBackManager"><i class='bx bxs-left-arrow'></i></a>
				<h2 class="titleEdition">Edition</h2>
			</div>
		</div>
		<div class="mainContent">
			<div class="navMenuEdit">
				<?=$navEditMenu?>
				<button id="btnNavBarCss" style="width: 100%;" class="btnNavElem" onclick="openEditMenu('editMenuCss')"><i class='bx bxl-css3 textNavMenu'></i><p class="textNavMenu">CSS</p></button>
				<button id="btnNavBarJs" style="width: 100%;" class="btnNavElem" onclick="openEditMenu('editMenuJs')"><i class='bx bxl-nodejs textNavMenu'></i><p class="textNavMenu">JS</p></button>
			</div>
			<div class="editMenuContent">
				<?=$editMenus?>
				<div id="editMenuCss" class="editMenu" style="display: none;">
					<form method="POST">
						<div class="editBarElement">
							<h3>CCS</h3>
							<button class="btnEditBarElement" name="saveCssJs" title="<?=$hlp->getLangWorldMainFile("w-save", "Save")?>"><i class='bx bxs-save bx-sm'></i></button>
							<button class="btnEditBarElement" type="button" onclick="resetJsCssContent('cssEdit')" title="<?=$hlp->getLangWorldMainFile("w-reset", "Reset")?>"><i class='bx bx-reset bx-sm'></i></button>
						</div>
						<textarea id="cssEdit" name="cssEdit" class="jsCssTextArea"><?=$cssJs['css']?></textarea>
					</form>
				</div>
				<div id="editMenuJs" class="editMenu" style="display: none;">
					<form method="POST">
						<div class="editBarElement">
							<h3>JS</h3>
							<button class="btnEditBarElement" name="saveCssJs" title="<?=$hlp->getLangWorldMainFile("w-save", "Save")?>"><i class='bx bxs-save bx-sm'></i></button>
							<button class="btnEditBarElement" type="button" onclick="resetJsCssContent('jsEdit')" title="<?=$hlp->getLangWorldMainFile("w-reset", "Reset")?>"><i class='bx bx-reset bx-sm'></i></button>
						</div>
						<textarea id="jsEdit" name="jsEdit" class="jsCssTextArea"><?=$cssJs['js']?></textarea>
					</form>
				</div>
			</div>
		</div>
		<?php
			} else {
		?>
			<div class="errorDoNotExists">
				<picture>
                    <img src="<?=$hlp->getMainUrl() . "/" . $cf->getValueFromKeyConf($cf->getFilesConfig(), "main_icon_png")?>">
                </picture>
				<h1>Page does not exists</h1>
				<div class="whiteSPacingError">
					<h3>Back to manager page</h3>
					<a href="<?=$hlp->getMainUrl() . "/KW/manager"?>">Manager</a>
				</div>
				<p>The page you want to edit does not exists. Please retry with a page that exists.</p>
			</div>
		<?php
			}
		?>
    </body>
</html>
