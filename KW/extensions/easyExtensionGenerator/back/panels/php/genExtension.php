<?php
    global $ext, $cf, $hlp;

    if (isset($_POST['startGenerationExtension-Devcompagnie'])) {
        $name = $_POST['extenName'];
        $author = $_POST['authorExt'];
        $isBack = "false";
        if (isset($_POST['extIsBack'])) {
            $isBack = "true";
        }
        $isFront = "false";
        if (isset($_POST['extIsFront'])) {
            $isFront = "true";
        }
        $isDb = "false";
        if (isset($_POST['extIsDb'])) {
            $isDb = "true";
        }
        $dependencies = "";
        for ($i = 0; $i <= $_POST['nbDependencies']; $i++) {
            if (isset($_POST['nDep-' . $i])) {
                if ($dependencies == "") {
                    $dependencies = $_POST['nDep-' . $i];
                } else {
                    $dependencies .= "," . $_POST['nDep-' . $i];
                }
            }
        }
        $exts = $ext->getExtensionList();
        for ($i = 0; $i < count($exts); $i++) {
            if ($exts[$i]['name'] == $name) {
                //le nom est déjà utlisé donc on abandonne
                return;
            }
        }
        $extConf_content = "name=" . $name . "\n"
            . "description=\n"
            . "author=" . $author . "\n"
            . "version=1.0\n"
            . "lang=en\n"
            . "\n"
            . "#is front extension\n"
            . "isFront=" . $isFront . "\n"
            . "# is back extension\n"
            . "isBack=" . $isBack . "\n"
            . "# is database extension\n"
            . "isDbExtension=" . $isDb . "\n"
            . "\n"
            . "dependencies=" . $dependencies . "\n"
            . "\n"
            . "use=false\n"
            . "\n"
            . "icon=\n"
            . "\n"
            . "This file was generatded by easy generator extension\n"
            . "Made by © Devcompagnie\n";

        $back_base_content = "#\n"
            . "# Here you can add button in manager ui and set his interface\n"
            . "#\n"
            . "# manager-ui=number of button to add\n"
            . "# manager-ui-button[number]=text in button\n"
            . "# manager-ui-button[number]-cat=categorie where button will be placed\n"
            . "# manager-ui-button[number]-catLogo=Logo placed befaore text in button\n"
            . "# manager-ui-pannel[number]-html= name of file for html page in html directory\n"
            . "# manager-ui-pannel[number]-css= name of file for css page in css directory\n"
            . "# manager-ui-pannel[number]-php= name of file for php page in php directory\n"
            . "# manager-ui-pannel[number]-js= name of file for js page in js directory\n"
            . "#\n\n"
            . "manager-ui=0";

        $front_base_content = "# All elements can be added in page editor :\n\n"
            . "front-elements=\n\n"
            . "#\n#[name of element]=[nb specifics variables]\n"
            . "#[name of element]-name=[name in page edit]\n"
            . "#[name of element]-dependencies=[dependency1],[dependency2],...\n"
            . "#[name of element]-front= [true/false] -> know if we need to generate html or not\n"
            . "#[name of element]-back=[true/false] -> know if we need to generate php or not\n"
            . "#[name of element]-css=[true/false] -> know if we need to generate css or not\n"
            . "#[name of element]-vars=[var name 1],[var name 2],...\n"
            . "#\n";

        $mainPathExt = "KW/extensions/" . str_replace(" ", "_", $name);
        if (file_exists($mainPathExt)) {
            return;
        }
        mkdir($mainPathExt, 0777, false);
        mkdir($mainPathExt . "/ressources", 0777, false);
        if (isset($_POST['extIsFront'])) {
            mkdir($mainPathExt . "/front", 0777, false);
            mkdir($mainPathExt . "/front/elements", 0777, false);
            $f = fopen($mainPathExt . "/front/front-elements.conf", "w+");
            fwrite($f, $front_base_content, strlen($front_base_content));
            fclose($f);
        }
        if (isset($_POST['extIsBack'])) {
            mkdir($mainPathExt . "/back", 0777, false);
            mkdir($mainPathExt . "/back/panels", 0777, false);
            mkdir($mainPathExt . "/back/panels/css", 0777, false);
            mkdir($mainPathExt . "/back/panels/html", 0777, false);
            mkdir($mainPathExt . "/back/panels/js", 0777, false);
            mkdir($mainPathExt . "/back/panels/php", 0777, false);
            $f = fopen($mainPathExt . "/back/manager-ui.conf", "w+");
            fwrite($f, $back_base_content, strlen($back_base_content));
            fclose($f);
        }
        $currExtListing = $cf->getValueFromKeyConf("KW/extensions/ext.conf", "list-ext");
        if ($currExtListing == "") {
            $currExtListing = str_replace(" ", "_", $name);
        } else {
            $currExtListing .= "," . str_replace(" ", "_", $name);
        }
        $cf->addValueFormKeyConf("KW/extensions/ext.conf", "list-ext", $currExtListing);
        $f = fopen($mainPathExt . "/ext.conf", "w+");
        fwrite($f, $extConf_content, strlen($extConf_content));
        fclose($f);
        header("location: " . $hlp->getMainUrl() . "/KW/manager");
    }
?>
