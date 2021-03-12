var $check = $("#adminCartaConducao"),
  el;

$check
  .data('checked', 0)
  .click(function(e) {

    el = $(this);

    switch (el.data('checked')) {

      // unchecked, going indeterminate
      case 0:
        el.data('checked', 1);
        el.prop('indeterminate', true);
        $('#adminCartaConducao').val('F');
        $('#labelCartaC').html('Sem carta!');
        break;

        // indeterminate, going checked
      case 1:
        el.data('checked', 2);
        el.prop('indeterminate', false);
        el.prop('checked', true);
        $('#adminCartaConducao').val('T');
        $('#labelCartaC').html('Com carta!');
        break;

        // checked, going unchecked
      default:
        el.data('checked', 0);
        el.prop('indeterminate', false);
        el.prop('checked', false);
        $('#adminCartaConducao').val('I');
        $('#labelCartaC').html('Indiferente.');

    }

  });


document.getElementById('abrePesquisa').addEventListener('click', abrirPesquisa)


function abrirPesquisa(){

    document.getElementById('abrePesquisa').removeEventListener('click', abrirPesquisa);
    document.getElementById('abrePesquisa').addEventListener('click', fecharPesquisa);

    document.getElementsByClassName('pesqAvancadaPopUp')[0].style.display = "block";
    document.getElementsByClassName('setaPesqAvancada')[0].innerHTML = "&#9660;";
}

function fecharPesquisa(){
    document.getElementById('abrePesquisa').removeEventListener('click', fecharPesquisa);
    document.getElementById('abrePesquisa').addEventListener('click', abrirPesquisa);

    document.getElementsByClassName('pesqAvancadaPopUp')[0].style.display = "none";
    document.getElementsByClassName('setaPesqAvancada')[0].innerHTML = "&#9654;"
}