// BASE DE JAVA DO SITE
// When the user scrolls down 1px from the top of the document, slide down the navbar

document.getElementsByClassName("btnEntrar")[0].addEventListener('click', abrirLogin)

document.getElementsByClassName("popUpLoginBackground")[0].addEventListener('click', fecharLogin)
document.getElementsByClassName("zonaFechar")[0].addEventListener('click', fecharLogin)

function abrirLogin(){
    //document.getElementById("body").style.overflowY = "hidden"
    document.getElementsByClassName("popUpLoginBackground")[0].style.visibility = "visible"
    document.getElementsByClassName("popUpLogin")[0].style.visibility = "visible"
}

function fecharLogin(){
    //document.getElementById("body").style.overflowY = "auto"
    document.getElementsByClassName("popUpLoginBackground")[0].style.visibility = "hidden"
    document.getElementsByClassName("popUpLogin")[0].style.visibility = "hidden"
}
