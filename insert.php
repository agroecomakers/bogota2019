<?php
if(isset($_POST) && !empty($_POST)){
    date_default_timezone_set('America/Bogota');
    $servername = "localhost";
    $database = "register";
    $username = "root";
    $password = "agromakers2019";
    //$username = "user";
    //$password = "";
    // Create connection
    $conn = mysqli_connect($servername, $username, null, $database);


    if($conn){
        $data = array();

        //Validar correo
        if(0 == preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $_POST['EMAIL'])){
            array_push($data, [
                'id' => 'EMAIL',
                'message' => 'Correo invalido.'
            ]);
        }

        //Validar correo base de datos
        $tildes = $conn->query("SET NAMES 'utf8'"); //Para que se muestren las tildes
        $result = mysqli_query($conn, "SELECT * FROM register WHERE email = '" . $_POST['EMAIL'] . "'");
        mysqli_data_seek ($result, 0);
        $extraido = mysqli_fetch_array($result);
        if(!empty($extraido) && empty($data)){
            array_push($data, [
                'id' => 'EMAIL',
                'message' => 'Correo existente.'
            ]);
        }

        //Validar cedula
        if(!isset($_POST['DOCUMENT']) || empty($_POST['DOCUMENT'])){
            array_push($data, [
                'id' => 'DOCUMENT',
                'message' => 'Número de identidad invalido.'
            ]);
        }

        //Validar cedula base de datos
        $tildes = $conn->query("SET NAMES 'utf8'"); //Para que se muestren las tildes
        $result2 = mysqli_query($conn, "SELECT * FROM register WHERE document = '" . $_POST['DOCUMENT'] . "'");
        mysqli_data_seek ($result2, 0);
        $extraido2 = mysqli_fetch_array($result2);
        if(!empty($extraido2) && empty($data)){
            array_push($data, [
                'id' => 'DOCUMENT',
                'message' => 'Número de identidad existente.'
            ]);
        }

        //Validar telefono
        if(!is_numeric($_POST['PHONE']) || !(strlen($_POST['PHONE']) == 7 || strlen($_POST['PHONE']) == 10)){
            array_push($data, [
                'id' => 'PHONE',
                'message' => 'Telefono invalido(recuerde que debe ser número local o número celular).'
            ]);
        }

        //Validar nombre
        if(!isset($_POST['NAME']) || empty($_POST['NAME'])){
            array_push($data, [
                'id' => 'NAME',
                'message' => 'Nombre invalido.'
            ]);
        }

        //Validar ocupación
        if(!isset($_POST['OCUPATION']) || empty($_POST['OCUPATION'])){
            array_push($data, [
                'id' => 'OCUPATION',
                'message' => 'Ocupación invalida.'
            ]);
        }

        //Validar dirección
        if(!isset($_POST['ADDRESS']) || empty($_POST['ADDRESS'])){
            array_push($data, [
                'id' => 'ADDRESS',
                'message' => 'Dirección invalida.'
            ]);
        }

        //Validar dirección
        if(!($_POST['DIAS'] == "Días 1 y 2" || $_POST['DIAS'] == "Día 1" || $_POST['DIAS'] == "Día 2")){
            array_push($data, [
                'id' => 'DIAS',
                'message' => 'Días invalidos.'
            ]);
        }

        //Validar si existio errores
        if(empty($data)){
            /*
            if (strtotime(date('Y-m-d H:i')) < strtotime('2019-12-08 24:00'))
            {
                $sql = "INSERT INTO register ( name, email, document, phone, ocupation, address, days, message, date) VALUES ('" . $_POST['NAME'] . "', '" . $_POST['EMAIL'] . "', '" . $_POST['DOCUMENT'] . "', '" . $_POST['PHONE'] . "', '" . $_POST['OCUPATION'] . "', '" . $_POST['ADDRESS'] . "', '" . $_POST['DIAS'] . "', '" . $_POST['MESSAGE'] . "', '" . date('Y-m-d H:i') . "')";
            }else{
                $sql = "INSERT INTO register ( name, email, document, phone, ocupation, address, days, message, date) VALUES ('" . $_POST['NAME'] . "', '" . $_POST['EMAIL'] . "', '" . $_POST['DOCUMENT'] . "', '" . $_POST['PHONE'] . "', '" . $_POST['OCUPATION'] . "', '" . $_POST['ADDRESS'] . "', 'Día 2', '" . $_POST['MESSAGE'] . "', '" . date('Y-m-d H:i') . "')";
            }*/
            $sql = "INSERT INTO register ( name, email, document, phone, ocupation, address, days, message, date) VALUES ('" . $_POST['NAME'] . "', '" . $_POST['EMAIL'] . "', '" . $_POST['DOCUMENT'] . "', '" . $_POST['PHONE'] . "', '" . $_POST['OCUPATION'] . "', '" . $_POST['ADDRESS'] . "', '" . $_POST['DIAS'] . "', '" . $_POST['MESSAGE'] . "', '" . date('Y-m-d H:i') . "')";

            if (mysqli_query($conn, $sql)) {
                  echo json_encode(['message' => 'Registro Exitoso', 'status' => true]);
            } else {
                  echo json_encode(['message' => 'Fallo conección a la base de datos', 'status' => false, 'error' => mysqli_error($conn)]);
            }

        }else{
            echo json_encode(['message' => 'Datos incorrectos', 'status' => false, 'validation' => $data]);
        }

    }else{
        echo json_encode(['message' => 'Fallo conección a la base de datos', 'status' => false]);
    }

    mysqli_close($conn);
}else{
    echo json_encode(['message' => 'No se registro', 'status' => false]);
}
