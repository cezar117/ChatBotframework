
<script>

function toggleDescripcion() {
    $(".readMinus").toggle();
    $(".readMore").toggle();
    if ($(".descripcionCard").hasClass("active")) {
        $(".descripcionCard").removeClass("active");
    } else {
        $(".descripcionCard").addClass("active");
    }


}

function initTwoInputs() {
    $('#two-inputs').dateRangePicker(
            {
                autoClose: true,
                startDate: new Date(),
                format: 'DD-MM-YYYY',
                minDays: 2,
                maxDays: 30,
                separator: ' al ',
                customTopBar: function(days, startTime, endTime)
                {
                    return days > 1 ? days - 1 + ' ' + ' noche(s)' : '';
                },
                hoveringTooltip: function(days, startTime, hoveringTime)
                {

                    if (days >= 2 && days < 3) {
                        $("#noches").html(days - 1 + ' ' + ' noche');
                        return days - 1 + ' ' + ' noche';

                    } else {
                        $("#noches").html(days - 1 + ' ' + ' noches');
                        return days - 1 + ' ' + ' noches';
                        ;
                    }

                },
                getValue: function()
                {
                    if ($('#checkIn').val() && $('#checkOut').val())
                        return $('#checkIn').val() + ' al ' + $('#checkOut').val();
                    else
                        return '';
                },
                setValue: function(s, s1, s2)
                {
                    $('#checkIn').val(s1);
                    $('#checkOut').val(s2);

                }
            });
}

function initFechaSalida() {
    $('.fechaSalida').dateRangePicker(
            {
                autoClose: true,
                singleDate: true,
                showShortcuts: true,
                singleMonth: true,
                format: 'DD-MM-YYYY',

                startDate: new Date()

            });
}

