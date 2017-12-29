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

  function getEstrellas(categoria){
    var stars = '';
    for (i = 1; i <= categoria; i++) {
      stars += '<i class="tiny material-icons activeStar">star</i>';
    }
    return stars;
  }

  function vistaPaquetes(producto,datos) {
    var result = '';
    $.each(datos.paquetes, function(i, item) {
      if (producto == 'hotel') {
        var habitacion = '';
        if (Array.isArray(item.Habitaciones.HabitacionRespuesta)) {
          $.each(item.Habitaciones.HabitacionRespuesta, function(ii, itemm) {
            habitacion += itemm.TipoHabitacion + ', ';
          });
        } else {
          habitacion = item.Habitaciones.HabitacionRespuesta.TipoHabitacion;
        } 
        result += '<div class="card horizontal resultados-contenedor result" data-idpaquete=' + item.PackageId + '>';
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
        result += '<div class="card horizontal resultados-contenedor result" data-idpaquete=' + item.PackageId + '>';
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
        result += '<p>Dias de Operacion : <b>' + item.CircuitoTarifas.CircuitoTarifa.DiasOperacion + '</b></p>';
        result += '<p>Desde : <b>' + accounting.formatMoney(item.CircuitoTarifas.CircuitoTarifa.Total) + '</b> Tarifa Adulto: <b>' + accounting.formatMoney(item.CircuitoTarifas.CircuitoTarifa.PrecioAdulto) + '</b> Tarifa Menores : <b>' + accounting.formatMoney(item.CircuitoTarifas.CircuitoTarifa.PrecioMenor) + '</b></p>';
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
    });
return result;
}

function vistaListado(producto, data) {
  var result = '';
  $.each(data.datosXML, function(i, item) {
    var precio = item.Precio_final === undefined || item.Precio_final === null ? '0.0' : item.Precio_final;
    if (producto == 'hotel') {
      result += '<div class="card horizontal resultados-contenedor" data-idproducto=' + i + ' data-nombreproducto="' + item.Nombre + '" >';
      result += '<div class="card-image" style="margin-left: 20px;margin-top: 18px;">';
      result += ' <img class="responsive-img circle" src="' + item.Imagen_main + '">';
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
      result += '<div class="card horizontal resultados-contenedor" data-idproducto=' + i + ' data-nombreproducto="' + item.Nombre + '" >';
      result += '<div class="card-image" style="margin-left: 20px;margin-top: 18px;">';
      result += ' <img class="responsive-img circle" src="' + item.Imagen + '">';
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
      result += '<div class="card horizontal resultados-contenedor" data-idproducto=' + i + ' data-nombreproducto="' + item.Nombre + '" >';
      result += '<div class="card-image" style="margin-left: 20px;margin-top: 18px;">';
      result += ' <img class="responsive-img circle" src="' + item.Imagen_main + '">';
      result += '</div>';
      result += '<div class="card-stacked">';
      result += '<div class="card-content">';
      result += '<p><b>' + item.Nombre + '</b></p>';
      result += '<p>Categoria . <b>' + item.ListaCategoria + '</b></p>';
      result += '<p>Circuito : <b>' + item.TipoCircuito + '</b> Servicio : <b>' + item.TipoServicio + '</b></p>';
      result += '<p>Dias de Duracion : <b>' + item.Dias + '</b></p>';
      result += '<p>Desde : <b>' + accounting.formatMoney(item.Total) + '</b></p>';
       $.each(item.rutas.RutaItinerario, function(ii, itemm) { 
            result += '<p>Ruta Dia '+itemm.Dia+' : <b>' + itemm.RutaCadena + '</b></p>';
       });
      result += '<p><span>' + getEstrellas(item.Categoria) + '</span></p>';
      result += '</div>';
      result += '</div>';
      result += '</div>';
    } else if (producto == 'actividad') {
      result += '<div class="card horizontal resultados-contenedor" data-idproducto=' + i + ' data-nombreproducto="' + item.Nombre + '" >';
      result += '<div class="card-image" style="margin-left: 20px; margin-top: 18px;">';
      result += ' <img class="responsive-img circle" src="' + item.Galeria.Imagen.Nombre + '">';
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
  var bandera = true;
  producto = $("#producto").data("producto");
  if (producto == 'hotel') {
    var url1 = "{{ route('hotelesBusqueda')}}";
  } else if (producto == 'tour') {
    var url1 = "{{ route('tourBusqueda')}}";
  } else if (producto == 'circuito') {
    var url1 = "{{ route('circuitoBusqueda')}}";
  } else if (producto == 'actividad') {
    var url1 = "{{ route('actividadBusqueda')}}";
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
      $('.btn').attr("disabled", true);
      $(self).attr('data-btn-text', $(self).text());
      $(self).html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Buscando..');
      $(self).addClass('active');
      $('.form1').addClass('opacar');
    },
    success: function(data) {
      console.info(data);
      console.log(data.error);
      console.log(producto);
      if(!data.error){
        var result = vistaListado(producto, data);
      }else {
        console.log('errorrrrr');
        var result = '<div class="card horizontal  red lighten-1">';
        result += '<div class="card-stacked">';
        result += '<div class="card-content">';
        result += '<p><b>' +data.error+ '</b></p>';
        result += '<p><button class="btn waves-effect teal accent-3"><span style="font-size: small;"> Notificar Error</span></button></p>';
        result += '</div>';
        result += '</div>';
        result += '</div>';
      }
      $(self).html($(self).attr('data-btn-text'));
      $(self).removeClass('active');
      $('.form1').removeClass('opacar loader');
      $('.btn').removeAttr("disabled");
      $('.form1, .fechaSalida, select').prop("disabled", false);
      $('#resultados').empty();
      $("#resultados").append(result);

      $('#resultados').on('click', '.resultados-contenedor', function(e) {
        busquedaPaquetes($(this))
      });
    }
  }).fail(function(jqXHR, textStatus, errorThrown) {
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

function busquedaPaquetes(self){
  console.info('buscando paquetes..');
  var producto = $("#producto").data("producto");
  var idproducto = $(self).data("idproducto");
  var nombreProducto = $(self).data("nombreproducto");
  var _token = " {{ csrf_token() }} ";
  if (producto == 'hotel') {
    var url2 = "{{ route('hotelDetalles')}}";
  } else if (producto == 'tour') {
    var url2 = "{{ route('tourDetalles')}}";
  } else if (producto == 'circuito') {
    var url2 = "{{ route('circuitoDetalles')}}";
  } else if (producto == 'actividad') {
    var url2 = "{{ route('actividadDetalles')}}";
  }  
  $.ajax({
    url: url2,
    data: { idproducto: idproducto, _token: _token },
    method: 'POST',
    dataType: 'JSON',
    cache: false,
    beforeSend: function() {
      $('#resultados').unbind("click");
      $('.form1, .fechaSalida, select').prop("disabled", true);
      $('.btn').attr("disabled", true);
      $(self).attr('data-btn-text', $(self).text());
      $(self).html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Buscando paquetes en '+nombreProducto+'..');
      $(self).addClass('active');
      $('.form1').addClass('opacar');
      $('.resultados').addClass('opacar');
    },
    success: function(datos) {
      console.log(datos);
      if(!datos.error){
        if (datos.paquetes != null || datos.paquetes != undefined) {
          var result = vistaPaquetes(producto,datos);
        } else {
          var result = '<div class="card horizontal yellow lighten-4">';
          result += '<div class="card-stacked">';
          result += '<div class="card-content">';
          result += '<p><b>No hay paquetes en este ' +producto+ '</b></p>';
          result += '</div>';
          result += '</div>';
          result += '</div>';
          console.log('sin paquetes');
        } 
        console.log('no error')
      }else {
        console.log('errorrrrrr')
        var result = '<div class="card horizontal  red lighten-1">';
        result += '<div class="card-stacked">';
        result += '<div class="card-content">';
        result += '<p><b>' +datos.error+ '</b></p>';
        result += '<p><button class="btn waves-effect teal accent-3"><span style="font-size: small;"> Notificar Error</span></button></p>';
        result += '</div>';
        result += '</div>';
        result += '</div>';
      }
      $('#resultados').empty();
      $("#resultados").append(result);
      $(self).html($(self).attr('data-btn-text'));
      $(self).removeClass('active');
      $('.form1').removeClass('opacar loader');
      $('.resultados').removeClass('opacar');
      $('.btn').removeAttr("disabled");
      $('.form1, .fechaSalida, select').prop("disabled", false);
      $('#resultados').on('click', '.resultados-contenedor', function(e) {
        enviarObj($(this))
      });
    }
  }).fail(function(jqXHR, textStatus, errorThrown) {
    $('#resultados').bind("click");
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

function enviarObj(self){
  console.log('enviando paquete al chat');
  var idpaquete = $(self).data("idpaquete");
  var _token = " {{ csrf_token() }} ";
  $.ajax({
    url: "{{ route('objAsesor')}}",
    data: { idpaquete: idpaquete, _token: _token },
    method: 'POST',
    dataType: 'JSON',
    cache: false,
    beforeSend: function() {
      $('.form1, .fechaSalida, select').prop("disabled", true);
      $('#resultados').unbind("click");
      $('.btn').attr("disabled", "disabled");
      $(self).attr('data-btn-text', $(self).text());
      $(self).html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Enviando..');
      $(self).addClass('active');
      $('.form1').addClass('opacar');
      $('.resultados').addClass('opacar');
    },
    success: function(jsonAsesor) {
      console.log(jsonAsesor);
      $(self).html(
        '<div class="card-image" style="margin-left: 20px;margin-top: 10px;"><span class="red-text"><i class="fa fa-bed fa-3x" style="padding-top: 42%;"></i></span></div><div class="card-stacked"><div class="card-content"><p>'+ $(self).attr('data-btn-text') +'</p></div></div>');
      $(self).removeClass('active');
      $('.form1').removeClass('opacar loader');
      $('.resultados').removeClass('opacar');
      $('.btn').removeAttr("disabled");
      $('.form1, .fechaSalida, select').prop("disabled", false);
      $('#resultados').on("click");
      console.log(jsonAsesor);
      postButtonMessage(jsonAsesor);
      $('#resultados').on('click', '.resultados-contenedor', function(e) {
        enviarObj($(this))
      });
    }
  }).fail(function(jqXHR, textStatus, errorThrown) {
    $(self).html($(self).attr('data-btn-text'));
    $(self).removeClass('active');
    $('.form1').removeClass('opacar loader');
    $('.resultados').removeClass('opacar');
    $('.btn').removeAttr("disabled");
    $('.form1, .fechaSalida, select').prop("disabled", false);
    $('#resultados').on("click");
    $("#resultados").html(jqXHR.responseText);
  });
}
</script>