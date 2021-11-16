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
    var classes = txtClasses.split(" ");
    var nTxtClasses = "";
    console.log(classes);
    for (var i = 0; i < classes.length; i++) {
        if (classes[i] != nameClass) {
            if (nTxtClasses == "") {
                nTxtClasses += classes[i];
            } else {
                nTxtClasses += " " + classes[i];
            }
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

function addClass(idTextareaClasses, idInputClassName, idEditClass, nameNoSpacing) {
    var currClasses = document.getElementById(idTextareaClasses).value;
    var nameClass = document.getElementById(idInputClassName).value;

    var nClasses = currClasses;
    var idNClass = nClasses.split(" ").length;
    if (nClasses == "") {
        idNClass = 0;
    }
    var nClassId = nameNoSpacing + "-class" + idNClass;
    var nButton = "<button id=\"" + nClassId + "\" class=\"elemClass\" type=\"button\" onclick=\"removeClass('" + idTextareaClasses + "', '" + nClassId + "', '" + nameClass + "')\"><p>" + nameClass + "</p></button>";
    if (nClasses == "") {
        nClasses = nameClass;
    } else {
        nClasses += " " + nameClass;
    }
    document.getElementById(idTextareaClasses).value = nClasses;
    document.getElementById(idInputClassName).value = "";
    document.getElementById(idEditClass).innerHTML += nButton;
}