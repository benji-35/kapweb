<?php
    $configPathExtension = $ext->getConfigFileExtension("Standar extension");
    $nb_forms= $cf->getValueFromKeyConf($configPathExtension, "nb-forms");

    for ($i = 0; $i < $nb_forms; $i++) {
        if (isset($_POST["showForm-" . $i])) {
            $_SESSION['standarExtension-formShow'] = $i;
            header("Refresh:" . 0, $_SERVER['PHP_SELF']);
        }
    }
?>