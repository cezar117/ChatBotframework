<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::get('/', function () {
    return view('welcome');
})->name("indexx");
// Route::get('/', function () {
//     return view('auth.login');
// });

Route::post('destinos', 'serviciosController@destinos')->name('destinos');

Route::get('buscadorHoteles', function () {
    $busqueda = session("oBuscador");
    return view('componenteBusquedaHoteles', ["from" => "buscadores", "busqueda" => $busqueda]);
})->name("buscadorHoteles");

Route::get('buscadorTours', function () {
    $busqueda = session("oBuscadorTours");
    return view('componenteBusquedaTours', ["from" => "buscadores", "busqueda" => $busqueda]);
})->name("buscadorTours");

Route::get('buscadorCircuitos', function () {
    $busqueda = session("oBuscadorCircuitos");
    return view('componenteBusquedaCircuitos', ["from" => "buscadores", "busqueda" => $busqueda]);
})->name("buscadorCircuitos");
Route::get('buscadorActividades', function () {
    $busqueda = session("oBuscadorActividades");
    return view('componenteBusquedaActividades', ["from" => "buscadores", "busqueda" => $busqueda]);
})->name("buscadorActividades");

Route::get('hoteles', function () {
    return view('hoteles.index', ["from" => "buscadores"]);
})->name('hoteles');

Route::get('tours', function () {
    return view('tours.index', ["from" => "buscadores"]);
})->name('tours');

Route::get('circuitos', function () {
    return view('circuitos.index', ["from" => "buscadores"]);
})->name('circuitos');

Route::get('actividades', function () {
    return view('actividades.index', ["from" => "buscadores"]);
})->name('actividades');

Route::get('error', function () {
    return view('estructura.error');
})->name('error');

Route::get('carrito', function () {
    return view('carrito.index');
})->name('carrito');

// Route::get('carrito/PostAuthSuccess', function() {
//     return view('carrito.ViewPostAutorizacionSuccess');
// })->name('PostAuthSuccess');

Route::post('carrito/PostAuthSuccess', function () {
    return view('carrito.ViewPostAutorizacionSuccess');
})->name('ViewPostAutorizacionSuccess');

Route::post('carrito/PostAuthError', function () {
    return view('carrito.ViewPostAutorizacionError');
})->name('ViewPostAutorizacionError');

Route::post('carrito/PreAuthError', function () {
    return view('carrito.ViewPreAutorizacionError');
})->name('ViewPreAutorizacionError');
// Route::post('carrito/CompletarDatos', function(){
//   return view('carrito.CompletarDatos');
// })->name('carrito.CompletarDatos');
// Route::get('finalizar', function () {
//     return view('carrito.finalizar');
// })->name('finalizar');
//
//Route::get('hotel', function () {
//    return view('hoteles.busqueda');
//});
Route::post('addToCart', 'ServiciosController@addToCart')->name('addToCart');

Route::post('hotelesBusqueda', 'HotelesController@hotelesBusqueda')->name('hotelesBusqueda');
Route::post('hoteles/busqueda/detalles', 'HotelesController@detalleHotel')->name('hotelDetalles');

Route::post('tours/busqueda', 'ToursController@busqueda')->name('tourBusqueda');
Route::post('tours/busqueda/detalles', 'ToursController@detalles')->name('tourDetalles');

Route::post('circuitos/busqueda', 'CircuitosController@busqueda')->name('circuitoBusqueda');
Route::post('circuitos/busqueda/detalles', 'CircuitosController@detalleCircuito')->name('circuitoDetalles');

Route::post('actividades/busqueda', 'ActividadesController@busqueda')->name('actividadBusqueda');
Route::post('actividades/busqueda/detalles', 'ActividadesController@detalleActividad')->name('actividadDetalles');

Route::post('objAsesor', 'ServiciosController@objAsesor')->name('objAsesor');

Route::post('addToCart', 'ServiciosController@addToCart')->name('addToCart');
Route::post('carrito', 'ServiciosController@carrito')->name('carrito');
Route::post('deleteToCart', 'ServiciosController@deleteToCart')->name('deleteToCart');

Route::get('carrito/Reservacion', 'ServiciosController@CompletarDatos')->name('carrito.CompletarDatos');

Route::get('carrito/finalizar', 'ServiciosController@finalizar')->name('carrito.finalizar');

Route::post('carrito/PreAutorizar', 'ServiciosController@PreAutorizar')->name('carrito.PreAutorizar');

Route::post('TourDatos', 'ServiciosController@TourDatos')->name('TourDatos');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
