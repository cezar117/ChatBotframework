<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusquedaRequest;
use App\Http\Servicios\ServicioOtisa;
use Illuminate\Http\Request;

class HotelesController extends Controller
{
    /**
     * Store the incoming blog post.
     *
     * @param  BusquedaRequest  $request
     * @return Response
     */
    public function hotelesBusqueda(BusquedaRequest $request)
    {
        try {
            $servicioOtisa    = new ServicioOtisa();
            $idSessionWSDL    = ObtenerSession();
            $tipoAloj         = [];
            $tipoCategoria    = collect();
            $hoteles          = null; //variable de sesion
            $destino          = $request->input("destino");
            $checkIn          = $request->input("checkIn");
            $checkOut         = $request->input("checkOut");
            $habitaciones     = $request->input("habitaciones");
            $sendHabitaciones = array();
            $aAdultos         = $request->input("adultos");
            $aMenores         = $request->input("menores");
            $edadMenores      = [];

            for ($i = 1, $ii = 0; $i <= $habitaciones; $i++, $ii++) {

                $edades        = $request->input("edades" . $i) != null ? $request->input("edades" . $i) : array();
                $edadMenores[] = $edades;

                $sendHabitaciones[] = array(
                    "Adultos"     => intval($aAdultos[$ii]),
                    "Menores"     => intval($aMenores[$ii]),
                    "edadMenores" => $edades,
                );
            }

            $categoria            = -1;
            $idHotel              = 0;
            $fecha_inicio_detalle = new \DateTime($checkIn);
            $fecha_fin_detalle    = new \DateTime($checkOut);
            $num_noches           = $fecha_inicio_detalle->diff($fecha_fin_detalle);

            $respuestaHoteles = $servicioOtisa->obtenerHoteles2($idSessionWSDL, $categoria, $checkIn, $checkOut, $idHotel, $destino, $sendHabitaciones);
            //dd($respuestaHoteles);
            if (isset($respuestaHoteles->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error)) {
                if (is_array($respuestaHoteles->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error)) {
                    return $array = ["error" => $respuestaHoteles->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error];
                } else {
                    return $array = ["error" => $respuestaHoteles->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error->Mensaje];
                }
            }
            if (isset($respuestaHoteles->xml->obtenerServiciosResult->HotelRespuesta->ListadeHoteles->Hotel)) {
                $hotelesXml = $respuestaHoteles->xml->obtenerServiciosResult->HotelRespuesta->ListadeHoteles->Hotel;
                foreach ($hotelesXml as $hotel) {
                    $hotel->estrellasFormato       = getCategoria($hotel->Categoria, $hotel->CategoriaString);
                    $hoteles["" . $hotel->Id . ""] = $hotel;

                    if (!in_array($hotel->Aloj_tipo, $tipoAloj)) {
                        $tipoAloj[] = $hotel->Aloj_tipo;
                    }
                    if (!$tipoCategoria->contains("valorCategoria", $hotel->estrellasFormato->valorCategoria . "")) {
                        $tipoCategoria->push($hotel->estrellasFormato);
                    }
                }
                $busqueda = [
                    "adultos"         => $aAdultos,
                    "menores"         => $aMenores,
                    "edadMenores"     => $edadMenores,
                    "categoria"       => $categoria,
                    "destino"         => getNombreDestino($destino),
                    "checkIn"         => $checkIn,
                    "checkOut"        => $checkOut,
                    "habitaciones"    => $habitaciones,
                    "habitacionesObj" => $sendHabitaciones,
                    "noches"          => $num_noches->d,
                ];
                session(['BusquedaProducto' => $busqueda]);

                $array = [
                    "datosXML"          => $hoteles,
                    "busqueda"          => $busqueda,
                    "tiposAlojamientos" => $tipoAloj,
                    "tiposCategorias"   => $tipoCategoria->sortByDesc("valorCategoria"),
                    "error"             => null,
                ];

                return $array;

            } else {
                return $array = [
                    "error" => "ocurrio un error en el sistema",
                    "obj"   => $respuestaHoteles];

            }

        } catch (Exception $e) {

            return $array = ["error" => $e];

        }

    }

    public function detalleHotel(Request $request)
    {
        //dd($request);
        try {
            $servicioOtisa = new ServicioOtisa();
            $idSessionWSDL = ObtenerSession();
            $hotelesXml    = null;
            $paquetes      = null;
            $idHotel       = $request->input("idproducto");
            $busqueda      = session("BusquedaProducto");
            //dd($busqueda);
            $respuestaHoteles = $servicioOtisa->obtenerHoteles2($idSessionWSDL, $busqueda["categoria"], $busqueda["checkIn"], $busqueda["checkOut"], $idHotel, $busqueda["destino"][0]["id"], $busqueda["habitacionesObj"]);
            //dd($respuestaHoteles);

            if (isset($respuestaHoteles->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error)) {
                return $array = ["error" => $respuestaHoteles->xml->obtenerServiciosResult->ErrorRespuesta->Error->Error->Mensaje];
            }

            if (isset($respuestaHoteles->xml->obtenerServiciosResult->HotelRespuesta->ListadeHoteles->Hotel)) {

                $hotelesXml = $respuestaHoteles->xml->obtenerServiciosResult->HotelRespuesta->ListadeHoteles->Hotel;
                //dd($hotelesXml);
                $detallesHotel = $servicioOtisa->ObtenerHotelDetalles($idSessionWSDL, $idHotel, $hotelesXml->IdPaquetes);
                //dd($detallesHotel);
                if (isset($detallesHotel->xml->obtenerServiciosResult->HotelDetalleRespuesta->ListaDetalles->HotelDetalles)) {

                    $hotel = $detallesHotel->xml->obtenerServiciosResult->HotelDetalleRespuesta->ListaDetalles->HotelDetalles;
                    // dd($hotel);
                    if (isset($hotelesXml->Packages->PackageRooms)) {
                        $packageRooms = objetoParseArray($hotelesXml->Packages->PackageRooms);
                        foreach ($packageRooms as $paquete) {

                            $paquetes["" . $paquete->PackageId . ""] = $paquete;

                        }
                        session(['productoPaquetes' => $paquetes]);
                    } else {
                        $packageRooms = null;
                    }
                    // dd($paquetes);
                    //$busqueda = session('BusquedaProducto');

                    $datosProducto = [
                        "idProducto"   => $idHotel,
                        "tipoProducto" => "Hotel",
                        "producto"     => $hotel,
                        "productoXML"  => $hotelesXml,
                    ];
                    // array_push($busqueda, $array);
                    session(['datosProducto' => $datosProducto]);

                    $array = [
                        "paquetes" => objetoParseArray($packageRooms),
                        "hotel"    => $hotelesXml,
                        "detalle"  => $hotel,
                    ];

                    return $array;
                } else {
                    return $array = [
                        "error" => "ocurrio un error en el sistema o viene nulo",
                        "obj"   => $detallesHotel];
                }

            } else {

                return $array = [
                    "error" => "ocurrio un error en el sistema o viene nulo",
                    "obj"   => $respuestaHoteles];
            }

        } catch (Exception $e) {
            return $e;
        }
    }
}
