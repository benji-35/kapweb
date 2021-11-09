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

function checkCategory(idBtn, idCheck) {
    if (document.getElementById(idCheck).checked == true) {
        document.getElementById(idCheck).checked = false;
        document.getElementById(idBtn).innerHTML = "<box-icon color='red' size='lg' name='message-square-x' type='solid' ></box-icon>";
    } else {
        document.getElementById(idCheck).checked = true;
        document.getElementById(idBtn).innerHTML = "<box-icon type='solid' color='green' size='lg' name='message-square-check'></box-icon>";
    }
}