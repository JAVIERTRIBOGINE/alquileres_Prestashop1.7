$(document).ready(function(){
	// FORMATO DATEPICKER A LOS CAMPOS DE FECHA
	$('#alquiler_dia, .calendario_admin').datepicker({
		firstDay: 1,
		dateFormat: 'yy-mm-dd',
        timeFormat:  "hh:mm:ss",
		beforeShowDay: function (day) {
            var day = day.getDay();
            if (day == 0) {
                return [false];
            } else {
                return [true];
            }
		}
	});
		// SE MUESTRA LA CAJA DE PETICIÓN DE ALQUILER
    $('#products_part2').hide();
    $('#show_rent_calendar').on('click', function () {
        $('#product').val('alquiler');
        searchProducts();
        $('#submitAddProduct').attr('disabled', 'disabled');
        $('#products_part2').toggle();
    });

		// AL AÑADIR LA FECHA, POR AJAX, SE CONSULTA LAS HORAS YA ALQUILADAS EN BBDD
    $('.calendario_admin').on('change',function(){
    	var horas=[];
        var fecha = $('.calendario_admin').val();
        $.ajax({
            type: 'POST',
            url: baseDir + 'modules/alquileres/ajax_alquileres2.php',
            data: {
                opt: 'horas-fechas',
                fecha: fecha,
				idCustomer: id_customer
            },
            dataType: 'json',
            success: // CON LA INFORMACIÓN DE BBDD, SE DESPLIEGAN LAS HORAS DEL DIA ELEGIDO
                function(result){
                    var incs = result['horas_reservadas'];
                    for (a in  incs) {// $('#res_calen').html(result['datos']);
                        var espacio = parseInt(a)+1;
                        console.log("a: "+ (parseInt(a)+1));

                        $('#horasLibres'+espacio).html('');
                        $('#horasLibres'+espacio).html(
                            "<td>Espacio " + espacio + "</td>"
                        );
                        $("#horasLibres"+(parseInt(a)+1)).fadeIn(500);
                        console.log('dentro del success');
                        for (b  in incs[a]) {
                            console.log('array ' + incs[a][b]);
                            if (incs[a][b]!=0) {
                                $("#horasLibres"+espacio).append(
                                    "<td class='freeHour' data-hour='"+
                                    incs[a][b]+"' data-space='"+espacio+"'>" +
                                    "<button class='btn btn-default '>" + incs[a][b] + "</button></td>");
                                }else{
                                // console.log('celda roja');
                                $("#horasLibres"+espacio).append(
                                    "<td  class='freeHour' data-hour='"+
                                    incs[a][b]+"' data-space='"+espacio+"'>" +
                                    "<button class='btn btn-danger' disabled>" + ((incs[a][(b-1)])+1) + "</button></td>");
                            }
                        }
                        $("#horasLibres"+espacio).append("</tr>");
                    }
                    console.log("datos: ");

                    $('.freeHour button').on('click', function(e){
                        e.preventDefault();
                        $(this).toggleClass('btn-success');
                        var espacioNum = $(this).parent('td').data('space');
                        var hour = $(this).parent('td').data('hour');
                        /* colaboracion de David:
                           .-En -1- se crea el identificador del futuro registro, si no existe
                           .-En -2- evalúa si EXISTE en all the DOM : eso se consigue
                           evaluando si el length del id es mayor que 0. Si es 0 es false,
                           si es mayor que 0, es true.
                           .-En -3-, -4- y -5- se ejecutan las dos opciones
                         */
                        /*1*/var ident = fecha + '_' + espacioNum + '_' + hour;
                        /*2*/if ($('#'+ident).length) {
                        /*3*/    $('#'+ident).remove();
                        /*4*/} else {
                        /*5*/    $('#reservasList').append('<li id="'+ident +'">Espacio ' + espacioNum + ' Fecha: ' + fecha + ' Hora: ' + hour + '</li>');
                        /*6*/}
                    });


                }
        });
        $('#rent_cal').on('click', function(){

            var linea='';
            var registro_alquiler = [];
            var quantity = $('#reservasList>li').length;
            for (a=0; a<quantity; a++) {
                registro_alquiler[a] = ($('#reservasList li:eq('+a+')').attr('id')).split('_');
                linea += $('#reservasList li:eq('+a+')').text()+'\n';
                console.log('texto' + a + " : " + linea);
            }
            $('#qty').val(quantity);
            $('#submitAddProduct').prop('enabled', true).click();
            $('#products_part2').hide();
            $('#show_rent_calendar').attr('disabled','disabled');
            $('.increaseqty_product').attr('dissabled','disabled');
            $('.decreaseqty_product').attr('disabled','disabled');
						//La información de los alquileres se mete en el campo de mensaje de pedido
						$('#order_message').val(linea).change();
            var mensaje = $('#order_message').val();
            $.ajax({
                type: 'POST',
                url: baseDir + 'modules/alquileres/ajax_alquileres2.php',
                data: {
                    opt: 'insertar_horas',
                    registro_alquiler : registro_alquiler,
                    idCustomer : id_customer,
                    mensaje : mensaje
                },
                dataType: 'json',
                success:
                    function(result){
                        console.log('ha insertado');
                        if (result['evaluacion'] == 'ok'){
                            $('#idAlquileres').val(result['idAlquileres']);
                            console.log('ok');
                            console.log('registros: ' + result['horas_reservadas']);
                        }else if (result['evaluacion'] == 'no_result'){
                            console.log('no_result');
                        }
                    }
            });
        });
    });
		// BOTONES VER-ESCONDER ALQUILERES EN EL PANEL DEL CUSTOMER
    $("#panel_alquileres").show();
    $('.alquileres_panel').hide();
    $('.esconderAlquileres').hide();
    $(".verAlquileres").on('click',(function(){
        var idBoton=$(this).attr('id').split('-');
        var id = parseInt(idBoton[1]);
        $("#esconderBotAlq-"+id).show();
        $("#alquileres_panel-"+id).show();
        $(this).hide();
    }));
    $(".esconderAlquileres").on('click',(function(){
        var idBoton=$(this).attr('id').split('-');
        var id = parseInt(idBoton[1]);
        $("#verBotAlq-"+id).show();
        $("#alquileres_panel-"+id).hide();
        $(this).hide();
    }));
});
