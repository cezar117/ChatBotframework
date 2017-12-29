<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusquedaRequest;
use App\Http\Servicios\ServicioOtisa;
use Illuminate\Http\Request;

class ActividadesController extends Controller
{

    private $path = "actividades";

    public function busqueda(BusquedaRequest $request)
    {
        // dd($request);
        $servicioOtisa = new ServicioOtisa();
        $idSessionWSDL = ObtenerSession();
        $actividades   = null;
        $checkIn       = $request->input('checkIn');
        $adultos       = $request->input('adultos');
        $menores       = $request->input('menores');
        $grupo         = 1;
        $destino       = $request->input('destino');
        $edadMenores   = $request->input("edades1") != null ? $request->input("edades1") : array();
        $categoria     = $request->input('categoria') != null ? $request->input('categoria') : 1;
        $tipoServicio  = $request->input('tipo') != null ? $request->input('tipo') : 1;
        $idioma        = 1;
        $idActividad   = 0;
        $sendGrupo     = [
            "Adultos"     => $adultos,
            "Menores"     => $menores,
            "edadMenores" => $edadMenores,
        ];
        // dd($sendGrupo);
        //    $respuestaTours = $servicioOtisa->ObtenerTours($idSessionWSDL,$numAdultos,$numNinos,$edadMenores,"",$destino,$tipoServicio,$idioma,$checkIn,null,null);
        //dd($edadMenores);
        $respuestaActividades = $servicioOtisa->ObtenerActividades2($idSessionWSDL, $adultos, $menores, $edadMenores, $idActividad, $destino, $tipoServicio, $idioma, $checkIn, null, null);
        dd($respuestaActividades);
        if (isset($respuestaActividades->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error)) {
            return $array = ["error" => $respuestaActividades->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error->Mensaje];
        }
        if (isset($respuestaActividades->xml->obtenerServiciosResult->ActividadesRespuesta->ListaDeActividades->Actividad)) {

            $actividadesXml = objetoParseArray($respuestaActividades->xml->obtenerServiciosResult->ActividadesRespuesta->ListaDeActividades->Actividad);
            // dd($actividadesXml);
            foreach ($actividadesXml as $actividad) {
                $actividades["" . $actividad->Id . ""] = $actividad;
            }

            session(['actividades' => $actividades]);

            $busqueda = [
                "adultos"         => $adultos,
                "menores"         => $menores,
                "destino"         => getNombreDestino($destino),
                "idioma"          => $categoria,
                "tipoServicio"    => $tipoServicio,
                "edadMenores"     => $edadMenores,
                "idioma"          => $idioma,
                "categoria"       => $categoria,
                "checkIn"         => $checkIn,
                "habitacionesObj" => $sendGrupo,
            ];

            // dd($busqueda);
            session(['BusquedaProducto' => $busqueda]);
            $array = [
                "datosXML" => $actividades,
                "busqueda" => $busqueda,
                "from"     => "resultados",
            ];
            return $array;

        } else {
            return $array = [
                "error" => 'error o viene vacioooo!',
                "obj"   => $respuestaActividades];
        }

    }

    public function detalleActividad(Request $request)
    {
        //dd($request);
        $idSessionWSDL  = ObtenerSession();
        $servicioOtisa  = new ServicioOtisa();
        $idActividad    = $request->input("idproducto");
        $actividadesXml = null;
        $paquetes       = null;
        $busqueda       = session("BusquedaProducto");

        $respuestaActividades = $servicioOtisa->ObtenerActividades2($idSessionWSDL, $busqueda["adultos"], $busqueda["menores"], $busqueda["edadMenores"], $idActividad, $busqueda["destino"], $busqueda["tipoServicio"], $busqueda["idioma"], $busqueda["checkIn"], null, null);

        if (isset($respuestaActividades->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error)) {
            return $array = ["error" => $respuestaHoteles->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error->Mensaje];
        }

        if (isset($respuestaActividades->xml->obtenerServiciosResult->ActividadesRespuesta->ListaDeActividades->Actividad)) {
            $actividadesXml = $respuestaActividades->xml->obtenerServiciosResult->ActividadesRespuesta->ListaDeActividades->Actividad;

            if (isset($actividadesXml->Packages->PackageActividad)) {
                $PackageActividad = objetoParseArray($actividadesXml->Packages->PackageActividad);
                foreach ($PackageActividad as $paquete) {
                    $paquetes["" . $paquete->PackageId . ""] = $paquete;

                }
                session(['productoPaquetes' => $paquetes]);
                if (!isset($actividadesXml->Precio_final)) {
                    $actividadesXml->Precio_final = getPrecioMinimoPaquete(objetoParseArray($PackageActividad));
                    $actividadesXml->Imagen       = $actividadesXml->Galeria->Imagen[0]->Nombre;
                    $actividadesXml->Categorias   = null;

                } else {
                    $actividadesXml->Precio_final = 0.0;
                    $actividadesXml->Imagen       = $actividadesXml->Galeria->Imagen[0]->Nombre;
                    $actividadesXml->Categorias   = null;

                }
            } else {
                $PackageActividad = null;
            }
            $datosProducto = [
                "idProducto"   => $idActividad,
                "tipoProducto" => "Actividad",
                "producto"     => $actividadesXml,
                "productoXML"  => $actividadesXml,
            ];

            session(['datosProducto' => $datosProducto]);

            $array = [
                "paquetes"  => objetoParseArray($PackageActividad),
                "actividad" => $actividadesXml,
                "detalle"   => $actividadesXml,
            ];

            return $array;

        } else {

            return $array = [
                "error" => "ocurrio un error en el sistema o viene nulo",
                "obj"   => $respuestaActividades];
        }

    }

}
