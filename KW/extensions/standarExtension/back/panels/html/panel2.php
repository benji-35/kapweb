<?php
    global $db, $ext, $hlp, $ep, $cf;

    $idPanel = $cf->getValueFromKeyConf($ext->getManagerUiExtension("Standar extension"), "manager-ui-pannel2-id");
    $configPathExtension = $ext->getConfigFileExtension("Standar extension");
    $configFront = $ext->getManagerUiExtension("Standar extension");
    $nb_forms= $cf->getValueFromKeyConf($configPathExtension, "nb-forms");
?>
<div class="contextDev" id="<?=$idPanel?>">
    <div class="content-FormCreation-StandarExtension">
        <div class="navMenu-StandarExtension-formCreation">
            <form method="POST" class="displayAllAvailablesForms-standarExtension">
                <?php
                    if ($nb_forms != "") {
                        for ($i = 0; $i < $nb_forms; $i++) {
                ?>
                    <button name="<?="showForm-" . $i?>" class="btnStandarExtension-selectForm">
                        <?=$cf->getValueFromKeyConf($configPathExtension, "form-" . $i . "-name")?>
                    </button>
                <?php
                        }
                    }
                ?>
            </form>
        </div>
        <div class="contentMenu-StandarExtension-formCreation">
            <?php
                if (isset($_SESSION['standarExtension-formShow'])) {
            ?>
            <?php
                } else {
            ?>
            <?php
                }
            ?>
        </div>
    </div>
</div>