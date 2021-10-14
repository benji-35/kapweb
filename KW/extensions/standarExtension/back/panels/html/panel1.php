<?php
    global $db, $ext, $hlp, $ep, $cf;

    $idPanel = $cf->getValueFromKeyConf($ext->getManagerUiExtension("Standar extension"), "manager-ui-pannel1-id");
    $configPathExtension = $ext->getConfigFileExtension("Standar extension");

?>
<div class="contextDev" id="<?=$idPanel?>">
    <div class="mainPanel1-standarExtension">
        <h1>Standar Extension</h1>
        <table id="tbl-standarExtension">
            <caption>Standar Extension Element</caption>
            <thead>
                <tr>
                    <th><p>Name</p></th>
                    <th><p>Description</p></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><p>Contact Me</p></td>
                    <td><p>With contact me element, you can add easly and quickly a contact me form</p></td>
                </tr>
            </tbody>
        </table>
        <div class="description-StandarExtension">
            <h2>Description of standar Extension</h2>
            <p>Standar extension is the first extension of kapweb cms. This extension will must help you to create and design your own website. This extension is created by Devcompagnie, the creator of kapweb.</p>
        </div>
        <div class="configContactMe-StandarExtension">
            <h3>Config the contact me</h3>
            <form method="POST">
                <input type="email" placeholder="Email received the contact me..." value="<?=$cf->getValueFromKeyConf($configPathExtension,'$cf-emailContactMe')?>" name="emailContactMe" class="emailContactMe">
                <input type="submit" value="Valide" name="validEmail-sendContactMe" class="validEmail-sendContactMe">
            </form>
        </div>
    </div>
</div>