function initDestinos() {
    $(".selectDestino").select2({
        language: "es",
        placeholder: "Selecciona tu destino",

        ajax: {
            url: "{{ route('destinos')}}",
            method: 'POST',
            dataType: 'json',
            delay: 150,

            data: function(params) {
                return {
                    term: params.term,
                    _token: "{{ csrf_token() }}"
                };
            },
            processResults: function(data, params) {

                params.page = params.page || 1;

                return {
                    results: data,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        minimumInputLength: 1
    });
}
function Busqueda2(self){

console.log(self);
        $('.btn').attr("disabled", "disabled");
        $(self).attr('data-btn-text', $(self).text());
        //binding spinner element to button and changing button text
        $(self).html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Buscando');
        $(self).addClass('active');

    $('.form1').addClass('opacar');
   // $('.form1 select').prop("disabled", true);
    producto = $("#producto").data("producto");
    if (producto == 'hotel') {
    var url1 = "{{ route('hotelesBusqueda')}}" ;
    var url2 = "{{ route('hotelDetalles')}}" ;
    } else if(producto == 'tour'){
    var url1 = "{{ route('tourBusqueda')}}"; 
    var url2 = "{{ route('tourDetalles')}}" ;
    } else if(producto == 'circuito'){
    var url1 = "{{ route('circuitoBusqueda')}}"; 
    var url2 = "{{ route('circuitoDetalles')}}" ;
    }else if(producto == 'actividad'){
    var url1 = "{{ route('actividadBusqueda')}}"; 
    var url2 = "{{ route('actividadDetalles')}}" ;
    }
    $.ajax({
            url: url1,
            data: $("#form").serialize(),
            dataType: 'JSON',
            method:'POST',
            cache:false,
            beforeSend: function() {
              $('.form1 select').prop("disabled", true);
          },
            success:function(data){
                console.log(data);
                 //$(self).attr('data-btn-text'));
                 var result = '';
           $.each(data.datosXML, function(i, item) {

var precio = item.Precio_final == undefined ? '0.0' : item.Precio_final;
if (producto == 'hotel') {
    result+='<div class="card horizontal resultados-contenedor" data-idproducto='+i+' data-nombreproducto="'+item.Nombre+'">';
      result+='<div class="card-image" style="margin-left: 20px;margin-top: 10px;">';
      result+='<span class="red-text"><i class="fa fa-h-square fa-3x" style="padding-top: 42%;"></i></span>';
      result+='</div>';
      result+='<div class="card-stacked">';
        result+='<div class="card-content">';
          result+='<p><b>'+item.Nombre+'</b></p>';  
          result+='<p>Desde : <b>'+accounting.formatMoney(item.Precio_final) +'</b></p>';
          result+='<p><span>'+ getEstrellas(item.Categoria) +'</span></p>';
        result+='</div>';
      result+='</div>';
    result+='</div>';

} else if(producto == 'tour'){

result+='<div class="card horizontal resultados-contenedor" data-idproducto='+i+' data-nombreproducto="'+item.Nombre+'">';
      result+='<div class="card-image" style="margin-left: 20px;margin-top: 10px;">';
      result+='<span class="red-text"><i class="fa fa-map fa-3x" style="padding-top: 42%;"></i></span>';
      result+='</div>';
      result+='<div class="card-stacked">';
        result+='<div class="card-content">';
          result+='<p><b>'+item.Nombre+'</b></p>';  
          result+='<p>Desde : <b>'+accounting.formatMoney(precio) +'</b></p>';
          result+='<p><b>'+item.Categorias+'</b></p>';
          result+='<p>Ruta : <span><b>'+item.Ruta+ '</b></span></p>';
        result+='</div>';
      result+='</div>';
    result+='</div>';
} else if(producto == 'circuito'){

result += '<div  class="resultados-contenedor flotante" data-idproducto='+i+'> <div class="columna1 A"><span class="red-text accent-4">'+item.Nombre+'</span><br><span>Ruta: </span><span><b>$ '+ item.Ruta +'</b></span></div><div class="columna2 B"><span>'+ item.Categorias +'</span></div><span>Itinerario : </span><span>'+item.rutas.RutaItinerario.RutaCadena+'</span></div>';

}else if(producto == 'actividad'){

    result+='<div class="card horizontal resultados-contenedor" data-idproducto='+i+' data-nombreproducto="'+item.Nombre+'">';
      result+='<div class="card-image" style="margin-left: 20px;margin-top: 10px;">';
      result+='<span class="red-text"><i class="fa fa-map fa-3x" style="padding-top: 42%;"></i></span>';
      result+='</div>';
      result+='<div class="card-stacked">';
        result+='<div class="card-content">';
          result+='<p><b>'+item.Nombre+'</b></p>';  
          result+='<p>Desde : <b>'+accounting.formatMoney(item.Precio_final) +'</b></p>';
          result+='<p><span>'+ getEstrellas(item.Categoria) +'</span></p>';
        result+='</div>';
      result+='</div>';
    result+='</div>';
}

});

$('#resultados').empty();
$("#resultados").append(result);      
$(self).html($(self).attr('data-btn-text'));
$(self).removeClass('active');
$('.form1').removeClass('opacar loader');
$('.btn').removeAttr("disabled");

    $('.resultados-contenedor').on('click', function(e) {
        e.preventDefault();
        $('.btn').attr("disabled", "disabled");
        $(self).attr('data-btn-text', $(self).text());
        $(self).html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Buscando');
        $(self).addClass('active');
        $('.form1').addClass('opacar');
        $('.resultados').addClass('opacar');

    var idproducto = $(this).data("idproducto");
     var nombreProductoo = $(this).data("nombreproducto");
     var _token = " {{ csrf_token() }} ";
     var result = '';

      $.ajax({
            url: url2,
            data: {idproducto:idproducto, _token:_token},
            method:'POST',
            dataType: 'JSON',
            cache:false,
            beforeSend: function() {
              $('.form1 select').prop("disabled", true);
          },
            success:function(datos){
     console.log('paquetess:::');
    console.log(datos.paquetes);
    console.log('toursxml:::::');
    console.log(datos.tour);           
if(datos.paquetes !=null || datos.paquetes != undefined){  

$.each(datos.paquetes, function(i, item) {
// console.log(item.TourTarifas.TourTarifas.DiasDeOperacion);
if (producto == 'hotel') {
    result+='<div class="card horizontal resultados-contenedor" data-idpaquete='+item.PackageId+'>';
      result+='<div class="card-image" style="margin-left: 20px;margin-top: 10px;">';
      result+='<span class="red-text"><i class="fa fa-bed fa-3x" style="padding-top: 42%;"></i></span>';
      result+='</div>';
      result+='<div class="card-stacked">';
        result+='<div class="card-content">';
          result+='<p><b>'+item.Habitaciones.HabitacionRespuesta.TipoHabitacion+'</b></p>';  
          result+='<p>Desde : <b>'+accounting.formatMoney(item.PricePackage) +'</b></p>';
          result+='<p><span class="black-text"><b>idPaquete : </b></span><span>'+item.PackageId +'</span></p>';
        result+='</div>';
      result+='</div>';
    result+='</div>';

} else if(producto == 'tour'){
    result+='<div class="card horizontal resultados-contenedor" data-idpaquete='+item.PackageId+'>';
      result+='<div class="card-image" style="margin-left: 20px;margin-top: 10px;">';
      result+='<span class="red-text"><i class="fa fa-briefcase fa-3x" style="padding-top: 42%;"></i></span>';
      result+='</div>';
      result+='<div class="card-stacked">';
        result+='<div class="card-content">';
          result+='<p>Dias de Operacion<b>'+ item.TourTarifas.TourTarifas.DiasDeOperacion +'</b></p>';  
          result+='<p>Desde : <b>'+accounting.formatMoney(item.PricePackage) +'</b> Tarifa Adulto: <b>'+accounting.formatMoney(item.TourTarifas.TourTarifas.TarifaPrecioAdulto)+'</b> Tarifa Menores : <b>'+accounting.formatMoney(item.TourTarifas.TourTarifas.TarifaPrecioMenor)+'</b></p>';
          result+='<p><span class="black-text"><b>idPaquete : </b></span><span>'+item.PackageId +'</span></p>';
        result+='</div>';
      result+='</div>';
    result+='</div>';   

} else if(producto == 'circuito'){

result += '<div  class="resultados-contenedor flotante" data-idproducto='+i+'> <div class="columna1 A"><span class="red-text accent-4">'+item.Nombre+'</span><br><span>Ruta: </span><span><b>$ '+ item.Ruta +'</b></span></div><div class="columna2 B"><span>'+ item.Categorias +'</span></div><span>Itinerario : </span><span>'+item.rutas.RutaItinerario.RutaCadena+'</span></div>';

}else if(producto == 'actividad'){

result += '<div  class="resultados-contenedor flotante" data-idproducto='+i+'> <div class="columna1 A"><span class="red-text accent-4">'+item.Nombre+'</span><br><span>Desde: </span><span><b>'+accounting.formatMoney(item.Precio_final) +'</b></span></div><div class="columna2 B"><span>'+ getEstrellas(item.Categoria) +'</span></div></div>';
}

});

$('#resultados').empty();
$("#resultados").append(result);

$(self).html($(self).attr('data-btn-text'));
$(self).removeClass('active');
$('.form1').removeClass('opacar loader');
$('.resultados').removeClass('opacar');
$('.btn').removeAttr("disabled");

          $('.resultados').on('click', 'resultados-contenedor', function() {
            e.preventDefault();
        $('.btn').attr("disabled", "disabled");
        $(self).attr('data-btn-text', $(self).text());
        $(self).html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Buscando');
        $(self).addClass('active');
        $('.form1').addClass('opacar');
        $('.resultados').addClass('opacar');
            var idpaquete = $(this).data("idpaquete");

            $.ajax({ 
                     url: "{{ route('objAsesor')}}", 
                        data: {idpaquete:idpaquete, _token:_token},
                        method:'POST',
                        dataType: 'JSON',
                        cache:false,
                        beforeSend: function() {
                        $('.form1 select').prop("disabled", true);
                      },
                        success:function(jsonAsesor){ 
                          $(self).html($(self).attr('data-btn-text'));
$(self).removeClass('active');
$('.form1').removeClass('opacar loader');
$('.resultados').removeClass('opacar');
$('.btn').removeAttr("disabled");
            console.log(jsonAsesor);
            postButtonMessage(jsonAsesor);
                        }
                });
            });
        }else{
          $('#resultados').empty();
          $("#resultados").append('sin paquetes');
            console.log('sin paquetes');   
            //swal({title:'Opss!', html:'no hay paquetes para este producto..!', type:"error"});
                        }
              } //fin success
            });  //fin ajax
        });  //fin on.click
    } //fin success
  });

   

 
}





function Busqueda(){


producto = $("#producto").data("producto");
if (producto == 'hotel') {
var url1 = "{{ route('hotelesBusqueda')}}" ;
var url2 = "{{ route('hotelDetalles')}}" ;
} else if(producto == 'tour'){
var url1 = "{{ route('tourBusqueda')}}"; 
var url2 = "{{ route('tourDetalles')}}" ;
} else if(producto == 'circuito'){
var url1 = "{{ route('circuitoBusqueda')}}"; 
var url2 = "{{ route('circuitoDetalles')}}" ;
}else if(producto == 'actividad'){
var url1 = "{{ route('actividadBusqueda')}}"; 
var url2 = "{{ route('actividadDetalles')}}" ;
}

swal({
   title: 'buscando..',
   text: "",
   type: 'warning',
   target: '.contenidoBuscadores',
   showCancelButton: false,
   showConfirmButton: false,
   onOpen: function(){
    swal.showLoading();
        var result = '';
            $.ajax({
            url: url1,
            data: $("#form").serialize(),
            dataType: 'JSON',
            method:'POST',
            cache:false,
            success:function(data){
                console.log(data);
            swal.hideLoading(); 
$.each(data.datosXML, function(i, item) {

var precio = item.Precio_final == undefined ? '0.0' : item.Precio_final;
if (producto == 'hotel') {

    result+='<div class="card horizontal resultados-contenedor" data-idproducto='+i+' data-nombreproducto="'+item.Nombre+'">';
      result+='<div class="card-image" style="margin-left: 20px;margin-top: 10px;">';
      result+='<span class="red-text"><i class="fa fa-h-square fa-3x" style="padding-top: 42%;"></i></span>';
      result+='</div>';
      result+='<div class="card-stacked">';
        result+='<div class="card-content">';
          result+='<p><b>'+item.Nombre+'</b></p>';  
          result+='<p>Desde : <b>'+accounting.formatMoney(item.Precio_final) +'</b></p>';
          result+='<p><span>'+ getEstrellas(item.Categoria) +'</span></p>';
        result+='</div>';
      result+='</div>';
    result+='</div>';


} else if(producto == 'tour'){

result+='<div class="card horizontal resultados-contenedor" data-idproducto='+i+' data-nombreproducto="'+item.Nombre+'">';
      result+='<div class="card-image" style="margin-left: 20px;margin-top: 10px;">';
      result+='<span class="red-text"><i class="fa fa-map fa-3x" style="padding-top: 42%;"></i></span>';
      result+='</div>';
      result+='<div class="card-stacked">';
        result+='<div class="card-content">';
          result+='<p><b>'+item.Nombre+'</b></p>';  
          result+='<p>Desde : <b>'+accounting.formatMoney(precio) +'</b></p>';
          result+='<p><b>'+item.Categorias+'</b></p>';
          result+='<p>Ruta : <span><b>'+item.Ruta+ '</b></span></p>';
        result+='</div>';
      result+='</div>';
    result+='</div>';

} else if(producto == 'circuito'){

result += '<div  class="resultados-contenedor flotante" data-idproducto='+i+'> <div class="columna1 A"><span class="red-text accent-4">'+item.Nombre+'</span><br><span>Ruta: </span><span><b>$ '+ item.Ruta +'</b></span></div><div class="columna2 B"><span>'+ item.Categorias +'</span></div><span>Itinerario : </span><span>'+item.rutas.RutaItinerario.RutaCadena+'</span></div>';

}else if(producto == 'actividad'){

    result+='<div class="card horizontal resultados-contenedor" data-idproducto='+i+' data-nombreproducto="'+item.Nombre+'">';
      result+='<div class="card-image" style="margin-left: 20px;margin-top: 10px;">';
      result+='<span class="red-text"><i class="fa fa-map fa-3x" style="padding-top: 42%;"></i></span>';
      result+='</div>';
      result+='<div class="card-stacked">';
        result+='<div class="card-content">';
          result+='<p><b>'+item.Nombre+'</b></p>';  
          result+='<p>Desde : <b>'+accounting.formatMoney(item.Precio_final) +'</b></p>';
          result+='<p><span>'+ getEstrellas(item.Categoria) +'</span></p>';
        result+='</div>';
      result+='</div>';
    result+='</div>';

}

});

$('#resultados').empty();
$("#resultados").append(result);

$('.resultados-contenedor').on('click',function(){

 var idproducto = $(this).data("idproducto");
 var nombreProductoo = $(this).data("nombreproducto");
 var _token = " {{ csrf_token() }} ";
 var result = '';

swal({
   title: 'Buscando Paquetes en  : '+nombreProductoo,
   text: "Buscando...",
   type: 'warning',
   target: '.contenidoBuscadores',
   showCancelButton: false,
   showConfirmButton: false,
   onOpen: function(){
    swal.showLoading();

            $.ajax({ 
            url: url2,
            data: {idproducto:idproducto, _token:_token},
            method:'POST',
            dataType: 'JSON',
            cache:false,
            success:function(datos){
              swal.hideLoading(); 
 console.log('paquetess:::');
console.log(datos.paquetes);
console.log('toursxml:::::');
console.log(datos.tour);           
if(datos.paquetes !=null || datos.paquetes != undefined){  

$.each(datos.paquetes, function(i, item) {
console.log(item.TourTarifas.TourTarifas.DiasDeOperacion);

if (producto == 'hotel') {

    result+='<div class="card horizontal resultados-contenedor" data-idpaquete='+item.PackageId+'>';
      result+='<div class="card-image" style="margin-left: 20px;margin-top: 10px;">';
      result+='<span class="red-text"><i class="fa fa-bed fa-3x" style="padding-top: 42%;"></i></span>';
      result+='</div>';
      result+='<div class="card-stacked">';
        result+='<div class="card-content">';
          result+='<p><b>'+item.Habitaciones.HabitacionRespuesta.TipoHabitacion+'</b></p>';  
          result+='<p>Desde : <b>'+accounting.formatMoney(item.PricePackage) +'</b></p>';
          result+='<p><span class="black-text"><b>idPaquete : </b></span><span>'+item.PackageId +'</span></p>';
        result+='</div>';
      result+='</div>';
    result+='</div>';

} else if(producto == 'tour'){
    result+='<div class="card horizontal resultados-contenedor" data-idpaquete='+item.PackageId+'>';
      result+='<div class="card-image" style="margin-left: 20px;margin-top: 10px;">';
      result+='<span class="red-text"><i class="fa fa-briefcase fa-3x" style="padding-top: 42%;"></i></span>';
      result+='</div>';
      result+='<div class="card-stacked">';
        result+='<div class="card-content">';
          result+='<p>Dias de Operacion<b>'+ item.TourTarifas.TourTarifas.DiasDeOperacion +'</b></p>';  
          result+='<p>Desde : <b>'+accounting.formatMoney(item.PricePackage) +'</b> Tarifa Adulto: <b>'+accounting.formatMoney(item.TourTarifas.TourTarifas.TarifaPrecioAdulto)+'</b> Tarifa Menores : <b>'+accounting.formatMoney(item.TourTarifas.TourTarifas.TarifaPrecioMenor)+'</b></p>';
          result+='<p><span class="black-text"><b>idPaquete : </b></span><span>'+item.PackageId +'</span></p>';
        result+='</div>';
      result+='</div>';
    result+='</div>';   

} else if(producto == 'circuito'){

result += '<div  class="resultados-contenedor flotante" data-idproducto='+i+'> <div class="columna1 A"><span class="red-text accent-4">'+item.Nombre+'</span><br><span>Ruta: </span><span><b>$ '+ item.Ruta +'</b></span></div><div class="columna2 B"><span>'+ item.Categorias +'</span></div><span>Itinerario : </span><span>'+item.rutas.RutaItinerario.RutaCadena+'</span></div>';

}else if(producto == 'actividad'){





result += '<div  class="resultados-contenedor flotante" data-idproducto='+i+'> <div class="columna1 A"><span class="red-text accent-4">'+item.Nombre+'</span><br><span>Desde: </span><span><b>'+accounting.formatMoney(item.Precio_final) +'</b></span></div><div class="columna2 B"><span>'+ getEstrellas(item.Categoria) +'</span></div></div>';
}

});
$('#resultados').empty();
$("#resultados").append(result);

 swal.closeModal();

$('.resultados-contenedor').click(function(){
var idpaquete = $(this).data("idpaquete");

$.ajax({ 
         url: "{{ route('objAsesor')}}", 
            data: {idpaquete:idpaquete, _token:_token},
            method:'POST',
            dataType: 'JSON',
            cache:false,
            success:function(jsonAsesor){ 
console.log(jsonAsesor);
postButtonMessage(jsonAsesor);
            }
    });
});
        }else{
            console.log('sin paquetes');   
            swal({title:'Opss!', html:'no hay paquetes para este producto..!', type:"error"});
                        }
              } //fin success
            });  
                },
         allowOutsideClick: false
            }).catch(swal.noop);
    });
            swal.closeModal();
              }
            });
                },
                allowOutsideClick: false
            }).catch(swal.noop);
}

function getEstrellas(categoria){
    var stars = '';
    for (i = 1; i <= categoria; i++) {
            stars += '<i class="tiny material-icons activeStar">star</i>';
    }
    return stars;
}

</script>

<script>

</script>