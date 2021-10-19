function displayNavMenu(name, iconName) {
    var doc = document.getElementById(name);
    var children = document.getElementById(iconName);

    if (doc.style.display == "block") {
        doc.style.display = "none";
        if (children.classList.contains("bx-up-arrow")) {
            children.classList.add("bx-down-arrow");
            children.classList.remove("bx-up-arrow");
        }
    } else {
        doc.style.display = "block";
        if (children.classList.contains("bx-down-arrow")) {
            children.classList.add("bx-up-arrow");
            children.classList.remove("bx-down-arrow");
        }
    }
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
    var currentUrl = window.location.href;
    var splittedUrl = currentUrl.split("/");
    var newUrl = "";
    for (var i = 0; i < splittedUrl.length - 1; i++) {
        newUrl += splittedUrl[i] + "/";
    }
    newUrl += splittedUrl[splittedUrl.length - 1].split("&")[0] + "&pageBtn=" + btnName;
    window.history.pushState("object or string", "Title", newUrl);
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
    } else {
        document.getElementById("softwareIntels").style.display = "none";
    }
}