<?php

    if (isset($_POST["sendContactMe-yolo"])) {
        mail($_POST[''], "Kapweb - contactMe", $_POST['messageToContactME']);
    }

?>