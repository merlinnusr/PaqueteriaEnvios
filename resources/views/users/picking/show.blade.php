@extends('layouts.app')
@section('content')

    <style>
        img {
            max-width: 300px;
            height: auto;
            max-height: 300px;

        }

        th {
            background: #534a4a
        }

        ;

    </style>
    <section class="">
        <div class="container bg-light jumbotron">
            <a href="{{ route('picking.index') }}" class="btn btn-outline-naranja">Regresar</a>
            <div class="row">
                <div class="col-12">
                    <h2 class="text-center mt-3">Detalles del paquete</h2>
                    <h4>Destinatario</h4>
                    <table class="table">

                        <tr>
                            <th>
                                Nombre:
                            </th>
                            <td>
                                {{ $picking->nombre }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Correo:
                            </th>
                            <td>
                                {{ $picking->correo }}

                            </td>
                        </tr>
                        <tr>
                            <th>
                                Celular:
                            </th>
                            <td>
                                {{ $picking->celular }}
                            </td>
                        </tr>


                    </table>
                    <h4>Paquete</h4>
                    <table class="table">
                        <tr>
                            <th>
                                Fecha de creacion
                            </th>
                            <td>
                                {{ $picking->fecha_creacion }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Oficina de entrega
                            </th>
                            <td>
                                @if (isset($sucursal))
                                    {{ $sucursal->nombre }} - {{ $sucursal->domicilio }} {{ $sucursal->colonia }}
                                    {{ $sucursal->ciudad }} {{ $sucursal->estado }}
                                @else 
                                    No recepcionado aun
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Codigo
                            </th>
                            <td>
                                {{ $sucursal->codigo ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Fecha de recepcion
                            </th>
                            <td>
                                {{ $picking->fecha_recepcion ? $picking->fecha_recepcion : 'No recepcionado aun' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Contraseña
                            </th>
                            <td>
                                @if ($picking->clave)
                                    {{ $picking->clave }}
                                    @if ($picking->fecha_entrega == null)
                                        <form class="d-online" action="/picking/resend" method="post">
                                            <input type="hidden" name="id" value="pedido['id']">
                                            <input type="submit" class="btn btn-primary"
                                                value="Reenviar contraseña al destinatario">
                                        </form>
                                    @endif
                                @else
                                    No Recepcionado aun
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Fecha de caducidad de la contraseña
                            </th>
                            <td>
                                {{ $picking->fecha_clave ? $picking->fecha_clave : 'No recepcionado aun' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                STATUS
                            </th>
                            <td>
                                @if (null != $picking->fecha_entrega)
                                    <span class='alert alert-success'>Entregado</span>
                                @elseif (null == $picking->fecha_recepcion)
                                    <span class='alert alert-warning'>No recepcionado aun</span>";
                                @elseif (null == $picking->fecha_entrega && null != $picking->fecha_recepcion && date('Y-m-d H:i:s') <= $picking->fecha_clave) {
                                    <span class='alert alert-info'>Listo para entregar </span>" ; 
                                @elseif (null == $picking->fecha_entrega && null !=$picking->fecha_recepcion && date('Y-m-d H:i:s') > $picking->fecha_clave) 
                                    @php
                                        $vencido = true;
                                    @endphp
                                    <span class='alert alert-danger'>BLOQUEADO</span>";
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Dimensiones
                            </th>
                            <td>
                                {{ $picking->dimensiones }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Costo
                            </th>
                            <td>
                                MXN $ {{ $picking->costo }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Foto del paquete:
                            </th>
                            <td>
                                <img src="assets/uploads/$pedido['foto_paquete']" style="max-width:300px;">
                            </td>
                        </tr>
                    </table>

                    @if (isset($vencido) && isset($vencido))
                        <div class="alert alert-danger text-center">
                            <p>
                                Tu paquete esta
                                <span class="h5">
                                    vencido por {{ $extradata->dias_vencidos }} dias,
                                </span>
                                por favor paga el total de
                                <span class="h5">
                                    $ {{ $total }}
                                </span>
                                para desbloquearlo y generar una nueva contraseña
                            </p>

                            <form action="{{ route('picking.update') }}" method="post">
                                <input type="hidden" name="paquete_id" value="">
                                <button type="submit" onClick="this.form.submit(); this.disabled=true; this.innerHtml='Espere'; " class="btn btn-primary boton_naranja">Pagar ahora</button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </section>
@endsection
