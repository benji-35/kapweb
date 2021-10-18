function displayNavMenu(name, iconName) {
    var doc = document.getElementById(name);
    var children = document.getElementById(iconName);

    if (doc.style.display == "block") {
        doc.style.display = "none";
        if (children.classList.contains("fa-arrow-circle-up")) {
            children.classList.add("fa-arrow-alt-circle-down");
            children.classList.add("far");
            children.classList.remove("fa-arrow-circle-up");
            children.classList.remove("fa");
        }
    } else {
        doc.style.display = "block";
        if (children.classList.contains("fa-arrow-alt-circle-down")) {
            children.classList.add("fa-arrow-circle-up");
            children.classList.add("fa");
            children.classList.remove("fa-arrow-alt-circle-down");
            children.classList.remove("far");
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
        document.getElementById("access-" + i).checked = false;
    }
}

function hideShowSoftwareIntels() {
    if (document.getElementById("softwareIntels").style.display=="none") {
        document.getElementById("softwareIntels").style.display = "block";
    } else {
        document.getElementById("softwareIntels").style.display = "none";
    }
}