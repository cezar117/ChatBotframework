<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
{{-- {{ Eliminar_Sesiones() }} --}}
        <title>TripYa</title>

        <!-- CSS  -->
        <!--<link href="{{ asset('bower_components/AdminLTE/plugins/select2/select2-materialize.css') }}" type="text/css" rel="stylesheet" >-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

        <!--<link href="{{ asset('bower_components/AdminLTE/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />-->
        <link href="{{ asset('css/bootstrap/bootstrap.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{ asset('css/datepicker/daterangepicker.min.css') }}">
        <link href="{{ asset('css/materialize/materialize.css') }}" type="text/css" rel="stylesheet" media="screen,projection,print">
        <link href="{{ asset('css/materialize/style.css') }}" type="text/css" rel="stylesheet" media="screen,projection,print">
        <link href="{{ asset('css/style.css') }}" type="text/css" rel="stylesheet" media="screen,projection,print">
        <link href="{{ asset('css/slider.css') }}" type="text/css" rel="stylesheet" media="screen,projection">
        <!--<link href="{{ asset('bower_components/AdminLTE/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <link href="{{ asset('css/sweetalert2.min.css') }}" type="text/css" rel="stylesheet" media="screen,projection">
        <link href="{{ asset('bower_components/AdminLTE/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('bower_components/AdminLTE/plugins/select2/s2-docs.css') }}" rel="stylesheet" type="text/css" />
<link href="https://unpkg.com/botframework-webchat/botchat.css" rel="stylesheet" />
        @stack('stylesheets')

@push('scripts')
 
@include('buscadores.scripts');

@endpush

@push("document.ready")
$('#form').on('submit', function() {

    if ($('#adultos').val() === 0 || undefined || null) {
        alert('seleccione adultos');
        return false;
    }

});

initTwoInputs();
initDestinos();

$(".menores").on("change", function() {
    cMenores($(this).val());
});

$("#habitaciones").on("change", function() {

    cHabitaciones($(this).val());
});

$(".btn-app").on('click', function() {
    $(".btn-app").each(function() {
        $(this).removeClass("teal");
    });
    $(this).addClass("teal");
    var to = $(this).data("buscador");
    
    var route = "";
    if (to === "hoteles") {
        route = "{{ route('buscadorHoteles') }}";
    } else if (to === "tours") {
        route = "{{ route('buscadorTours') }}";
    } else if (to === "circuitos") {
        route = "{{ route('buscadorCircuitos') }}";
    } else if (to === "actividades") {
        route = "{{ route('buscadorActividades') }}";
    }
    $.ajax({
        method: "GET",
        url: route,
        success: function(data) {
            $(".contenidoBuscadores").html(data);
            initDestinos();
            if (to === "hoteles") {
                initTwoInputs();
            } else {
                initFechaSalida();
            }

            $(".menores").on("change", function() {
                cMenores($(this).val());
            });
            $("#habitaciones").on("change", function() {

                cHabitaciones($(this).val());
            });

        }
    });

});

@endpush

    </head>
    <body>
<div class="container flotante">
    <div class="abc A">
        <div class="contactos-caja">
            {{-- {{dd(session("datos")) }} --}}
        </div>
        <div class="chat-caja">
        </div>
    </div>
 <div class="buscadores B">
            <div class="row center">
                <a class="btn btn-app teal" data-buscador="hoteles">
                    <i class="fa fa-home"></i> Hoteles
                </a>
                <a class="btn btn-app" data-buscador="tours">
                    <i class="fa fa-map-o"></i> Tours
                </a>
                <a class="btn btn-app" data-buscador="circuitos">
                    <i class="fa fa-map-o"></i> Circuitos
                </a>
                <a class="btn btn-app" data-buscador="actividades">
                    <i class="fa fa-map-o"></i> Actividades
                </a>
            </div>

<div class="flotante">

<div class="contenidoBuscadores">
    
    @include('componenteBusquedaHoteles',["from"=>"buscadores"]) 
  
</div>

<div class="resultados result2 B" id="resultados">

</div>

</div>
{{-- 
<button id="enviar"> enviar json</button> --}}
</div>
</div>
        <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
        <script src="{{ asset('js/materialize/materialize.js') }}"></script>
        <script src="{{ asset('js/scripts.js') }}"></script>
        <script src="{{ asset('js/autosize.min.js') }}"></script>

        <script src="{{ asset('js/materialize/init.js') }}"></script>
        <script type="text/javascript" src="{{asset('js/datepicker/moment.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/datepicker/jquery.daterangepicker.min.js')}}"></script>

        <script src="{{ asset('js/jquery.payform.js') }}"></script>
        <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
        <script src="{{ asset('js/jquery.steps.min.js') }}"></script>
        <script src="{{ asset('js/sticky-kit.min.js') }}"></script>
        <script src="{{ asset('js/accounting.min.js') }}"></script>
