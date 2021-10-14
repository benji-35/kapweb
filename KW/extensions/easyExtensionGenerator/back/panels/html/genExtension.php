<?php
    global $db, $ext, $hlp, $ep, $cf;
    $idPanel = $cf->getValueFromKeyConf($ext->getManagerUiExtension("Extension Generator"), "manager-ui-pannel1-id");
?>
<div class="contextDev" id="<?=$idPanel?>">
    <div class="easyExtensionGen-main">
        <h1>Easy extension Generator</h1>
        <form method="POST" class="easyExtensionGen-formExtenGenerator">
            <h1>Generator</h1>
            <div class="easyExtensionGen-whitespacing">
                <input class="easyExtensionGen-inputIntels" type="text" placeholder="Extension name..." name="extenName">
                <input class="easyExtensionGen-inputIntels" type="text" placeholder="Your name..." name="authorExt">
                <div class="easyExtensionGen-alignCheck">
                    <label>Is Back Extension</label>
                    <input type="checkbox" name="extIsBack" id="easyExtensionGen-isBack" checked hidden>
                    <button title="is a back extension ?" id="easyExtensionGen-isBack-btn" type="button" onclick="checkCategory('easyExtensionGen-isBack-btn', 'easyExtensionGen-isBack')">
                        <box-icon type='solid' color='green' size='lg' name='message-square-check'></box-icon>
                    </button>
                </div>
                <div class="easyExtensionGen-alignCheck">
                    <label>Is Front Extension</label>
                    <input type="checkbox" name="extIsFront" id="easyExtensionGen-isFront" hidden>
                    <button title="is a front extension ?" id="easyExtensionGen-isFront-btn" type="button" onclick="checkCategory('easyExtensionGen-isFront-btn', 'easyExtensionGen-isFront')">
                        <box-icon color='red' size='lg' name='message-square-x' type='solid' ></box-icon>
                    </button>
                </div>
                <div class="easyExtensionGen-alignCheck">
                    <label>Is Database Extension</label>
                    <input type="checkbox" name="extIsDb" id="easyExtensionGen-isDb" hidden>
                    <button title="is a database extension ?" id="easyExtensionGen-isDb-btn" type="button" onclick="checkCategory('easyExtensionGen-isDb-btn', 'easyExtensionGen-isDb')">
                        <box-icon color='red' size='lg' name='message-square-x' type='solid' ></box-icon>
                    </button>
                </div>
                <input type="number" value="0" id="nbDependencies-GeneratorDep-devcompagnie" hidden name="nbDependencies">
                <div id="dependencies-GeneratorDep-devcompagnie">

                </div>
                <button class="easyExtensionGen-btnAddDep" type="button" onclick="addDep()">Add Dependency</button>
                <button class="easyExtensionGen-btnGenerate" type="submit" name="startGenerationExtension-Devcompagnie" title="Generate Extension"><box-icon name='code-curly'></box-icon> Generate</button>
            </div>
            <p>If your extension does not appear, it's may be because the name is already used for another extension.</p>
        </form>
    </div>
</div>