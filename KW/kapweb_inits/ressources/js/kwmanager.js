function displayNavMenu(name, iconName, btnName, inverseDiv) {
    var doc = document.getElementById(name);
    if (iconName != null) {
        var children = document.getElementById(iconName);
    }
    if (doc.style.display == "block") {
        window.history.pushState("object or string", "Title", updateHiveNavMenu(btnName));
        doc.style.display = "none";
        if (iconName != null && children.classList.contains("bx-up-arrow")) {
            children.classList.add("bx-down-arrow");
            children.classList.remove("bx-up-arrow");
        }
        if (inverseDiv != null) {
            document.getElementById(inverseDiv).style.display = "block";
        }
    } else {
        window.history.pushState("object or string", "Title", updateDisplayNavMenu(btnName));
        doc.style.display = "block";
        if (iconName != null && children.classList.contains("bx-down-arrow")) {
            children.classList.add("bx-up-arrow");
            children.classList.remove("bx-down-arrow");
        }
        if (inverseDiv != null) {
            document.getElementById(inverseDiv).style.display = "none";
        }
    }
}

function updateDisplayContextMenuUrl(btnName) {
    var currentUrl = window.location.href;
    var splittedUrl = currentUrl.split("/");
    var newUrl = "";

    for (var i = 0; i < splittedUrl.length - 1; i++) {
        newUrl += splittedUrl[i] + "/";
    }
    var options = splittedUrl[splittedUrl.length - 1].split("&");

    newUrl += options[0] + "&pageBtn=" + btnName;

    for (var i = 1; i < options.length; i++) {
        if (options[i].startsWith("pageBtn=") == false) {
            newUrl += "&" + options[i];
        }
    }
    return newUrl;
}

function updateDisplayNavMenu(navMenu) {
    var currentUrl = window.location.href;
    var splittedUrl = currentUrl.split("&");
    var newUrl = splittedUrl[0];
    var addedInUrl = false;

    for (var i = 1; i < splittedUrl.length; i++) {
        if (splittedUrl[i].startsWith("navMenu=")) {
            addedInUrl = true;
            if (splittedUrl[i] == "navMenu=") {
                splittedUrl[i] += navMenu;
            } else {
                splittedUrl[i] += "," + navMenu;
            }
        }
        newUrl += "&" + splittedUrl[i];
    }
    if (addedInUrl == false) {
        newUrl += "&navMenu=" + navMenu;
    }

    return newUrl;
}

function updateHiveNavMenu(navMenu) {
    var currentUrl = window.location.href;
    var splittedUrl = currentUrl.split("&");
    var newUrl = splittedUrl[0];

    for (var i = 1; i < splittedUrl.length; i++) {
        if (splittedUrl[i].startsWith("navMenu=")) {
            var sepTitleContent = splittedUrl[i].split("=");
            var nContent = "";
            if (sepTitleContent.length > 1) {
                var sepContent = sepTitleContent[1].split(",");
                for (var x = 0; x < sepContent.length; x++) {
                    if (sepContent[x] != navMenu && sepContent[x] != "") {
                        if (nContent == "") {
                            nContent = sepContent[x];
                        } else {
                            nContent += "," + sepContent[x];
                        }
                    }
                }
            }
            splittedUrl[i] = "navMenu=" + nContent;
        }
        newUrl += "&" + splittedUrl[i];
    }

    return newUrl;
}

function displayContextMenu(name, btnName) {
    var allContexts = document.getElementsByClassName("contextDev");
    var allButtons = document.getElementsByClassName("btnSelected");

    for (var i = 0; i < allButtons.length; i++) {
        allButtons[i].classList.add('btnNavMenu');
        allButtons[i].classList.remove('btnSelected');
    }
    for (var i = 0; i < allContexts.length; i++) {
        allContexts[i].style.display = "none";
    }

    window.history.pushState("object or string", "Title", updateDisplayContextMenuUrl(btnName));
    document.getElementById(name).style.display = "block";
    document.getElementById(btnName).classList.remove('btnNavMenu');
    document.getElementById(btnName).classList.add('btnSelected');
}

function displayEditCurrUrl(button, inputReadonly, inputedit, inputSend) {
    document.getElementById(button).style.display = "none";
    document.getElementById(inputReadonly).style.display = "none";
    document.getElementById(inputedit).style.display = "block";
    document.getElementById(inputSend).style.display = "block";
}

function selectAllAccessNew(nbAccess) {
    var tRows = document.getElementById("tblBodyNAccess").getElementsByTagName("tr");
    for (var i = 0; i < tRows.length; i++) {
        document.getElementById("access-" + i).checked = true;
    }
}

function unselectAllAccessNew(nbAccess) {
    var tRows = document.getElementById("tblBodyNAccess").getElementsByTagName("tr");
    for (var i = 0; i < tRows.length; i++) {
        document.getElementById("access-" + i).checked = bxlse;
    }
}

function hideShowSoftwareIntels() {
    if (document.getElementById("softwareIntels").style.display=="none") {
        document.getElementById("softwareIntels").style.display = "block";
        document.getElementById("refreshMedias").style.display = "none";
    } else {
        document.getElementById("softwareIntels").style.display = "none";
    }
}

function hideShowRefreshMedias() {
    if (document.getElementById("refreshMedias").style.display=="none") {
        document.getElementById("refreshMedias").style.display = "block";
        document.getElementById("softwareIntels").style.display = "none";
    } else {
        document.getElementById("refreshMedias").style.display = "none";
    }
}

function abortAddImage(btnName, div, inverseDiv) {
    displayNavMenu(div, null, btnName, inverseDiv);
}

function changeMenuEdit(idName, menuEditId) {
    var btns = document.getElementsByClassName("generalButton-edit");
    var menus = document.getElementsByClassName("generalMenu-edit");
    for (var i = 0; i < btns.length; i++) {
        if (btns[i].classList.contains("buttonSelected-edit")) {
            btns[i].classList.remove("buttonSelected-edit");
        }
    }
    for (var i = 0; i < menus.length; i++) {
        menus[i].style.display = "none";
    }
    document.getElementById(idName).classList.add("buttonSelected-edit");
    document.getElementById(menuEditId).style.display = "block";
}

function ownToggleButton(idToggleInput, idIconToggle) {
    if (document.getElementById(idToggleInput).checked) {
        document.getElementById(idIconToggle).classList.remove("bxs-toggle-right");
        document.getElementById(idIconToggle).classList.add("bx-toggle-left");
        document.getElementById(idToggleInput).checked = false;
    } else {
        document.getElementById(idIconToggle).classList.remove("bx-toggle-left");
        document.getElementById(idIconToggle).classList.add("bxs-toggle-right");
        document.getElementById(idToggleInput).checked = true;
    }
}
