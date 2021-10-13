<?php
    global $cf, $ext;
    if (isset($_POST["sendContactMe-\$kw['contactMe-nameSender']"])) {
        $cfExten = $ext->getConfigFileExtension("Standar extension");
        $emailTarget = $cf->getValueFromKeyConf($cfExten, "emailContactMeFront");
        mail($emailTarget, "Kapweb - contactMe", $_POST['messageToContactME']);
    }
?>