<?php

namespace App\Services;

class PickingService
{

    protected function isMine($pedido)
    {

        if ($pedido['cliente_id'] != auth()->id()) {
            echo "No encontrado";
            return false;
        }

        return true;
    }



    public function resend()
    {
        $id = $this->input->post('id');

        $paquete = $this->picking_model->buscar($id)[0];
        $data = $paquete;

        if (!$this->isMine($paquete))
            return;


        $oficina = $this->sucursal_model->buscar($paquete['sucursal_id'])[0];

        $this->load->library('email');
        $config = [
            'protocol' => 'smtp',
            "smtp_host" => 'mail.dagpacket.com.mx',
            "smtp_user" => 'no-reply@dagpacket.com.mx',
            "smtp_pass" => 'R[^}ZCk#~u3T',
            "smtp_port" => '465',
            'smtp_crypto' => 'ssl',
            'mailtype' => 'html',
            'wordwrap' => TRUE,
            'charset' => 'utf-8',
        ];
        $this->email->initialize($config);

        $this->email->from('dagpacket@gmail.com', 'Dagpacket');
        $this->email->to($paquete['correo'], $paquete['nombre']);
        $this->email->subject('Tienes un nuevo paquete por recoger');
        $this->email->message(
            "<h1>Tienes un nuevo paquete por recoger </h1><br><br>
                    Tu Código de paquete es : <strong>" . $paquete['codigo'] . "</strong><br>
                    Tu Contraseña es :<strong>" . $data['clave'] . "</strong><br>
                    Expira el :" . $data['fecha_clave'] . "<br>
                    Oficina : " . $oficina['nombre'] . "<br>
                    Ubicacion : " . $oficina['domicilio'] . " " . $oficina['colonia'] . " " . $oficina['ciudad'] . " " . $oficina['estado'] . "<br><br>
                    <h2>Instrucciones</h2><br>
                    <ol>
                    <li>Anota el código y contraseña o toma una captura de este correo</li>
                    <li>Dirigite a la oficina mencionada donde tenemos tu paquete</li>
                    <li>Entrega el código y contraseña asi como una identificación oficial</li>
                    <li>Recibe tu paquete</li></ol><br>
                    <br><br>Recuerda que tienes hasta el final del dia siguiente habil para recoger tu paquete o tu clave y código serán bloqueados (Revisa la fecha de caducidad)<br>
                    Si tu clave está bloqueada deberas pagar nuevamente al momento de recoger el paquete el valor de la recepcion por cada dia de retraso<br>
                    <h4>Horarios generales</h4>
                    <ul><li>Lunes a Viernes :  10am a 6pm</li>
                    <li>Sabados : 10am a 2pm</li>
                    <li>Domingo : Descanso (los domingos no generan recargo)</li>
                    </ul><br>
                    <strong>Dagpacket Agradece tu preferencia</strong><br><br><img src='" . base_url() . "/assets/img/logo2.png' style='width:200px;height:auto;' >"
        );
        $this->email->send();




        return redirect('/picking/detalles?idPedido=' . $paquete['id']);
    }



    public function detalles()
    {

        $idPedido = htmlspecialChars($this->input->post('idPedido') ?? $this->input->get('idPedido'));

        if ($idPedido < 1) {
            echo "Not found";
            return;
        }
        $data['pedido'] =  $this->picking_model->buscar($idPedido)[0];



        if (!$this->isMine($data['pedido']))
            return;

        if ($data['pedido']['sucursal_id'] != null)
            $data['sucursal'] = $this->sucursal_model->buscar($data['pedido']['sucursal_id'])[0];

        $this->load->view('header', return_user());
        $this->load->view('picking/detalles', $data);
    }

    public function reniew()
    {
        $paquete_id = $this->input->post('paquete_id');
        $pedido = $this->picking_model->buscar($paquete_id)[0];

        if (!$this->isMine($pedido))
            return;


        $ahora = strtotime(date('Y-m-d H:i:s'));
        $fecha_clave = strtotime($pedido['fecha_clave']);

        $diasVencido = $this->difDias($fecha_clave, $ahora);


        $precioDia = 0;
        if ($pedido['dimensiones'] == 'carta') {
            $precioDia = 10;
        }
        if ($pedido['dimensiones'] == '20x20x20') {
            $precioDia = 10;
        }
        if ($pedido['dimensiones'] == '30x30x30') {
            $precioDia = 13;
        }
        if ($pedido['dimensiones'] == '60x60x60') {
            $precioDia = 20;
        }

        $totalApagar = $diasVencido * $precioDia;

        $this->comprobarSaldo($totalApagar);

        $saldo = $this->wallet_model->obtener_wallet_usuario(return_user()['id'])[0]['wallet'];
        $nuevoSaldo = $saldo - $totalApagar;

        $this->wallet_model->actualizar_wallet(return_user()['id'], $nuevoSaldo);
        $precioAnterior = $pedido['costo'];
        $precioTotal = $precioAnterior + $totalApagar;

        $this->picking_model->actualizar($pedido['id'], array('costo' => $precioTotal));
        $this->generarClave($pedido['id']);

        redirect('picking/detalles?idPedido=' . $pedido['id']);
    }
    function pdf()
    {
        $idPedido = $this->input->post('idPedido');
        $pedido = $this->picking_model->buscar($idPedido);


        if (!$this->isMine($pedido[0]))
            return;

        if (null != $pedido[0]['sucursal_id'])
            $sucursal = $this->sucursal_model->buscar($pedido['0']['sucursal_id']);

        $data = array(
            'pedido' => $pedido[0],
            'sucursal' => $sucursal[0] ?? null
        );


        $html = $this->load->view('picking/epago', $data, true);
        $this->dompdf->load_html($html);
        $this->dompdf->set_option('isRemoteEnabled', true);
        $this->dompdf->set_paper('A4', 'portrait');
        $this->dompdf->render();
        $this->dompdf->stream('recibo_de_solicitud.pdf', array('Attachment' => 1));
    }

