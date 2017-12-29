 function vistaPaquetes(producto) {

    var habitacion = '';
        if (Array.isArray(item.Habitaciones.HabitacionRespuesta)) {
            $.each(item.Habitaciones.HabitacionRespuesta, function(ii, itemm) {
                habitacion += itemm.TipoHabitacion + ', ';
            });
        } else {
            habitacion = item.Habitaciones.HabitacionRespuesta.TipoHabitacion;
        }    
        
     if (producto == 'hotel') {
         result += '<div class="card horizontal resultados-contenedor" data-idpaquete=' + item.PackageId + '>';
         result += '<div class="card-image" style="margin-left: 20px;margin-top: 10px;">';
         result += '<span class="red-text"><i class="fa fa-bed fa-3x" style="padding-top: 42%;"></i></span>';
         result += '</div>';
         result += '<div class="card-stacked">';
         result += '<div class="card-content">';
         result += '<p><b>' + habitacion + '</b></p>';
         result += '<p>Desde : <b>' + accounting.formatMoney(item.PricePackage) + '</b></p>';
         result += '<p><span class="black-text"><b>idPaquete : </b></span><span>' + item.PackageId + '</span></p>';
         result += '</div>';
         result += '</div>';
         result += '</div>';
     } else if (producto == 'tour') {
         result += '<div class="card horizontal resultados-contenedor" data-idpaquete=' + item.PackageId + '>';
         result += '<div class="card-image" style="margin-left: 20px;margin-top: 10px;">';
         result += '<span class="red-text"><i class="fa fa-briefcase fa-3x" style="padding-top: 42%;"></i></span>';
         result += '</div>';
         result += '<div class="card-stacked">';
         result += '<div class="card-content">';
         result += '<p>Dias de Operacion<b>' + item.TourTarifas.TourTarifas.DiasDeOperacion + '</b></p>';
         result += '<p>Desde : <b>' + accounting.formatMoney(item.PricePackage) + '</b> Tarifa Adulto: <b>' + accounting.formatMoney(item.TourTarifas.TourTarifas.TarifaPrecioAdulto) + '</b> Tarifa Menores : <b>' + accounting.formatMoney(item.TourTarifas.TourTarifas.TarifaPrecioMenor) + '</b></p>';
         result += '<p><span class="black-text"><b>idPaquete : </b></span><span>' + item.PackageId + '</span></p>';
         result += '</div>';
         result += '</div>';
         result += '</div>';
     } else if (producto == 'circuito') {
         result += '<div class="card horizontal resultados-contenedor" data-idpaquete=' + item.PackageId + '>';
         result += '<div class="card-image" style="margin-left: 20px;margin-top: 10px;">';
         result += '<span class="red-text"><i class="fa fa-briefcase fa-3x" style="padding-top: 42%;"></i></span>';
         result += '</div>';
         result += '<div class="card-stacked">';
         result += '<div class="card-content">';
         result += '<p>Dias de Operacion<b>' + item.TourTarifas.TourTarifas.DiasDeOperacion + '</b></p>';
         result += '<p>Desde : <b>' + accounting.formatMoney(item.PricePackage) + '</b> Tarifa Adulto: <b>' + accounting.formatMoney(item.TourTarifas.TourTarifas.TarifaPrecioAdulto) + '</b> Tarifa Menores : <b>' + accounting.formatMoney(item.TourTarifas.TourTarifas.TarifaPrecioMenor) + '</b></p>';
         result += '<p><span class="black-text"><b>idPaquete : </b></span><span>' + item.PackageId + '</span></p>';
         result += '</div>';
         result += '</div>';
         result += '</div>';
     } else if (producto == 'actividad') {
         result += '<div class="card horizontal resultados-contenedor" data-idpaquete=' + item.PackageId + '>';
         result += '<div class="card-image" style="margin-left: 20px;margin-top: 10px;">';
         result += '<span class="red-text"><i class="fa fa-briefcase fa-3x" style="padding-top: 42%;"></i></span>';
         result += '</div>';
         result += '<div class="card-stacked">';
         result += '<div class="card-content">';
         result += '<p>Nombre : <b>' + item.ActividadTarifas.ActividadTarifas.Nombre + '</b></p>';
         result += '<p>Desde : <b>' + accounting.formatMoney(item.PricePackage) + '</b> Tarifa Adulto: <b>' + accounting.formatMoney(item.ActividadTarifas.ActividadTarifas.TarifaPrecioAdulto) + '</b> Tarifa Menores : <b>' + accounting.formatMoney(item.ActividadTarifas.ActividadTarifas.TarifaPrecioMenor) + '</b></p>';
         result += '<p><span class="black-text"><b>idPaquete : </b></span><span>' + item.PackageId + '</span></p>';
         result += '</div>';
         result += '</div>';
         result += '</div>';
     }

 }

 function vistaListado(producto, data) {

     var result = null;
     $.each(data.datosXML, function(i, item) {
         var precio = item.Precio_final === undefined || item.Precio_final === null ? '0.0' : item.Precio_final;
         if (producto == 'hotel') {
             result += '<div class="card horizontal resultados-contenedor" data-idproducto=' + i + ' data-nombreproducto="' + item.Nombre + '">';
             result += '<div class="card-image" style="margin-left: 20px;margin-top: 18px;">';
             result += ' <img src="' + item.Imagen_main + '">';
             //result+='<span class="red-text"><i class="fa fa-h-square fa-3x" style="padding-top: 42%;"></i></span>';
             result += '</div>';
             result += '<div class="card-stacked">';
             result += '<div class="card-content">';
             result += '<p><b>' + item.Nombre + '</b></p>';
             result += '<p>Direccion : <b>' + item.Direccion + '</b></p>';
             result += '<p>Desde : <b>' + accounting.formatMoney(item.Precio_final) + '</b></p>';
             result += '<p><span>' + getEstrellas(item.Categoria) + '</span></p>';
             result += '</div>';
             result += '</div>';
             result += '</div>';

         } else if (producto == 'tour') {

             result += '<div class="card horizontal resultados-contenedor" data-idproducto=' + i + ' data-nombreproducto="' + item.Nombre + '">';

             result += '<div class="card-image" style="margin-left: 20px;margin-top: 18px;">';
             result += ' <img src="' + item.Imagen + '">';
             //result+='<span class="red-text"><i class="fa fa-map fa-3x" style="padding-top: 42%;"></i></span>';
             result += '</div>';
             result += '<div class="card-stacked">';
             result += '<div class="card-content">';
             result += '<p><b>' + item.Nombre + '</b></p>';
             result += '<p>Desde : <b>' + accounting.formatMoney(precio) + '</b></p>';
             result += '<p><b>' + item.Categorias + '</b></p>';
             result += '<p>Ruta : <span><b>' + item.Ruta + '</b></span></p>';
             result += '</div>';
             result += '</div>';
             result += '</div>';
         } else if (producto == 'circuito') {

             result += '<div class="card horizontal resultados-contenedor" data-idproducto=' + i + ' data-nombreproducto="' + item.Nombre + '">';
             result += '<div class="card-image" style="margin-left: 20px;margin-top: 18px;">';
             result += ' <img src="' + item.Imagen_main + '">';
             //result+='<span class="red-text"><i class="fa fa-map fa-3x" style="padding-top: 42%;"></i></span>';
             result += '</div>';
             result += '<div class="card-stacked">';
             result += '<div class="card-content">';
             result += '<p><b>' + item.Nombre + '</b></p>';
             result += '<p>Categoria . <b>' + item.ListaCategoria + '</b></p>';
             result += '<p>Circuito : <b>' + item.TipoCircuito + '</b> Servicio : <b>' + item.TipoServicio + '</b></p>';
             result += '<p>Desde : <b>' + accounting.formatMoney(item.Precio_final) + '</b></p>';
             result += '<p><span>' + getEstrellas(item.Categoria) + '</span></p>';
             result += '</div>';
             result += '</div>';
             result += '</div>';

         } else if (producto == 'actividad') {

             result += '<div class="card horizontal resultados-contenedor" data-idproducto=' + i + ' data-nombreproducto="' + item.Nombre + '">';
             result += '<div class="card-image" style="margin-left: 20px; margin-top: 18px;">';
             result += ' <img src="' + item.Galeria.Imagen.Nombre + '">';
             //result+='<span class="red-text"><i class="fa fa-map fa-3x" style="padding-top: 42%;"></i></span>';
             result += '</div>';
             result += '<div class="card-stacked">';
             result += '<div class="card-content">';
             result += '<p><b>' + item.Nombre + '</b></p>';
             result += '<p>Ciudad : <b>' + item.Ciudad + '</b></p>';
             result += '<p>Duracion : <b>' + item.Duraciones.string + '</b></p>';
             result += '<p>Desde : <b>' + accounting.formatMoney(item.Precio_final) + '</b></p>';
             result += '<p><span>' + getEstrellas(item.Categoria) + '</span></p>';
             result += '</div>';
             result += '</div>';
             result += '</div>';
         }
     });
     return result;
 }

 function Busqueda2(self) {

     $('.btn').attr("disabled", true);
     $(self).attr('data-btn-text', $(self).text());
     //binding spinner element to button and changing button text
     $(self).html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Buscando');
     $(self).addClass('active');
     $('.form1').addClass('opacar');
     producto = $("#producto").data("producto");

     if (producto == 'hotel') {
         var url1 = "{{ route('hotelesBusqueda')}}";
         var url2 = "{{ route('hotelDetalles')}}";
     } else if (producto == 'tour') {
         var url1 = "{{ route('tourBusqueda')}}";
         var url2 = "{{ route('tourDetalles')}}";
     } else if (producto == 'circuito') {
         var url1 = "{{ route('circuitoBusqueda')}}";
         var url2 = "{{ route('circuitoDetalles')}}";
     } else if (producto == 'actividad') {
         var url1 = "{{ route('actividadBusqueda')}}";
         var url2 = "{{ route('actividadDetalles')}}";
     }
     console.log('buscando..');
     $.ajax({
         url: url1,
         data: $("#form").serialize(),
         dataType: 'JSON',
         method: 'POST',
         cache: false,
         beforeSend: function() {
             $('.form1, .fechaSalida, select').prop("disabled", true);
             $(".resultados-contenedor").off("click");
         },
         success: function(data) {
             console.log(data);
             if (data.error !== null) {
                 var result = vistaListado(producto, data);
             } else {
                 var result = '<div class="card horizontal  red lighten-1">';
                 result += '<div class="card-stacked">';
                 result += '<div class="card-content">';
                 result += '<p><b>' + data.error + '</b></p>';
                 result += '</div>';
                 result += '</div>';
                 result += '</div>';
             }

             $(".resultados-contenedor").on("click");
             $(self).html($(self).attr('data-btn-text'));
             $(self).removeClass('active');
             $('.form1').removeClass('opacar loader');
             $('.btn').removeAttr("disabled");
             $('.form1, .fechaSalida, select').prop("disabled", false);
             $('#resultados').empty();
             $("#resultados").append(result);

         } //fin success

     }).fail(function(jqXHR, textStatus, errorThrown) {
         $(".resultados-contenedor").on("click");
         $(self).html($(self).attr('data-btn-text'));
         $(self).removeClass('active');
         $('.form1').removeClass('opacar loader');
         $('.resultados').removeClass('opacar');
         $('.btn').removeAttr("disabled");
         $('.form1, .fechaSalida, select').prop("disabled", false);

         $('#resultados').empty();
         $("#resultados").html(jqXHR.responseText);
         console.log(jqXHR);
         console.log(textStatus);
         console.log(errorThrown);

     });

 }