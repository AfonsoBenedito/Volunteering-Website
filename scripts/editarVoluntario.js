function paginaPerfil(){
    document.getElementsByClassName('zonaEdicaoPerfil')[0].style.visibility = 'visible'
    document.getElementsByClassName('zonaEdicaoDados')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoPass')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoEmail')[0].style.visibility = 'hidden'

    document.getElementById('editarPerfilVoluntario').classList.add('btnUsed')
    document.getElementById('editarDadosVoluntario').classList.remove('btnUsed')
    document.getElementById('editarPassVoluntario').classList.remove('btnUsed')
    document.getElementById('editarEmailVoluntario').classList.remove('btnUsed')

    document.getElementsByClassName('conteudoPrincipal')[0].style.height = '850px'


}

function paginaDados(){
    document.getElementsByClassName('zonaEdicaoPerfil')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoDados')[0].style.visibility = 'visible'
    document.getElementsByClassName('zonaEdicaoPass')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoEmail')[0].style.visibility = 'hidden'

    document.getElementById('editarPerfilVoluntario').classList.remove('btnUsed')
    document.getElementById('editarDadosVoluntario').classList.add('btnUsed')
    document.getElementById('editarPassVoluntario').classList.remove('btnUsed')
    document.getElementById('editarEmailVoluntario').classList.remove('btnUsed')

    document.getElementsByClassName('conteudoPrincipal')[0].style.height = '600px'
}

function paginaPassword(){
    document.getElementsByClassName('zonaEdicaoPerfil')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoDados')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoPass')[0].style.visibility = 'visible'
    document.getElementsByClassName('zonaEdicaoEmail')[0].style.visibility = 'hidden'

    document.getElementById('editarPerfilVoluntario').classList.remove('btnUsed')
    document.getElementById('editarDadosVoluntario').classList.remove('btnUsed')
    document.getElementById('editarPassVoluntario').classList.add('btnUsed')
    document.getElementById('editarEmailVoluntario').classList.remove('btnUsed')

    document.getElementsByClassName('conteudoPrincipal')[0].style.height = '600px'
}

function paginaEmail(){
    document.getElementsByClassName('zonaEdicaoPerfil')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoDados')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoPass')[0].style.visibility = 'hidden'
    document.getElementsByClassName('zonaEdicaoEmail')[0].style.visibility = 'visible'

    document.getElementById('editarPerfilVoluntario').classList.remove('btnUsed')
    document.getElementById('editarDadosVoluntario').classList.remove('btnUsed')
    document.getElementById('editarPassVoluntario').classList.remove('btnUsed')
    document.getElementById('editarEmailVoluntario').classList.add('btnUsed')

    document.getElementsByClassName('conteudoPrincipal')[0].style.height = '600px'
}


function onload(){
    document.getElementById('editarPerfilVoluntario').addEventListener('click', paginaPerfil)
    document.getElementById('editarDadosVoluntario').addEventListener('click', paginaDados)
    document.getElementById('editarPassVoluntario').addEventListener('click', paginaPassword)
    document.getElementById('editarEmailVoluntario').addEventListener('click', paginaEmail)
}

window.addEventListener('load',onload)