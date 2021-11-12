<?php
    global $db, $ext, $hlp, $ep, $cf;
    $extName = "Extension Generator";
    $idPanel = $cf->getValueFromKeyConf($ext->getManagerUiExtension($extName), "manager-ui-pannel1-id");
?>
<div class="contextDev" id="<?=$idPanel?>">
    <div class="easyExtensionGen-main">
        <h1><?=$ext->getLangaugeValue($extName, "titlePage")?></h1>
        <form method="POST" class="easyExtensionGen-formExtenGenerator">
            <div id="easyExtension-mainDiv" class="pannelMainDiv">
                <h1><?=$ext->getLangaugeValue($extName, "w-generator")?></h1>
                <div class="easyExtensionGen-whitespacing">
                    <input class="easyExtensionGen-inputIntels" type="text" placeholder="<?=$ext->getLangaugeValue($extName, "extName")?>" name="extenName">
                    <input class="easyExtensionGen-inputIntels" type="text" placeholder="<?=$ext->getLangaugeValue($extName, "authName")?>" name="authorExt">
                    <div class="easyExtensionGen-alignCheck">
                        <label><?=$ext->getLangaugeValue($extName, "isBackExt")?></label>
                        <input type="checkbox" name="extIsBack" id="easyExtensionGen-isBack" checked hidden>
                        <button title="<?=$ext->getLangaugeValue($extName, "isBackTitle")?>" id="easyExtensionGen-isBack-btn" type="button" onclick="checkCategory('easyExtensionGen-isBack-btn', 'easyExtensionGen-isBack', 'btnAccess-a-easyExtension')">
                            <box-icon type='solid' color='green' size='lg' name='message-square-check'></box-icon>
                        </button>
                        <button type="button" id="btnAccess-a-easyExtension" title="<?=$ext->getLangaugeValue($extName, "optionAcces")?>" onclick="goToIntels('easyExtension-backDiv')"><i class='bx bxs-right-arrow'></i></button>
                    </div>
                    <div class="easyExtensionGen-alignCheck">
                        <label><?=$ext->getLangaugeValue($extName, "isFrontExt")?></label>
                        <input type="checkbox" name="extIsFront" id="easyExtensionGen-isFront" hidden>
                        <button title="<?=$ext->getLangaugeValue($extName, "isFrontTitle")?>" id="easyExtensionGen-isFront-btn" type="button" onclick="checkCategory('easyExtensionGen-isFront-btn', 'easyExtensionGen-isFront', 'none')">
                            <box-icon color='red' size='lg' name='message-square-x' type='solid' ></box-icon>
                        </button>
                    </div>
                    <div class="easyExtensionGen-alignCheck">
                        <label><?=$ext->getLangaugeValue($extName, "isDbExt")?></label>
                        <input type="checkbox" name="extIsDb" id="easyExtensionGen-isDb" hidden>
                        <button title="<?=$ext->getLangaugeValue($extName, "isDbTitle")?>" id="easyExtensionGen-isDb-btn" type="button" onclick="checkCategory('easyExtensionGen-isDb-btn', 'easyExtensionGen-isDb', 'none'); //'btnAccess-c-easyExtension'">
                            <box-icon color='red' size='lg' name='message-square-x' type='solid' ></box-icon>
                        </button>
                        <button type="button" id="btnAccess-c-easyExtension" title="<?=$ext->getLangaugeValue($extName, "optionAcces")?>" style="display: none;" onclick="goToIntels('easyExtension-dbDiv')"><i class='bx bxs-right-arrow'></i></button>
                    </div>
                    <input type="number" value="0" id="nbDependencies-GeneratorDep-devcompagnie" hidden name="nbDependencies">
                    <div id="dependencies-GeneratorDep-devcompagnie">

                    </div>
                    <button class="easyExtensionGen-btnAddDep" type="button" onclick="addDep('<?=$ext->getLangaugeValue($extName, "placeholderDepName")?>', '<?=$ext->getLangaugeValue($extName, "w-delete")?>')"><?=$ext->getLangaugeValue($extName, "addDep")?></button>
                    <button class="easyExtensionGen-btnGenerate" type="submit" name="startGenerationExtension-Devcompagnie" title="Generate Extension"><box-icon name='code-curly'></box-icon><?=" " . $ext->getLangaugeValue($extName, "w-generate")?></button>
                </div>
                <p><?=$ext->getLangaugeValue($extName, "descripExtGen")?></p>
            </div>
            <div id="easyExtension-backDiv" class="pannelIntels-easyExtension">
                <div class="titleIntels-easyExtension-intels">
                    <button class="backIntels-easyExtension" type="button" onclick="backToMainDiv('easyExtension-backDiv')"><i class='bx bxs-left-arrow'></i></button>
                    <h2><?=$ext->getLangaugeValue($extName, 'intelsBackTitle')?></h2>
                </div>
                <div class="easyExtensionGen-whitespacing">
                    <input type="number" id="nbBackIntels-easyExt" name="nbBackIntels" value="0" hidden>
                    <input type="number" id="currNbBackIntels-easyExt" name="currNbBackIntels" value="0" hidden>
                    <table class="tableListingBackendPage-easyExtension">
                        <caption><?=$ext->getLangaugeValue($extName, 'listingBackPageTitle')?></caption>
                        <thead>
                            <tr>
                                <th class="easyExtension-nameTbaleListingBack">Nom</th>
                                <th class="easyExtension-actonsTbaleListingBack"></th>
                            </tr>
                        </thead>
                        <tbody id="easyExtension-tableListBackPages">
                            <tr>
                                <td>
                                    <button type="button" onclick="addBackPage('<?=$ext->getLangaugeValue($extName, "placeholderBPageName")?>', '<?=$ext->getLangaugeValue($extName, "w-delete")?>')" title="<?=$ext->getLangaugeValue($extName, 'addBackPageDescribe')?>"><i class='bx bx-plus-circle'></i><?=$ext->getLangaugeValue($extName, 'w-new')?></button>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="easyExtension-dbDiv" class="pannelIntels-easyExtension">
                <div class="titleIntels-easyExtension-intels">
                    <button class="backIntels-easyExtension" type="button" onclick="backToMainDiv('easyExtension-dbDiv')"><i class='bx bxs-left-arrow'></i></button>
                    <h2><?=$ext->getLangaugeValue($extName, 'intelsDbTitle')?></h2>
                </div>
                <div class="easyExtensionGen-whitespacing">
                    <input type="number" name="nbDbIntels" hidden>
                    <input type="number" name="currNbDbIntels" hidden>
                </div>
            </div>
        </form>
    </div>
</div>
