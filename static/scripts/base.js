// BASE DE JAVA DO SITE
// When the user scrolls down 1px from the top of the document, slide down the navbar

var btnEntrar = document.getElementsByClassName("btnEntrar")[0];
if (btnEntrar) btnEntrar.addEventListener('click', abrirLogin);

var loginBg = document.getElementsByClassName("popUpLoginBackground")[0];
if (loginBg) loginBg.addEventListener('click', fecharLogin);
var zonaFechar = document.getElementsByClassName("zonaFechar")[0];
if (zonaFechar) zonaFechar.addEventListener('click', fecharLogin);

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
