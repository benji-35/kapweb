function addDep() {
    var cVal = document.getElementById("nbDependencies-GeneratorDep-devcompagnie").value;
    var nVal = (cVal * 1) + 1;
    var txt = "<fieldset class=\"easyExtensionGen-DependencyAdded\" id=\"nDep-" + nVal + "\"><legend>Dependency</legend>" +
        "<input type=\"text\" placeholder=\"Dependency extension name...\" name=\"nDep-" + nVal + "\">" +
        "<button type=\"button\" onclick=\"removeDep('nDep-" + nVal + "')\">Supprimer</button>" +
        "</fieldset>";
    console.log(txt);
    document.getElementById("dependencies-GeneratorDep-devcompagnie").insertAdjacentHTML("beforeend", txt);
    document.getElementById("nbDependencies-GeneratorDep-devcompagnie").value = nVal;
}

function removeDep(idName) {
    document.getElementById(idName).remove();
}

function checkCategory(idBtn, idCheck) {
    if (document.getElementById(idCheck).value == "1") {
        document.getElementById(idCheck).value = "0";
        document.getElementById(idBtn).innerHTML = "<box-icon color='red' size='lg' name='message-square-x' type='solid' ></box-icon>";
    } else {
        document.getElementById(idCheck).value = "1"
        document.getElementById(idBtn).innerHTML = "<box-icon type='solid' color='green' size='lg' name='message-square-check'></box-icon>";
    }
}