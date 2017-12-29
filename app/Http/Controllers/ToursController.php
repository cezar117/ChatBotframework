<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusquedaRequest;
use App\Http\Servicios\ServicioOtisa;
use Illuminate\Http\Request;

class ToursController extends Controller
{
    /**
     * Store the incoming blog post.
     *
     * @param  BusquedaRequest  $request
     * @return Response
     */
    public function busqueda(BusquedaRequest $request)
    {
        // dd($request);
        $servicioOtisa = new ServicioOtisa();
        $idSessionWSDL = ObtenerSession();
        $tours         = null;
        $checkIn       = $request->input('checkIn');
        $numAdultos    = $request->input('adultos');
        $numNinos      = $request->input('menores');
        $destino       = $request->input('destino');
        $edadMenores   = $request->input("edades") != null ? $request->input("edades") : array();
        $categoria     = $request->input('categoria') != null ? $request->input('categoria') : 1;
        $tipoServicio  = $request->input('tipo') != null ? $request->input('tipo') : 1;
        $idioma        = 2;
        $sendGrupo     = [
            "Adultos"     => $numAdultos,
            "Menores"     => $numNinos,
            "edadMenores" => $edadMenores,
        ];
        $respuestaTours = $servicioOtisa->ObtenerTours2($idSessionWSDL, $numAdultos, $numNinos, $edadMenores, 0, $destino, $tipoServicio, $idioma, $checkIn, null, null);
        if (isset($respuestaTours->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error)) {
            return $array = ["error" => $respuestaTours->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error->Mensaje];
        }
        if (isset($respuestaTours->xml->obtenerServiciosResult->TourRespuesta->ListaDeTours->Tour)) {

            $toursXml = $respuestaTours->xml->obtenerServiciosResult->TourRespuesta->ListaDeTours->Tour;

            foreach ($toursXml as $tour) {
                if (isset($tour->Packages->PackageTours)) {
                    $tour->Precio_final = getPrecioMinimoPaquete(objetoParseArray($tour->Packages->PackageTours));
                }

                $tours["" . $tour->Id . ""] = $tour;
            }
            //dd($tours);
            session(['tours' => $tours]);
            //
            $busqueda = [
                "adultos"         => $numAdultos,
                "menores"         => $numNinos,
                "destino"         => getNombreDestino($destino),
                "idioma"          => $idioma,
                "tipoServicio"    => $tipoServicio,
                "edadMenores"     => $edadMenores,
                "idioma"          => $idioma,
                "categoria"       => $categoria,
                "checkIn"         => $checkIn,
                "habitacionesObj" => $sendGrupo,
            ];

            session(['BusquedaProducto' => $busqueda]);
            $array = [
                "datosXML" => $tours,
                "busqueda" => $busqueda,
                "error"    => null,
            ];

            return $array;
        } else {
            return $array = [
                "error" => "ocurrio un error en el sistema o no se devolvieron datos",
                "obj"   => $respuestaTours];
        }
    }

    public function detalles(Request $request)
    {
        // dd($request);
        $idSessionWSDL = ObtenerSession();
        $servicioOtisa = new ServicioOtisa();
        $idTour        = $request->input("idproducto");
        $toursXml      = null;
        $busqueda      = session("BusquedaProducto");
        //dd($idTour);
        $respuestaTours = $servicioOtisa->ObtenerTours2($idSessionWSDL, $busqueda["adultos"], $busqueda["menores"], $busqueda["edadMenores"], $idTour, $busqueda["destino"][0]["id"], $busqueda["tipoServicio"], $busqueda["idioma"], $busqueda["checkIn"], null, null);
        //dd($respuestaTours);

        if (isset($respuestaTours->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error)) {
            return $array = ["error" => $respuestaTours->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error->Mensaje];
        }

        if (isset($respuestaTours->xml->obtenerServiciosResult->TourRespuesta->ListaDeTours->Tour)) {
            $toursXml = $respuestaTours->xml->obtenerServiciosResult->TourRespuesta->ListaDeTours->Tour;

            $detallesTour = $servicioOtisa->ObtenerTourDetalle($idSessionWSDL, $idTour);
            //dd($detallesTour);
            if (isset($detallesTour->xml->obtenerServiciosResult->TourDetalleRespuesta->ListaDeToursDetalle->TourDetalles)) {

                $tour = $detallesTour->xml->obtenerServiciosResult->TourDetalleRespuesta->ListaDeToursDetalle->TourDetalles;

                if (isset($toursXml->Packages->PackageTours)) {
                    $packageTours = objetoParseArray($toursXml->Packages->PackageTours);
                    foreach ($packageTours as $paquete) {

                        $paquetes["" . $paquete->PackageId . ""] = $paquete;

                    }
                    session(['productoPaquetes' => $paquetes]);
                    if (!isset($toursXml->Precio_final)) {
                        $toursXml->Precio_final    = getPrecioMinimoPaquete(objetoParseArray($packageTours));
                        $toursXml->CategoriaString = $toursXml->Categorias;

                    } else {
                        $toursXml->Precio_final    = 0.0;
                        $toursXml->CategoriaString = $toursXml->Categorias;
                    }
                } else {
                    $packageTours = null;
                }

                $datosProducto = [
                    "idProducto"   => $idTour,
                    "tipoProducto" => "Tour",
                    "producto"     => $tour,
                    "productoXML"  => $toursXml,
                ];

                session(['datosProducto' => $datosProducto]);
                $array = [
                    "paquetes" => objetoParseArray($packageTours),
                    "tour"     => $toursXml,
                    "detalle"  => $tour,
                ];

                return $array;
            } else {
                return $array = [
                    "error" => "ocurrio un error en el sistema (Detalles) o viene nulo",
                    "obj"   => $detallesTour];
            }

        } else {

            return $array = [
                "error" => "ocurrio un error en el sistema o viene nulo",
                "obj"   => $respuestaTours];
        }

    }

}