<script src="{{ asset('bower_components/AdminLTE/plugins/select2/select2.min.js') }}" ></script>
<script src="{{ asset('bower_components/AdminLTE/plugins/select2/i18n/es.js') }}"></script>

  <script src="https://unpkg.com/botframework-webchat/botchat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/4.3.0/firebase.js"></script>     
    @stack('scripts')
        <script>
 $(document).ready(function() {
@stack('document.ready')
  var bandera =true;
    //console.log("keeeep_aliveee");
$('.contactos-caja').on('click', '.bottt', function() {
      var botid= $(this).data("idbott");
      var id = $(this).data("idchat");
      conversationId = id;
      console.log('show');
     $('#'+id).removeClass('parpadea'); 
     $('.wc-upload').remove();
     $('#wc-upload-input').remove();
     $('.wc-textbox').css('left','10px')
    $('.ocultoo').hide();
    $('.wc-header').empty();
    $('.wc-header').append('Chat de '+id);
    $('#'+botid).show();

});

// $('.buscar').click(function(e){
// e.preventDefault();
// Busqueda2($(this));

// });

// $('.resultados').on('click', '.resultados-contenedor', function(e) {
//     //e.preventDefault();
//      busquedaPaquetes($(this));  
// //return false;
// });

setInterval('keep_alive()', 3000);
// $('.waves-effect').click(function( event) {
//   //var btn = $(this);
//   event.preventDefault();
//    // var self = $(this);
//    //    //start loading animation
 
//    //      // if ($(self).attr("disabled") == "disabled") {
//    //        // e.preventDefault();
        
//    //      //disable buttons when loading state
//    //      $('.has-spinner').attr("disabled", "disabled");
//    //      $(self).attr('data-btn-text', $(self).text());
//    //      //binding spinner element to button and changing button text
//    //      $(self).html('<span class="spinner"><i class="fa fa-spinner fa-spin"></i></span> Buscando');
//    //      $(self).addClass('active');
//    //    // }
//    //    Busqueda2(self);
// });
});

// $('#enviar').click(function (e){
//   var jsonn = new Array();
// jsonn[0]= {
//     "IdProducto": "XJWANHNT-MTYJQGJIX-00005-205390",
//     "NombreProducto": "Plaza Gallery Hotel  and  Boutique",
//     "IdPaquete": "eea6dbb6-e908-c044-474a-2b3671ade67c",
//     "TipoProducto": "Hotel",
//     "Latitud": 16.73720485,
//     "Longitud": -92.63887613,
//     "ImagenPrincipal": "https://photos.hotelbeds.com/giata/20/205390/205390a_hb_a_001.jpg",
//     "Precio": 4032,
//     "Categoria": 4,
//     "CategoriaString": "BOUTIQUE",
//     "DatosBusqueda": {
//       "Categoria": -1,
//       "Destino": 1,
//       "CheckIn": "17-09-2017",
//       "CheckOut": "19-09-2017",
//       "Habitaciones": [
//         {
//           "Adultos": 2,
//           "Menores": 0,
//           "EdadMenores": []
//         }
//       ],
//       "noches": 2
//     },
//     "Idioma": null,
//     "TipoServicio": null
//   };
// console.log(jsonn);
// postButtonMessage(jsonn);
// e.preventDefault();
// });
</script>            
        
<script>
// var config = {
//     apiKey: "AIzaSyDrLUWTeG9z-TEOsHyIijfrVCkQjb6bA4I",
//     authDomain: "chat-asesor.firebaseapp.com",
//     databaseURL: "https://chat-asesor.firebaseio.com",
//     projectId: "chat-asesor",
//     storageBucket: "chat-asesor.appspot.com",
//     messagingSenderId: "393284793006"
//   };
//   firebase.initializeApp(config);
</script>  
 <script>  
// var userr = firebase.auth().currentUser;

// if (userr) {
//   console.log('welcome');
//   console.log('user singed in');
// console.log(userr);

// } else {
//   console.log('welcome');
//   console.log(' No user is signed in');
//   console.log(userr);
//   // No user is signed in.
// }
// firebase.auth().onAuthStateChanged(function(userr) {
//   if (userr) {
//     document.body.innerHTML = '<h1> Congrats ' + userr.displayName + ', you are done! </h1> <h2> Now get back to what you love building. </h2> <h2> Need to verify your email address or reset your password? Firebase can handle all of that for you using the email you provided: ' + userr.email + '. <h/2>';
//   } else {
//     console.log("no user signed in");
//   }
// });
 </script>
