<?php
    global $ext, $cf;
    if (isset($_POST['validEmail-sendContactMe'])) {
        $nemail = $_POST['emailContactMe'];
        $cf->addValueFormKeyConf($ext->getConfigFileExtension("Standar extension"), "emailContactMe", $nemail);
        $cf->addValueFormKeyConf($ext->getConfigFileExtension("Standar extension"), "emailContactMeFront", "\"" . $nemail . "\"");
    }
?>