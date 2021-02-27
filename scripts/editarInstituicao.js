function paginaPerfil(){
    document.getElementsByClassName('zonaEdicaoPerfil')[0].style.visibility = 'visible'
    document.getElementsByClassName('zonaEdicaoDados')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoPass')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoEmail')[0].style.visibility = 'hidden'

    document.getElementById('editarPerfilInstituicao').classList.add('btnUsed')
    document.getElementById('editarDadosInstituicao').classList.remove('btnUsed')
    document.getElementById('editarPassInstituicao').classList.remove('btnUsed')
    document.getElementById('editarEmailInstituicao').classList.remove('btnUsed')

}

function paginaDados(){
    document.getElementsByClassName('zonaEdicaoPerfil')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoDados')[0].style.visibility = 'visible'
    document.getElementsByClassName('zonaEdicaoPass')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoEmail')[0].style.visibility = 'hidden'

    document.getElementById('editarPerfilInstituicao').classList.remove('btnUsed')
    document.getElementById('editarDadosInstituicao').classList.add('btnUsed')
    document.getElementById('editarPassInstituicao').classList.remove('btnUsed')
    document.getElementById('editarEmailInstituicao').classList.remove('btnUsed')

}

function paginaPassword(){
    document.getElementsByClassName('zonaEdicaoPerfil')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoDados')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoPass')[0].style.visibility = 'visible'
    document.getElementsByClassName('zonaEdicaoEmail')[0].style.visibility = 'hidden'

    document.getElementById('editarPerfilInstituicao').classList.remove('btnUsed')
    document.getElementById('editarDadosInstituicao').classList.remove('btnUsed')
    document.getElementById('editarPassInstituicao').classList.add('btnUsed')
    document.getElementById('editarEmailInstituicao').classList.remove('btnUsed')

}

function paginaEmail(){
    document.getElementsByClassName('zonaEdicaoPerfil')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoDados')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoPass')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoEmail')[0].style.visibility = 'visible'

    document.getElementById('editarPerfilInstituicao').classList.remove('btnUsed')
    document.getElementById('editarDadosInstituicao').classList.remove('btnUsed')
    document.getElementById('editarPassInstituicao').classList.remove('btnUsed')
    document.getElementById('editarEmailInstituicao').classList.add('btnUsed')

}


function onload(){
    document.getElementById('editarPerfilInstituicao').addEventListener('click', paginaPerfil)
    document.getElementById('editarDadosInstituicao').addEventListener('click', paginaDados)
    document.getElementById('editarPassInstituicao').addEventListener('click', paginaPassword)
    document.getElementById('editarEmailInstituicao').addEventListener('click', paginaEmail)
}

window.addEventListener('load',onload)