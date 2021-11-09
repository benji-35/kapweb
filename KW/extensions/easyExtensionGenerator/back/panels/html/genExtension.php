<?php
    global $db, $ext, $hlp, $ep, $cf;
    $extName = "Extension Generator";
    $idPanel = $cf->getValueFromKeyConf($ext->getManagerUiExtension($extName), "manager-ui-pannel1-id");
?>
<div class="contextDev" id="<?=$idPanel?>">
    <div class="easyExtensionGen-main">
        <h1><?=$ext->getLangaugeValue($extName, "titlePage")?></h1>
        <form method="POST" class="easyExtensionGen-formExtenGenerator">
            <h1><?=$ext->getLangaugeValue($extName, "w-generator")?></h1>
            <div class="easyExtensionGen-whitespacing">
                <input class="easyExtensionGen-inputIntels" type="text" placeholder="<?=$ext->getLangaugeValue($extName, "extName")?>" name="extenName">
                <input class="easyExtensionGen-inputIntels" type="text" placeholder="<?=$ext->getLangaugeValue($extName, "authName")?>" name="authorExt">
                <div class="easyExtensionGen-alignCheck">
                    <label><?=$ext->getLangaugeValue($extName, "isBackExt")?></label>
                    <input type="checkbox" name="extIsBack" id="easyExtensionGen-isBack" checked hidden>
                    <button title="<?=$ext->getLangaugeValue($extName, "isBackTitle")?>" id="easyExtensionGen-isBack-btn" type="button" onclick="checkCategory('easyExtensionGen-isBack-btn', 'easyExtensionGen-isBack')">
                        <box-icon type='solid' color='green' size='lg' name='message-square-check'></box-icon>
                    </button>
                </div>
                <div class="easyExtensionGen-alignCheck">
                    <label><?=$ext->getLangaugeValue($extName, "isFrontExt")?></label>
                    <input type="checkbox" name="extIsFront" id="easyExtensionGen-isFront" hidden>
                    <button title="<?=$ext->getLangaugeValue($extName, "isFrontTitle")?>" id="easyExtensionGen-isFront-btn" type="button" onclick="checkCategory('easyExtensionGen-isFront-btn', 'easyExtensionGen-isFront')">
                        <box-icon color='red' size='lg' name='message-square-x' type='solid' ></box-icon>
                    </button>
                </div>
                <div class="easyExtensionGen-alignCheck">
                    <label><?=$ext->getLangaugeValue($extName, "isDbExt")?></label>
                    <input type="checkbox" name="extIsDb" id="easyExtensionGen-isDb" hidden>
                    <button title="<?=$ext->getLangaugeValue($extName, "isDbTitle")?>" id="easyExtensionGen-isDb-btn" type="button" onclick="checkCategory('easyExtensionGen-isDb-btn', 'easyExtensionGen-isDb')">
                        <box-icon color='red' size='lg' name='message-square-x' type='solid' ></box-icon>
                    </button>
                </div>
                <input type="number" value="0" id="nbDependencies-GeneratorDep-devcompagnie" hidden name="nbDependencies">
                <div id="dependencies-GeneratorDep-devcompagnie">

                </div>
                <button class="easyExtensionGen-btnAddDep" type="button" onclick="addDep('<?=$ext->getLangaugeValue($extName, "placeholderDepName")?>', '<?=$ext->getLangaugeValue($extName, "w-delete")?>')"><?=$ext->getLangaugeValue($extName, "addDep")?></button>
                <button class="easyExtensionGen-btnGenerate" type="submit" name="startGenerationExtension-Devcompagnie" title="Generate Extension"><box-icon name='code-curly'></box-icon><?=" " . $ext->getLangaugeValue($extName, "w-generate")?></button>
            </div>
            <p><?=$ext->getLangaugeValue($extName, "descripExtGen")?></p>
        </form>
    </div>
</div>