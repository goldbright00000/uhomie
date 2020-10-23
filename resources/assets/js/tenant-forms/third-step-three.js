import Tooltip from 'vue-bulma-tooltip';

$(function() {
    $('input[type=file]').change(function() {
        
        if( $(this).val() ){
            /*
            $('#mlmlml').css('background', 'rgba( 255, 255, 255, .8 ) url("/images/spinner_100px.svg") 50% 50% no-repeat;');
            $('#mlmlml').show();
            var body = $("body");
            body.addClass("loading");
            */
            let nombre = $(this).attr('name');
            var formData = new FormData();
            // Añadiendo el archivo al form
            formData.append('archivo', $(this).prop('files')[0]);
            formData.append('filename', $(this).prop('files')[0].name);
            formData.append('tipo', nombre);
            var elemento_input = this;
            $(elemento_input).siblings('.file-name').text($(elemento_input).prop('files')[0].name);
            $(elemento_input).parent('label').siblings('.file-show').children('img').remove();
            $(elemento_input).parent('label').siblings('.file-show').prepend('<img src="/images/icono-tilde-azul.png">');
            /*
            $.ajax({
                url: '/ocr_verify',
                method: 'POST',
                headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response){
                    if(response.respuesta){
                        toastr.success('Archivo validado correctamente');
                        $(elemento_input).siblings('.file-name').text($(elemento_input).prop('files')[0].name);
                        $(elemento_input).parent('label').siblings('.file-show').children('img').remove();
                        $(elemento_input).parent('label').siblings('.file-show').prepend('<img src="/images/icono-tilde-azul.png">');
                    }else{
                        //toastr.error('Archivo no es legible o no es valido');
                        if( response.hasOwnProperty('nombre_no_encontrado')  ){
                            toastr.error('Nombre: '+response.nombre_no_encontrado+' no encontrado');
                        }
                        if( response.hasOwnProperty('rut_no_encontrado')  ){
                            toastr.error('Codigo en el documento: '+response.rut_no_encontrado+' no encontrado');
                        }
                        if( response.hasOwnProperty('error_code')  ){
                            console.log(response.error_code)
                            if( response.error_code == 4 ){
                                toastr.error('Debes validar primero el carnet de identidad por la parte frontal para poder validar la parte trasera.');
                                
                            }
                            if( response.error_code == 5 ){
                                toastr.error('Lo sentimos, el documento no está vigente.');
                                
                            }
                            if( response.error_code == 6 ){
                                toastr.error('No procesamos este tipo de documentos por el momento, por favor contáctanos para darte una solución.');
                                
                            }
                        }
                        if( response.hasOwnProperty('liquidacion')){
                            toastr.error(response.liquido);
                        }
                        if( response.hasOwnProperty('dicom')){
                            toastr.error(response.dicom);
                        }
                        
                        $(elemento_input).val(null);
                    }
                    var body = $("body");
                    body.removeClass("loading");
                    $('#mlmlml').hide();
                },
                error: function(jqXHR, textStatus, errorThrown ){
                    var body = $("body");
                    body.removeClass("loading");
                    $('#mlmlml').hide();
                    console.log('fallo');
                    console.log(jqXHR.textStatus);
                }

            });
            */
        }
        
        
    });
});
/*TODO: Hacerle que suba archivos AJAX*/
