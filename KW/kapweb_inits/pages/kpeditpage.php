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
			$arrUpdate = $ep->updateArraySaveFromExtensions($arrUpdate, $balise);
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
			</div>
			<div class="editMenuContent">
				<?=$editMenus?>
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