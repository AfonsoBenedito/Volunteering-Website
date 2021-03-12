



            
function abrirMenuPerfil(){
    document.getElementsByClassName("backMenuPerfil")[0].style.visibility = "visible"
    document.getElementsByClassName("apontadorPerfil")[0].style.visibility = "visible"
    document.getElementsByClassName("menuPerfil")[0].style.visibility = "visible"
    document.getElementsByClassName("perfilButton")[0].style.border = "2px solid rgb(0,14,20)"
}

function fecharMenuPerfil(){
    document.getElementsByClassName("backMenuPerfil")[0].style.visibility = "hidden"
    document.getElementsByClassName("apontadorPerfil")[0].style.visibility = "hidden"
    document.getElementsByClassName("menuPerfil")[0].style.visibility = "hidden"
    document.getElementsByClassName("perfilButton")[0].style.border = "2px solid rgb(0,14,20,0)"
}



function onload(){
    document.getElementsByClassName("perfilButton")[0].addEventListener('click', abrirMenuPerfil)
    document.getElementsByClassName("backMenuPerfil")[0].addEventListener('click', fecharMenuPerfil)

}

window.addEventListener('load', onload);