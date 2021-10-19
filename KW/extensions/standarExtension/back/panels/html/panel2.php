<?php
    global $db, $ext, $hlp, $ep, $cf;

    $idPanel = $cf->getValueFromKeyConf($ext->getManagerUiExtension("Standar extension"), "manager-ui-pannel2-id");
    $configPathExtension = $ext->getConfigFileExtension("Standar extension");

?>
<div class="contextDev" id="<?=$idPanel?>">

</div>