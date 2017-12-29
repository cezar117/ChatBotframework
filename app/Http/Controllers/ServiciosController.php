<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use stdClass;

set_time_limit(0);
class ServiciosController extends Controller
{

    public function ObjAsesor(Request $request)
    {
        $BusquedaProducto = session('BusquedaProducto'); //BusquedaProducto
        //dd($BusquedaProducto);
        $datosProducto = session('datosProducto');
        //dd($datosProducto);
        $paqueteProducto = session('productoPaquetes'); //productoPaquetes
        //dd($paqueteProducto);
        $Idioma       = isset($BusquedaProducto['idioma']) ? $BusquedaProducto['idioma'] : null;
        $TipoServicio = null;
        $datosPaquete = $paqueteProducto[$request->input("idpaquete")];

        $objetoAsesor[]                            = new stdclass();
        $objetoAsesor[0]->IdProducto               = $datosProducto["idProducto"];
        $objetoAsesor[0]->NombreProducto           = $datosProducto["productoXML"]->Nombre; //productoXML
        $objetoAsesor[0]->IdPaquete                = $request->input("idpaquete");
        $objetoAsesor[0]->TipoProducto             = $datosProducto["tipoProducto"];
        $objetoAsesor[0]->Latitud                  = isset($datosProducto["productoXML"]->Latitud) ? (float) $datosProducto["productoXML"]->Latitud : (float) $datosProducto["productoXML"]->rutas->RutaItinerario->Destinos->DestinoRuta[0]->Latitud; //productoXML
        $objetoAsesor[0]->Longitud                 = isset($datosProducto["productoXML"]->Longitud) ? (float) $datosProducto["productoXML"]->Longitud : (float) $datosProducto["productoXML"]->rutas->RutaItinerario->Destinos->DestinoRuta[0]->Longitud;
        $objetoAsesor[0]->ImagenPrincipal          = isset($datosProducto["productoXML"]->Imagen_main) ? $datosProducto["productoXML"]->Imagen_main : $datosProducto["productoXML"]->Imagen; //productoXML
        $objetoAsesor[0]->Precio                   = (int) $datosPaquete->PricePackage;
        $objetoAsesor[0]->Categoria                = isset($datosProducto["productoXML"]->Categoria) ? $datosProducto["productoXML"]->Categoria : 0; //productoXML
        $objetoAsesor[0]->CategoriaString          = isset($datosProducto["productoXML"]->CategoriaString) ? $datosProducto["productoXML"]->CategoriaString : $datosProducto["productoXML"]->Categorias; //productoXML
        $objetoAsesor[0]->DatosBusqueda            = new stdclass();
        $objetoAsesor[0]->DatosBusqueda->Categoria = $BusquedaProducto["categoria"];
        $objetoAsesor[0]->DatosBusqueda->Destino   = (int) $BusquedaProducto["destino"][0]["id"];
        //$objetoAsesor[0]->DatosBusqueda->Tipo         = 1;
        $objetoAsesor[0]->DatosBusqueda->CheckIn      = $BusquedaProducto["checkIn"];
        $objetoAsesor[0]->DatosBusqueda->CheckOut     = isset($BusquedaProducto["checkOut"]) ? $BusquedaProducto["checkOut"] : $BusquedaProducto["checkIn"];
        $objetoAsesor[0]->DatosBusqueda->Habitaciones = array();
        if ($datosProducto["tipoProducto"] == 'Hotel') {
            for ($i = 0; $i <= count($BusquedaProducto["habitacionesObj"]) - 1; $i++) {
                $objetoAsesor[0]->DatosBusqueda->Habitaciones[$i]              = new stdclass();
                $objetoAsesor[0]->DatosBusqueda->Habitaciones[$i]->Adultos     = $BusquedaProducto["habitacionesObj"][$i]["Adultos"];
                $objetoAsesor[0]->DatosBusqueda->Habitaciones[$i]->Menores     = $BusquedaProducto["habitacionesObj"][$i]["Menores"];
                $objetoAsesor[0]->DatosBusqueda->Habitaciones[$i]->EdadMenores = array();
                for ($j = 0; $j <= count($BusquedaProducto["habitacionesObj"][$i]["edadMenores"]) - 1; $j++) {
                    $objetoAsesor[0]->DatosBusqueda->Habitaciones[$i]->EdadMenores[$j] = (int) $BusquedaProducto["habitacionesObj"][$i]["edadMenores"][$j];
                }
            }
        } else {
            $objetoAsesor[0]->DatosBusqueda->Habitaciones[0]              = new stdclass();
            $objetoAsesor[0]->DatosBusqueda->Habitaciones[0]->Adultos     = (int) $BusquedaProducto["habitacionesObj"]["Adultos"];
            $objetoAsesor[0]->DatosBusqueda->Habitaciones[0]->Menores     = (int) $BusquedaProducto["habitacionesObj"]["Menores"];
            $objetoAsesor[0]->DatosBusqueda->Habitaciones[0]->EdadMenores = $BusquedaProducto["habitacionesObj"]["edadMenores"];

        }
        $objetoAsesor[0]->DatosBusqueda->noches = isset($BusquedaProducto["noches"]) ? $BusquedaProducto["noches"] : 0;
        $objetoAsesor[0]->Idioma                = $Idioma;
        $objetoAsesor[0]->TipoServicio          = $TipoServicio;
        // $objetoAsesor->DatosPaquete                   = $datosPaquete;
        // $objetoAsesor->datosProducto                  = $datosProducto["hotel"];
        // $objetoAsesor->datosDelHotel                  = $datosProducto["productoXML"];

        session(['datos' => $objetoAsesor]);
        //dd(json_encode($objetoAsesor));
        sleep(2);
        return json_encode($objetoAsesor);
    }

    public function destinos(Request $request)
    {
        try {
            $path     = app_path();
            $term     = $request->get('term');
            $Client   = new Client();
            $response = $Client->request('GET', 'https://otisab2b.com/secciones/home/buscador/1_0/autocomplete/rellenar_autocomplete.php?tipo=1&term=' . $term, ['verify' => $path . '\cacert.pem']);
            $json;
            $destinos = json_decode($response->getBody()->getContents());
            foreach ($destinos as $destino) {
                $json[] = [
                    "id"   => $destino->destino_id,
                    "text" => $destino->value,
                ];
            }
            return json_encode($json);
        } catch (BadResponseException $e) {
            $text   = json_decode($e->getResponse()->getBody()->getContents());
            $json[] = [
                "id"   => null,
                "text" => $text,
            ];
            return json_encode($json);
        }
    }
}
