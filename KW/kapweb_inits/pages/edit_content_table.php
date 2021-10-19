<?php
    if ($hlp->isConnectedSu() == false)
        header("location: " . $hlp->getMainUrl() . "/KW&no_connected");
    if (!isset($_SESSION['tableTarget']))
        header("location: " . $hlp->getMainUrl() . "/KW/manager&no_table_target");
    $intels = $hlp->getTable($_SESSION['tableTarget']);
    if (count($intels) <= 0)
        header("location: " . $hlp->getMainUrl() . "/KW/manager&table_taregt_empty1");
    $tableRows = $hlp->getRowsTable($_SESSION['tableTarget']);
    $args = explode(",", $intels['args']);
?>
<html  lang="<?=$_SESSION['language']?>">
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
				<script type="text/javascript" src="<?=$_SESSION['jsPath']?>">initHeightSize();</script>
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
        <a href="<?=$hlp->getMainUrl() . "/KW/manager"?>">Back to manager</a>
        <table id="tblStocks" class="showExisting">
            <thead>
                <tr>
                    <?php
                        for($i = 0; $i < count($args); $i++) {
                            if (strlen($args[$i]) > 0) {
                    ?>
                        <th><?=$args[$i]?></th>
                    <?php
                            }
                        }
                    ?>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    for ($i = 0; $i < count($tableRows); $i++) {
                ?>
                    <tr>
                        <?php
                            for ($x = 0; $x < count($args); $x++) {
                                if (strlen($args[$x]) > 0) {
                        ?>
                            <td><?=$tableRows[$i][$args[$x]]?></td>
                        <?php
                                }
                            }
                        ?>
                    </tr>
                <?php
                    }
                ?>
            </tbody>
        </table><!--
        <form>
            <label>Ajouter une nouvelle valeur</label>
        </form>-->
    </body>
</html>