<script>      
      var ConnectionStatus = {
        Uninitialized:0,              // the status when the DirectLine object is first created/constructed
        Connecting:1,                 // currently trying to connect to the conversation
        Online:2,                     // successfully connected to the converstaion. Connection is healthy so far as we know.
        ExpiredToken:3,               // last operation errored out with an expired token. Possibly waiting for someone to supply a new one.
        FailedToConnect:4,            // the initial attempt to connect to the conversation failed. No recovery possible.
        Ended:5                       // the bot ended the conversation
      }
    var n=1;
    var a;
    //var botConnection;
    var botConnections = new Array();
    var clientesArray=new Array();
    var nuevoArray = new Array();
    var arrayIds = new Array();
    var bandera = false;
    var conversationId;

function keep_alive(){

 $.each(botConnections, function(index, value) {
 botConnections[index]
                    .postActivity({type: "event", value: "", from: {id: firebaseUser.uid }, name: "keep_alive"})
                    .subscribe(id => {
                      // console.log("success se mando el keep_alive");
                    });
    console.log(botConnections[index].conversationId); 
});   
      }

function pintar_usuarios(evaluarIndex){
    var html ='';
    var id;
    $('.contactos-caja').empty(); 
    $('.contactos-caja').html(html); 
    console.log(botConnections);
    console.log(arrayIds);
    var a=1;
    //var otroa = 1;
    //var ultimoIndex = botConnections.length;
// if(botConnections.length == 1) n,a = 1;
    $.each(botConnections, function(index, data) {
    if (data.conversationId !== undefined) {
      id = data.conversationId;
    console.log(index);
     html+='<div data-idchat='+data.conversationId+' id="'+data.conversationId+'" data-idbott="bot'+arrayIds[index]+'" data-arrayid="'+arrayIds[index]+'" class="card horizontal usuario_chat bottt">';
      html+='<div class="card-image" style="margin-left: 20px;margin-top: 10px;">';
      html+='<i class="fa fa-user fa-3x""></i>';
      html+='</div>';
      html+='<div class="card-stacked">';
        html+='<div class="card-content">';
          html+='<p>'+data.conversationId+'</p>';
        html+='</div>';
      html+='</div>';
    html+='</div>'; 

    //   if(evaluarIndex)
    //   {
    //     if(otroa != ultimoIndex)
    //     {
    
    // html+='<div data-idchat='+data.conversationId+' id="'+data.conversationId+'" data-idbott="bot'+n+'" class="card horizontal usuario_chat bottt">';
    //   html+='<div class="card-image" style="margin-left: 20px;margin-top: 10px;">';
    //   html+='<i class="fa fa-user fa-3x""></i>';
    //   html+='</div>';
    //   html+='<div class="card-stacked">';
    //     html+='<div class="card-content">';
    //       html+='<p>'+data.conversationId+'</p>';
    //     html+='</div>';
    //   html+='</div>';
    // html+='</div>';  
    //     }      
    //   }  
    //   else
    //   {
    //     html+='<div data-idchat='+data.conversationId+' id="'+data.conversationId+'" data-idbott="bot'+n+'" class="card horizontal usuario_chat bottt">';
    //   html+='<div class="card-image" style="margin-left: 20px;margin-top: 10px;">';
    //   html+='<i class="fa fa-user fa-3x""></i>';
    //   html+='</div>';
    //   html+='<div class="card-stacked">';
    //     html+='<div class="card-content">';
    //       html+='<p>'+data.conversationId+'</p>';
    //     html+='</div>';
    //   html+='</div>';
    // html+='</div>'; 
    //   }
    }
    //otroa ++;
    a++;
     });
    console.log(html);
    $('.contactos-caja').append(html);

    return true;
}   
function postButtonMessage(jsonAsesor) {
         //console.log(botConnections);
         console.log(jsonAsesor);
         console.info( clientesArray.includes( conversationId ) );
         if(clientesArray.includes( conversationId )){
            clientesArray[conversationId]
                    .postActivity({type: "event", value: jsonAsesor, from: {id: firebaseUser.uid }, name: "compartir_producto"})
                    .subscribe(id => {
                      console.log("success se mando el compartir_producto");
                    }); 
         }              
      }

