function addDep(placeHolderTxt, deleteWorld) {
    var cVal = document.getElementById("nbDependencies-GeneratorDep-devcompagnie").value;
    var nVal = (cVal * 1) + 1;
    var txt = "<fieldset class=\"easyExtensionGen-DependencyAdded\" id=\"nDep-" + nVal + "\"><legend>Dependency</legend>" +
        "<input type=\"text\" placeholder=\"" + placeHolderTxt + "\" name=\"nDep-" + nVal + "\">" +
        "<button type=\"button\" onclick=\"removeDep('nDep-" + nVal + "')\">" + deleteWorld + "</button>" +
        "</fieldset>";
    console.log(txt);
    document.getElementById("dependencies-GeneratorDep-devcompagnie").insertAdjacentHTML("beforeend", txt);
    document.getElementById("nbDependencies-GeneratorDep-devcompagnie").value = nVal;
}

function removeDep(idName) {
    document.getElementById(idName).remove();
}

function addBackPage(placeholderName, deleteWord) {
    var cVal = document.getElementById('nbBackIntels-easyExt').value;
    var currVal = document.getElementById('currNbBackIntels-easyExt').value;
    var nCurrVal = (currVal * 1) + 1;
    var nVal = (cVal * 1) + 1;
    var txt = "<tr id=\"easyExt-automatAdded-" + cVal + "-backPage\">" + 
        "<td class=\"easyExtension-nameTbaleListingBack\"><input type=\"text\" placeholder=\"" + placeholderName + "\" name=\"easyExt-automatAdded-" + cVal + "-namePageBack\"></td>" +
        "<td class=\"easyExtension-actonsTbaleListingBack\"><button onclick=\"removeBackPage('easyExt-automatAdded-" + cVal + "-backPage')\" type=\"button\" title=\"" + deleteWord + "\"><i class='bx bxs-trash'></i></button></td></tr>";
    document.getElementById("easyExtension-tableListBackPages").insertAdjacentHTML("beforeend", txt);
    document.getElementById("nbBackIntels-easyExt").value = nVal;
    document.getElementById('currNbBackIntels-easyExt').value = nCurrVal;
}

function removeBackPage(idName) {
    document.getElementById(idName).remove();
    var currVal = document.getElementById('currNbBackIntels-easyExt').value;
    var nCurrVal = (currVal * 1) - 1;
    document.getElementById('currNbBackIntels-easyExt').value = nCurrVal;
}

function checkCategory(idBtn, idCheck, idBtnAccess) {
    if (document.getElementById(idCheck).checked == true) {
        document.getElementById(idCheck).checked = false;
        document.getElementById(idBtn).innerHTML = "<box-icon color='red' size='lg' name='message-square-x' type='solid' ></box-icon>";
        if (idBtnAccess != "none") {
            document.getElementById(idBtnAccess).style.display = "none";
        }
    } else {
        document.getElementById(idCheck).checked = true;
        document.getElementById(idBtn).innerHTML = "<box-icon type='solid' color='green' size='lg' name='message-square-check'></box-icon>";
        if (idBtnAccess != "none") {
            document.getElementById(idBtnAccess).style.display = "block";
        }
    }
}

function goToIntels(idIntel) {
    document.getElementById('easyExtension-mainDiv').style.display = "none";
    document.getElementById(idIntel).style.display = "block";
}

function backToMainDiv(idIntel) {
    document.getElementById(idIntel).style.display = "none";
    document.getElementById('easyExtension-mainDiv').style.display = "block";
}