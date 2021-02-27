document.getElementsByClassName("perfilButton")[0].addEventListener('click', abrirMenuPerfil)
document.getElementsByClassName("backMenuPerfil")[0].addEventListener('click', fecharMenuPerfil)
            
function abrirMenuPerfil(){
    document.getElementsByClassName("backMenuPerfil")[0].style.visibility = "visible"
    document.getElementsByClassName("apontadorPerfil")[0].style.visibility = "visible"
    document.getElementsByClassName("menuPerfil")[0].style.visibility = "visible"
}

function fecharMenuPerfil(){
    document.getElementsByClassName("backMenuPerfil")[0].style.visibility = "hidden"
    document.getElementsByClassName("apontadorPerfil")[0].style.visibility = "hidden"
    document.getElementsByClassName("menuPerfil")[0].style.visibility = "hidden"
}