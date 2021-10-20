<?php
    global $hlp, $cf, $ext, $db, $ep;

    if (isset($_POST['addImageEdit'])) {
        $uploads_dir = "KW/kapweb_inits/ressources/medias/";
        $nameMedia = $_POST['newMediaName'];
        $enableMedia = 0;
        $typeMedia = $_POST['newMedia-type'];
        $description = "";
        if (isset($_POST['descriptNewMedia'])) {
            $description = $_POST['descriptNewMedia'];
        }
        if (isset($_POST['isEnableImage-add'])) {
            $enableMedia = 1;
        }
        if (isset($_POST['isUrlImage-add'])) {
            $hlp->newMedia($nameMedia, $enableMedia, $_POST['imgUrl-edit'], $typeMedia, $description);
            header("location: " . $hlp->getMainUrl() . "/KW/manager&pageBtn=btnImages&navMenu=navMenu2");
        } else {
            $nameFile = str_replace(" ", "_", basename($_FILES['uploadFile']['name']));
            $uploadFilePath = "KW/kapweb_inits/ressources/medias/";
            $uploadFilePath = $uploadFilePath . $nameFile;
            if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $uploadFilePath)) {
                $hlp->newMedia($nameMedia, $enableMedia, $uploadFilePath, $typeMedia, $description);
                header("location: " . $hlp->getMainUrl() . "/KW/manager&pageBtn=btnImages&navMenu=navMenu2");
            }
        }
        header("location: " . $hlp->getMainUrl() . "/KW/manager&pageBtn=btnImages&navMenu=navMenu2");
    }
    header("location: " . $hlp->getMainUrl() . "/KW/manager&pageBtn=btnImages&navMenu=navMenu2");
?>
