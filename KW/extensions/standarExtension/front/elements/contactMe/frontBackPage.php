<?php
    if (isset($_POST["sendContactMe-\$kw['contactMe-nameSender']"])) {
        mail($kw['configExtension']['$cf-emailContactMe'], "Kapweb - contactMe", $_POST['messageToContactME']);
    }
?>