function inicializarBotFramework(){ 

  $('.chat-caja').append('<div class=ocultoo id=bot'+n+' data-idbot="'+n+'"></div>');
  var botConnection = null;
  var banderilla = false;
      botConnection = new BotChat.DirectLine({                  
                  secret: 'xZ-wMNfuSFw.cwA.Kg4.fndc-m2w-ZjkQcPY3HnmVoSWZjgn6tq-YktphG9QD-A'
              });
        //BotConectionFn(botConnection);
            BotChat.App({
        sendTyping: true,
        botConnection: botConnection,
        user: { id: firebaseUser.uid },
        bot: { id: 'ivanqbot' },
        resize: 'detect'
      }, document.getElementById('bot'+n));

        botConnection.connectionStatus$.subscribe(connectionStatus=>{
          console.log("connectionStatus: "+connectionStatus);
          if(connectionStatus==ConnectionStatus.Online){            
            botConnection
                    .postActivity({ type: "event", value: "" + firebaseUser.uid, from: {id: firebaseUser.uid  }, name: "identificacion_asesor" })
                    .subscribe(id =>{ console.log("success " +firebaseUser.uid); }
                      );
               }
        });

        botConnection.activity$
                  .filter(activity => activity.type === "event" && activity.name === "usuario_conectado")
                  .subscribe(activity => {
                    console.log('suscribir usuario');
                      if(suscribirUsuario(botConnection)){
                         Materialize.toast('Se a conectado un nuevo Usuario ..', 4000);
                         $('#'+activity.conversation.id).addClass('parpadea');
                      }                    
                    });

         botConnection.activity$
                  .filter(activity => activity.type === "event" && activity.name === "usuario_desconectado")
                  .subscribe(activity => {
                    console.log('el usuario se desconecto');
                    console.log(activity);                   
                    var idbot = $('#'+activity.conversation.id).data('idbott');
                    var arrayid= $('#'+activity.conversation.id).data('arrayid');

                    $('#'+activity.conversation.id).fadeOut( 300,function(){$(this).remove(); });
                    $('#'+idbot).fadeOut( 300,function(){$(this).remove(); });

                    $.each(arrayIds, function(i){
                            if(arrayIds[i] === arrayid) {
                                arrayIds.splice(i,1);
                                return false;
                            }
                        });
                    $.each(botConnections, function(i){
                            if(botConnections[i].conversationId === activity.conversation.id) {
                                botConnections.splice(i,1);
                                return false;
                            }
                        });
                    console.log('array map');
                    console.log(botConnections);
                   // pintar_usuarios(true);
                    });
         botConnection.activity$
                  .filter(activity => activity.type === "message" && activity.from.id === 'ivanqbot')
                  .subscribe(activity => {
                      console.log('un nuevo usuario');
                        console.log('mensaje nuevo de '+activity.conversation.id+':'+activity.text);
                            var idCaja= $('#'+activity.conversation.id).data('idbott');
                            console.log('#'+idCaja);
                             if( ! $('#'+idCaja).is(":visible") ){
                                   console.log('Elemento oculto');
                                    $('#'+activity.conversation.id).addClass('parpadea');
                                }
                                if( $('#'+idCaja).is(":hidden") ){
                                    console.log('Elemento oculto');
                                    $('#'+activity.conversation.id).addClass('parpadea');
                                }
                    });         

 $("#bot"+n).addClass('oculto'); 
  arrayIds.push(n);
  n++;
        return botConnection;
      }

function suscribirUsuario(botCon)
      {
        //botConnection = null;
        var banderilla =  true; 
        bandera = true;
        botConnections.push(inicializarBotFramework());
        console.log(botConnections);

$.each(botConnections, function(index, data) { 
if(data.conversationId !== undefined){
  clientesArray[data.conversationId] = data;  
  }
}); 
console.log('clientes arraaaay :');
console.log(clientesArray);
 pintar_usuarios(false);

return true;
// botCon.activity$
//                   .filter(activity => activity.type === "message" && activity.from.id === 'ivanqbot')
//                   .subscribe(activity => {

//                      if (banderilla ===true){
//                       console.log('un nuevo usuario');
//                         alert(activity.text);
//                         banderilla = false;  }
//                         console.log('mensaje nuevo de '+activity.conversation.id+':'+activity.text);
//                         $('#'+activity.conversation.id).addClass('parpadea');

//                     });
      }

      function InicializarAsesor()
      {
        var conexion = inicializarBotFramework();
        botConnections.push(conexion);
      }
    </script>
    <script src="https://www.gstatic.com/firebasejs/4.1.1/firebase.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.1.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.1.1/firebase-auth.js"></script>
    <script>
      // Initialize Firebase
      // TODO: Replace with your project's customized code snippet
      var config = {
        apiKey: "AIzaSyA9CGwoc0AgNZXy8cjykpwxEYbiJCBQQxs",
        authDomain: "tripya-862b6.firebaseapp.com",
        databaseURL: "https://tripya-862b6.firebaseio.com",
        projectId: "tripya-862b6",
        storageBucket: "tripya-862b6.appspot.com",
        messagingSenderId: "827929398371"
      };
      firebase.initializeApp(config);

      var firebaseUser;

      firebase.auth().onAuthStateChanged(function(user) {
        if (user) {
          console.log("user signed in");
          console.log(user);
          var isAnonymous = user.isAnonymous;
          var uid = user.uid;
          firebaseUser=user;

InicializarAsesor();  

        } else {
          console.log("user signed out");
        }
      });
      firebase.auth().signInAnonymously().catch(function(error) {
        // Handle Errors here.
        var errorCode = error.code;
        var errorMessage = error.message;
      });

    </script>
    </body>
</html>
