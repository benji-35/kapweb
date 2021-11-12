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

        $nbBackUis = 0;

        if ($isBack && $_POST['currNbBackIntels'] > 0) {
            $nbBackUis = $_POST['currNbBackIntels'];
        }

        $extConf_content = "name=" . $name . "\n"
            . "description=\n"
            . "author=" . $author . "\n"
            . "version=1.0\n"
            . "lang=en,fr\n"
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
            . "#\n"
            . "# button-translate=[true/false] -> if you translate each buttons in your language file\n"
            . "#\n"
            . "# manager-ui-button[number]=text in button\n"
            . "# manager-ui-button[number]-cat=categorie where button will be placed\n"
            . "# manager-ui-button[number]-catLogo=Logo of category (if you are not sure the category get the logo, you can add this line on each button of your extension)\n"
            . "# manager-ui-button[number]-logo=Logo placed befaore text in button\n"
            . "# manager-ui-button[number]-logoColor=Color of the button logo\n"
            . "# manager-ui-button[number]-accessName= access name for the button. Only super user that get this acces can show and interract with this button\n"
            . "# manager-ui-pannel[number]-html= name of file for html page in html directory\n"
            . "# manager-ui-pannel[number]-css= name of file for css page in css directory\n"
            . "# manager-ui-pannel[number]-php= name of file for php page in php directory\n"
            . "# manager-ui-pannel[number]-js= name of file for js page in js directory\n"
            . "# manager-ui-pannel[number]-id= id pannel (without spacing)\n"
            . "#\n\n"
            . "manager-ui=" . $nbBackUis . "\n";

        $front_base_content = "# All elements can be added in page editor :\n\n"
            . "front-elements=\n\n"
            . "#\n#[name of element]=[nb specifics variables]\n"
            . "#[name of element]-name=[name in page edit]\n"
            . "#[name of element]-dependencies=[dependency1],[dependency2],...\n"
            . "#[name of element]-front= [true/false] -> know if we need to generate front php (AFTER !DOCTYPE) or not\n"
            . "#[name of element]-back=[true/false] -> know if we need to generate back php (BEFORE !DOCTYPE) or not\n"
            . "#[name of element]-css=[true/false] -> know if we need to generate css or not\n"
            . "#[name of element]-vars=[var name 1],[var name 2],...\n"
            . "#\n";

        $db_base_content = "#table-used=[db1,db2,...]\n\n"
            . "#db1=[table1, table2, table3,...]\n\n"
            . "#db1-table1-type=[ varchar / text / int / tinyint / smallint / mediumint / bigint / boolean / date / decimal / float / double / real / serial / datetime / timestamp / time / year / char ]\n"
            . "#db1-table1-size=[varaible's size]\n"
            . "#db1-table1-value=[you can let this line empty or enter a starting value] !important! if your variable is auto increment, do not put a starting value\n"
            . "#db1-table1-nullable=[true / false / empty]\n"
            . "#db1-table1-index=[empty / PRIMARY KEY / UNIQUE]\n"
            . "#db1-table1-ai=[true / false / empty] -> is auto increment variable\n"
            . "\n#If value of line is null, you can delete the line.\n\ntable-used=\n";
        
        $base_en_langFile = "hellowWorld=Hello World !\n";
        $base_fr_langFile = "hellowWorld=Bonjour le monde !\n";
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
            if ($nbBackUis > 0) {
                $currBackUi = 1;
                for ($i = 0; $i < $_POST['nbBackIntels'] ; $i++) {
                    if (isset($_POST['easyExt-automatAdded-' . $i . '-namePageBack'])) {
                        $emptySpacing = str_replace(" ", "_", $_POST['easyExt-automatAdded-' . $i . '-namePageBack']);
                        $back_base_content .= ""
                            . "manager-ui-button" . $currBackUi . "=" . $_POST['easyExt-automatAdded-' . $i . '-namePageBack'] . "\n"
                            . "manager-ui-button" . $currBackUi . "-cat=" . $_POST['extenName'] . "\n"
                            . "manager-ui-button" . $currBackUi . "-catLogo=bx-layer-plus\n"
                            . "manager-ui-button" . $currBackUi . "-logo=bx bxs-file-plus\n"
                            . "manager-ui-button" . $currBackUi . "-logoColor=#68ff33\n"
                            . "manager-ui-button" . $currBackUi . "-accessName=" . $_POST['extenName'] . "\n"
                            . "manager-ui-pannel" . $currBackUi . "-html=" . $emptySpacing . ".php\n"
                            . "manager-ui-pannel" . $currBackUi . "-css=" . $emptySpacing . ".css\n"
                            . "manager-ui-pannel" . $currBackUi . "-php=" . $emptySpacing . ".php\n"
                            . "manager-ui-pannel" . $currBackUi . "-js=" . $emptySpacing . ".js\n"
                            . "manager-ui-pannel" . $currBackUi . "-id=" . $_POST['extenName'] . $currBackUi . "\n\n";
                        
                            $f = fopen($mainPathExt . "/back/panels/css/" . $emptySpacing . ".css", "w+");
                            fwrite($f, "", strlen(""));
                            fclose($f);
                            $f = fopen($mainPathExt . "/back/panels/php/" . $emptySpacing . ".php", "w+");
                            fwrite($f, "", strlen(""));
                            fclose($f);
                            $f = fopen($mainPathExt . "/back/panels/js/" . $emptySpacing . ".js", "w+");
                            fwrite($f, "", strlen(""));
                            fclose($f);
                            $contentHtml = "<?php\n\tglobal \$db, \$ext, \$hlp, \$ep, \$cf;\n\t\$extName = \"" . $_POST['extenName'] . "\";\n\t"
                                ."\$idPanel = \$cf->getValueFromKeyConf(\$ext->getManagerUiExtension(\$extName), \"manager-ui-pannel" . $currBackUi . "-id\");\n?>\n"
                                . "\n<div class=\"contextDev\" id=\"<?=\$idPanel?>\">\n"
                                . "\t<h1>" . $_POST['easyExt-automatAdded-' . $i . '-namePageBack'] . "</h1>\n"
                                . "\t<p><?=\$ext->getLangaugeValue(\$extName, \"hellowWorld\")?></p>\n"
                                . "</div>\n";
                            $f = fopen($mainPathExt . "/back/panels/html/" . $emptySpacing . ".php", "w+");
                            fwrite($f, $contentHtml, strlen($contentHtml));
                            fclose($f);
                            $currBackUi++;
                    }
                }
            }
            $f = fopen($mainPathExt . "/back/manager-ui.conf", "w+");
            fwrite($f, $back_base_content, strlen($back_base_content));
            fclose($f);
        }
        if (isset($_POST['extIsDb'])) {
            mkdir($mainPathExt . "/database", 0777, false);
            $f = fopen($mainPathExt . "/database/db.conf", "w+");
            fwrite($f, $db_base_content, strlen($db_base_content));
            fclose($f);
        }
        mkdir($mainPathExt . "/languages", 0777, false);
        $f = fopen($mainPathExt . "/languages/en.conf", "w+");
        fwrite($f, $base_en_langFile, strlen($base_en_langFile));
        fclose($f);
        $f = fopen($mainPathExt . "/languages/fr.conf", "w+");
        fwrite($f, $base_fr_langFile, strlen($base_fr_langFile));
        fclose($f);

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
        $f = fopen($mainPathExt . "/config.conf", "w+");
        fwrite($f, "\n", 1);
        fclose($f);
        header("location: " . $hlp->getMainUrl() . "/KW/manager");
    }
?>