    public function  saldoInsuficiente()
    {


        $this->load->view('header', return_user());
        $this->load->view('picking/no_saldo');
    }
    public function nuevo($customErrors = null)
    {

        $this->comprobarSaldo(10);


        $this->load->helper(array('form', 'url'));

        $this->logged();

        $data = [
            'sucursales' => $this->sucursal_model->obtener_todas(),
            'customErrors' => $customErrors
        ];

        $this->load->view('header', return_user());
        $this->load->view('picking/nuevo', $data);
    }


    protected function set_rules()
    {
        //mesages        
        $this->form_validation->set_message('required', 'El campo {field} es requerido');
        $this->form_validation->set_message('alpha_numeric_spaces', 'El campo {field} solo admite letras, números y espacios');
        $this->form_validation->set_message('numeric', 'El campo field} solo admite números');
        $this->form_validation->set_message('integer', 'El campo {field} no ha sido seleccionado correctamente');
        $this->form_validation->set_message('valid_email', 'El campo {field} no es un correo válido');


        //rules
        $this->form_validation->set_rules('nombre', 'Nombre', 'required|alpha_numeric_spaces');
        $this->form_validation->set_rules('celular', 'Celular', 'required|alpha_numeric_spaces');
        $this->form_validation->set_rules('correo', 'Correo', 'required|valid_email');
        $this->form_validation->set_rules('contenido', 'Contenido', 'required|alpha_numeric_spaces');
        $this->form_validation->set_rules('paquete', 'Paquete', 'required|integer');
    }

    protected function comprobarSaldo($price)
    {

        $saldo = $this->wallet_model->obtener_wallet_usuario(return_user()['id'])[0]['wallet'];
        if ($saldo < $price) {
            redirect('picking/saldoInsuficiente');
        }
    }



    public function store()
    {

        $this->set_rules();
        if ($this->form_validation->run() == FALSE) {

            $this->nuevo();
            return;
        }


        $price = $this->getPrice();
        if (!$price) {

            $customErrors[] = 'El paquete supera las dimenciones permitidas';
            $this->nuevo($customErrors);
            return;
        }




        $comprobacionCupon  = $this->obtenerDescuento($price);



        $price = $comprobacionCupon['precio'];

        $this->comprobarSaldo($price);




        $foto = $this->saveImage('foto');

        if (isset($foto['error'])) {
            $customErrors[] = 'La fotografia del paquete no fue posible subir. Comprueba que sea formato JPG, PNG o GIF y Tamaño maximo de 3mb';
        }
        if (isset($customErrors)) {
            $this->nuevo($customErrors);
            return;
        }




        $dataSave = [
            'cliente_id' => return_user()['id'],
            'nombre' => $this->input->post('nombre'),
            'celular' => $this->input->post('celular'),
            'correo' => $this->input->post('correo'),
            'contenido' => $this->input->post('contenido'),
            'foto_paquete' => $foto['success']['file_name'],
            'dimensiones' => $this->getDimensiones(),
            'costo' => $price,
            'codigo' => uniqid(),


        ];


        $id_nuevo = $this->picking_model->crear($dataSave);

        if ($comprobacionCupon['esValido']) {
            $this->cupones_model->usar($comprobacionCupon['cupon_id'], $id_nuevo, return_user()['id'], 'picking');
        }


        $saldo = $this->wallet_model->obtener_wallet_usuario(return_user()['id'])[0]['wallet'];
        $resto = $saldo - $price;
        $this->wallet_model->actualizar_wallet(return_user()['id'], $resto);


        redirect('picking?ok=true&id=' . $id_nuevo);
    }

    ////////////////////////////////protected


