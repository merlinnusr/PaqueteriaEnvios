<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <style>
        table {
            width: 100%;
        }

        th,
        td {
            padding: 5px;
            margin: 0px;
            width: 25%;
            text-align: left;
        }

        h3 {
            margin: 0px !important;
        }

        h4 {
            margin: 0px !important;
        }

        .fondo {
            background: #DCDCDC;
        }

        .contendor {
            width: 700px;
            margin: 0px auto;
            font-family: Arial;
        }
    </style>

    <div class="contendor">
    
        <img src="{{asset('assets/images/logo2.png')}}" style="height: 50px;">
        <table>
            <tr>
                <th class="fondo">
                    <h4 style="margin: 0px !important;">Guia</h4>
                </th>
                <td style="text-align:right">
                    <h4 style="margin: 0px !important;">{{$data->tracking_number}}</h4>
                </td>
            </tr>
            <tr>
                <th class="fondo">
                    <h4 style="margin: 0px !important;">Paquetería</h4>
                </th>
                <td style="text-align:right">
                    <h4 style="margin: 0px !important;">{{$data->rate_provider}}</h4>
                </td>
            </tr>
            <tr>
                <th class="fondo">
                    <h4 style="margin: 0px !important;">Servicio</h4>
                </th>
                <td style="text-align:right">
                    <h4 style="margin: 0px !important;">{{$data->rate_servicelevel}}</h4>
                </td>
            </tr>
            <tr>
                <th class="fondo">
                    <h4 style="margin: 0px !important;">Número de servicio</h4>
                </th>
                <td style="text-align:right">
                    <h4 style="margin: 0px !important;">{{$data->purchase_number}}</h4>
                </td>
            </tr>
            <tr>
                <th class="fondo">
                    <h4 style="margin: 0px !important;">Costo</h4>
                </th>
                <td style="text-align:right">
                    <h4 style="margin: 0px !important;">$ {{$data->price}}
                    </h4>
                </td>
            </tr>

        </table>
        <table>
            <tr>
                <th class="fondo">
                    <h4 style="margin: 0px !important;">Alto</h4>
                </th>
                <th class="fondo">
                    <h4 style="margin: 0px !important;">Ancho</h4>
                </th>
                <th class="fondo">
                    <h4 style="margin: 0px !important;">Largo</h4>
                </th>
                <th class="fondo">
                    <h4 style="margin: 0px !important;">Peso</h4>
                </th>
            </tr>
            <tr>
                <td style="text-align:center">
                    <h4 style="margin: 0px !important;">{{$data->height}} cm</h4>
                </td>
                <td style="text-align:center">
                    <h4 style="margin: 0px !important;">{{$data->width}} cm</h4>
                </td>
                <td style="text-align:center">
                    <h4 style="margin: 0px !important;">{{$data->length}}cm</h4>
                </td>
                <td style="text-align:center">
                    <h4 style="margin: 0px !important;">{{$data->weight }} kg</h4>
                </td>

            </tr>

        </table>

        <p>Manifiesto bajo protesta de decir la verdad que la mercancía que ampara esta guía es legal y de procedencia licita. Por lo que deslindo a DAGPACKET de cualquier responsabilidad asumiendo total penalización jurídica, legal y las que resulten en caso de lo contrario
        </p>

        <div style="margin-top:30px;">
            <center>
                <h3>GRACIAS POR SU ENVIO</h3>
                <p>Por favor imprima este recibo y entregue su paquete en cualquiera de nuestras sucursales con su numero de servicio: {{$data->purchase_number}}</p>
                <p>Consulta nuestro listado de oficinas en <a href="https://dagpacket.com.mx/#ubicaciones" target="_blank">https://dagpacket.com.mx/#ubicaciones</a></p>
                <br><br>
                <p>
                   <u>Nombre y Firma</u>
                    
                </p>
            </center>

        </div>

    </div>


</body>

</html>