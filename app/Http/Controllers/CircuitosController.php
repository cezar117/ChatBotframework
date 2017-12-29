<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusquedaRequest;
use App\Http\Servicios\ServicioOtisa;
use Illuminate\Http\Request;

class CircuitosController extends Controller
{

    private $path = "circuitos";
    private $ServicioOtisa;
    public $idSessionWSDL;

    public function busqueda(BusquedaRequest $request)
    {

        $ServicioOtisa = new ServicioOtisa();
        $idSessionWSDL = ObtenerSession();
        $checkIn       = $request->input('checkIn');
        $destino       = $request->input('destino');
        $idioma        = 2;
        $tipoServicio  = 1;
        $categoria     = $request->input('categoria') != null ? $request->input('categoria') : 1;
        $tipo_circuito = 1;
        $Adultos       = $request->input('adultos');
        $Ninos         = $request->input('menores');
        $edadMenores   = $request->input("edades1") != null ? $request->input("edades1") : array();
        $circuitos     = null;

        $grupo = array(
            "Adultos"     => $Adultos,
            "Menores"     => $Ninos,
            "edadMenores" => $edadMenores,
        );

        $RespuestaCircuitos = $ServicioOtisa->obtenerCircuitos2($idSessionWSDL, $checkIn, $destino, 0, $idioma, $tipo_circuito, $tipoServicio, array($grupo), 0);

        if (isset($RespuestaCircuitos->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error)) {
            //dd($respuestaTours);
            return $array = [
                "error" => $RespuestaCircuitos->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error,
            ];
        }

        if (isset($RespuestaCircuitos->xml->obtenerServiciosResult->CircuitoRespuesta->ListadeCircuitos->Circuito)) {

            $CircuitosXml = objetoParseArray($RespuestaCircuitos->xml->obtenerServiciosResult->CircuitoRespuesta->ListadeCircuitos->Circuito);
            //dd($CircuitosXml);
            foreach ($CircuitosXml as $circuito) {
                $circuitos["" . $circuito->Id . ""] = $circuito;
                if (isset($circuito->Packages->PackageTours)) {
                    $circuito->Precio_final = getPrecioMinimoPaquete(objetoParseArray($circuito->Packages->PackageTours));
                } else {
                    $circuito->Precio_final = 0.0;

                }

            }
            //dd($circuitos);
            session(['circuitos' => $circuitos]);

            $busquedaCircuitos = [
                "adultos"         => $Adultos,
                "menores"         => $Ninos,
                "destino"         => getNombreDestino($destino),
                "tipoCircuito"    => $tipo_circuito,
                "checkIn"         => $checkIn,
                "edadMenores"     => $edadMenores,
                "idioma"          => $idioma,
                "tipoServicio"    => $tipoServicio,
                "habitacionesObj" => $grupo,
            ];

            session(['BusquedaProducto' => $busquedaCircuitos]);

            $array = [
                "datosXML" => $circuitos,
                "busqueda" => $busquedaCircuitos,
                "error"    => null,
            ];

            return $array;

        } else {
            return $array = [
                "error" => "ocurrio un error en el sistema o todo nulo",
                "obj"   => $RespuestaCircuitos,
            ];
        }

    }

    public function detalleCircuito(Request $request)
    {
        $ServicioOtisa = new ServicioOtisa();
        $idSessionWSDL = ObtenerSession();
        $idCircuito    = $request->input("idproducto");
        $busqueda      = session('BusquedaProducto');
        $paquetes      = null;
        $grupo         = array(
            "Adultos"     => $busqueda["adultos"],
            "Menores"     => $busqueda["menores"],
            "edadMenores" => $busqueda["edadMenores"],
        );
        $CircuitosXml = null;

        $RespuestaCircuitos = $ServicioOtisa->obtenerCircuitos2($idSessionWSDL, $busqueda["checkIn"], 0, $idCircuito, $busqueda["idioma"], $busqueda["tipoCircuito"], $busqueda["tipoServicio"], array($grupo));

        if (isset($RespuestaCircuitos->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error)) {

            return $array = ["error" => $RespuestaCircuitos->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error->Mensaje];
        }

        if (isset($RespuestaCircuitos->xml->obtenerServiciosResult->CircuitoRespuesta->ListadeCircuitos->Circuito)) {

            $CircuitosXml = $RespuestaCircuitos->xml->obtenerServiciosResult->CircuitoRespuesta->ListadeCircuitos->Circuito;
            // dd($CircuitosXml);
            $detallesCircuitos = $ServicioOtisa->obtenerDetallesCircuito($idSessionWSDL, $idCircuito, $busqueda["idioma"], 'TERRESTRE', 'COMPARTIDO', 'SIN_GRUPO');

            if (isset($detallesCircuitos->xml->obtenerServiciosResult->CircuitoDetalleRespuesta->ListaDeCircuitosDetalles->CircuitoDetalles)) {

                $circuito = $detallesCircuitos->xml->obtenerServiciosResult->CircuitoDetalleRespuesta->ListaDeCircuitosDetalles->CircuitoDetalles;

                if (isset($CircuitosXml->Package->PackageCircuito)) {
                    $PackageCircuito = objetoParseArray($CircuitosXml->Package->PackageCircuito);
                    foreach ($PackageCircuito as $paquete) {
                        $paquetes["" . $paquete->PackageId . ""] = $paquete;
                    }
                    session(['productoPaquetes' => $paquetes]);
                    if (!isset($CircuitosXml->Precio_final)) {
                        $CircuitosXml->Precio_final = getPrecioMinimoPaquete(objetoParseArray($PackageCircuito));
                        $CircuitosXml->Categorias   = null;
                    } else {
                        $CircuitosXml->Precio_final = 0.0;
                        $CircuitosXml->Categorias   = null;
                    }
                } else {
                    $PackageCircuito = null;
                }

                $datosProducto = [
                    "idProducto"   => $idCircuito,
                    "tipoProducto" => "Circuito",
                    "producto"     => $circuito,
                    "productoXML"  => $CircuitosXml,
                ];

                session(['datosProducto' => $datosProducto]);
                $array = [
                    "paquetes" => objetoParseArray($PackageCircuito),
                    "circuito" => $CircuitosXml,
                    "detalle"  => $circuito,
                ];
                return $array;
            } else {
                return $array = [
                    "error" => "ocurrio un error en el sistema o viene nulo",
                    "obj"   => $detallesCircuitos];
            }

        } else {

            return $array = [
                "error" => "ocurrio un error en el sistema o viene nulo",
                "obj"   => $RespuestaCircuitos];
        }

    }

}