    protected function obtenerDescuento($precioOriginal)
    {



        $nombre = $this->input->post('cupon');
        $for = $this->input->post('for') ?? 'picking';
        $price = $precioOriginal;



        $response = $this->cupones_model->comprobarCupon($nombre, $for, $price);



        if ($response['response'] == true) {
            return [
                'esValido' => true,
                'precio' => $response['precio'],
                'cupon_id' => $response['cupon']['id']
            ];
        }


        return [
            'esValido' => false,
            'precio' => $precioOriginal
        ];
    }

    protected  function difDias($fecha_clave, $hoy)
    {
        $fecha_clave = strtotime(date('Y-m-d', $fecha_clave));
        $hoy = strtotime(date('Y-m-d', $hoy));

        $days = 0;
        if ($fecha_clave >= $hoy) {
            return 0;
        }

        while ($fecha_clave < $hoy) {
            $fecha_clave += 86400;

            if (strtotime('Sunday this week', $fecha_clave) != $fecha_clave) {
                $days++;
            }
        }
        return $days;
    }






    protected function generarClave($id)
    {
        $paquete = $this->picking_model->buscar($id)[0];
        $data['clave'] = uniqId();
        $data['fecha_clave'] = $this->getPassDate();
        $oficina = $this->sucursal_model->buscar($paquete['sucursal_id'])[0];

        $this->load->library('email');
        $config = [
            'protocol' => 'smtp',
            "smtp_host" => 'mail.dagpacket.com.mx',
            "smtp_user" => 'no-reply@dagpacket.com.mx',
            "smtp_pass" => 'R[^}ZCk#~u3T',
            "smtp_port" => '465',
            'smtp_crypto' => 'ssl',
            'mailtype' => 'html',
            'wordwrap' => TRUE,
            'charset' => 'utf-8',
        ];

        $this->email->initialize($config);

        $this->email->from('correo@gmail.com', 'Dagpacket');
        $this->email->to($paquete['correo'], $paquete['nombre']);
        $this->email->subject('Tienes un nuevo paquete por recoger');
        $this->email->message(
            "Tienes un nuevo paquete por recoger <br>" .
                "Tu Codigo de paquete es :" . $paquete['codigo'] . "<br>" .
                "Tu Contraseña es :" . $data['clave'] . "<br>" .
                "Expira el :" . $data['fecha_clave'] . "<br>" .
                "Oficina : " . $oficina['nombre'] . "<br>" .
                "Ubicacion : " . $oficina['domicilio'] . " " . $oficina['colonia'] . " " . $oficina['ciudad'] . " " . $oficina['estado'] . "<br>" .
                "<br>Entrega el codigo y contraseña en tu paqueteria y la identificacion original para recoger tu paquete.Recuerda que tienes aproximadamente 24 horas para recoger tu paquete o tu clave y código serán bloqueados, para desbloquearlo deberas pagar nuevamente el valor de la recepcion por cada dia de retraso" . "<br>" .
                "Dagpacket Agradece tu preferencia"
        );
        $this->email->send();



        $this->picking_model->actualizar($id, $data);
    }

    protected function getPassDate()
    {

        $today = strtotime('today');
        if (
            strtotime('Monday this week') ==  $today ||
            strtotime('Tuesday this week') ==  $today ||
            strtotime('Wednesday this week') ==  $today ||
            strtotime('Thursday this week') ==  $today ||
            strtotime('Friday this week') ==  $today
        ) {
            return date('Y-m-d H:i:s', strtotime('tomorrow 23:00'));
        } else if (strtotime('Saturday this week') ==  $today || strtotime('Sunday this week') ==  $today) {
            return date('Y-m-d H:i:s', strtotime('next monday 23:00'));
        }

        return false;
    }


    public function getPrice($paquete)
    {
        if ($paquete == 1 || $paquete == 2) {
            return 10;
        } else if ($paquete == 3) {
            return 13;
        } else if ($paquete == 4) {
            return 20;
        } else {
            return false;
        }
    }

    public function uploadImage($photo)
    {
        $imageName = time() . '.' . $photo->extension();
        $photo->move(public_path('uploads/images/picking'), $imageName);
        return "uploads/images/picking/{$imageName}";
    }
    public  function getDimensiones($paquete)
    {
        if ($paquete == 1) {
            return 'carta';
        } else if ($paquete == 2) {
            return '20x20x20';
        } else if ($paquete == 3) {
            return '30x30x30';
        } else if ($paquete == 4) {
            return '60x60x60';
        } else {
            return false;
        }
    }


    protected function logged()
    {
        if (is_logged_in() == false) {
            redirect('login');
        }
    }


    protected function saveImage($file)
    {

        $config['upload_path'] = 'assets/uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['remove_spaces'] = TRUE;
        $config['encrypt_name'] = TRUE;
        $config['max_size'] = 3072;


        $data = [];
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($file)) {
            //*** ocurrio un error
            $data['error'] = $this->upload->display_errors();
            return $data;
        }

        $data['success'] = $this->upload->data();


        return $data;
    }
}
