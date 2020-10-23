$(function() {
    $('input[type=file]').change(function() {
        $(this).siblings('.file-name').text($(this).prop('files')[0].name);
    });
});

function makePayment () {
  if($('#input_membership').val() == null || $('#input_membership').val() == 0){
    alert('No se selecciono ninguna membresia');
    return;
    }else {
        $('#form').submit();
    }
}
function selectMembership(id){
  $('#select_button'+id).removeClass('ThemeBlue');
  $('#input_membership').val(id);
  //alert($('#input_membership').val())
}
