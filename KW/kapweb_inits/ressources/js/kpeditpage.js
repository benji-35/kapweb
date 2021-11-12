function openNavBar (idNaveBar, idEditMenu, idIcon) {
    var docNavBAr = document.getElementById(idNaveBar);

    if (docNavBAr.style.display == "none") {
        docNavBAr.style.display = "block";
        document.getElementById(idIcon).className = "bx bxs-up-arrow iconNavBar";
    } else {
        docNavBAr.style.display = "none";
        document.getElementById(idIcon).className = "bx bxs-down-arrow iconNavBar";
    }
    openEditMenu(idEditMenu);
}

function openEditMenu(idEditMenu) {
    var editsMenus = document.getElementsByClassName("editMenu");
    for (var i = 0; i < editsMenus.length; i++) {
        editsMenus[i].style.display = "none";
    }
    document.getElementById(idEditMenu).style.display = "block";
}

function removeClass(classTextAreaId, idClass, nameClass) {
    var txtClasses = document.getElementById(classTextAreaId).value;
    document.getElementById(idClass).remove();
    var classes = txtClasses.split("\n");
    var nTxtClasses = "";
    for (var i = 0; i < classes.length; i++) {
        if (classes[i] != nameClass) {
            nTxtClasses += classes[i] + "\n";
        }
    }
    document.getElementById(classTextAreaId).value = nTxtClasses;
}

function resetJsCssContent(idJssCss) {
    document.getElementById(idJssCss).value = "";
}

function showHideAddElem(idShowHide, state) {
    if (state == true) {
        document.getElementById(idShowHide).style.display = "block";
    } else {
        document.getElementById(idShowHide).style.display = "none";
    }
}