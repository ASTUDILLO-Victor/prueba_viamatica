<?php
namespace App\controllers;

use App\models\empleado;
use App\models\Task;

class TasksController
{
    public function create()
    {
        // todo el create cambio ya pude hacer que me funcione el codigo del querybilder
        // Validación y saneamiento de datos
        $cedula = filter_input(INPUT_POST, 'Ecedu', FILTER_SANITIZE_STRING);
        $name = filter_input(INPUT_POST, 'Enom', FILTER_SANITIZE_STRING);
        $ape = filter_input(INPUT_POST, 'Eape', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'Email', FILTER_VALIDATE_EMAIL);
        $password = password_hash(filter_input(INPUT_POST, 'pas', FILTER_SANITIZE_STRING), PASSWORD_DEFAULT);
        $id_rol = filter_input(INPUT_POST, 'Epo', FILTER_SANITIZE_NUMBER_INT);
        $sexo = filter_input(INPUT_POST, 'Ese', FILTER_SANITIZE_STRING);
        $celu = filter_input(INPUT_POST, 'Ecelu', FILTER_SANITIZE_STRING);
        $fecha = filter_input(INPUT_POST, 'Efe', FILTER_SANITIZE_STRING);
        $dire = filter_input(INPUT_POST, 'Edire', FILTER_SANITIZE_STRING);
        $tabla = filter_input(INPUT_POST, 'tabla', FILTER_SANITIZE_STRING);
    
        // Comprobación de campos obligatorios
        if (!$cedula || !$name || !$ape || !$email || !$password || !$id_rol || !$sexo || !$celu || !$fecha || !$dire) {
            header("Location: index.php?url=form&&error=missing_fields");
            exit();
        }
    
        try {
            // Crear nuevo empleado
            empleado::create([
                'cedula' => $cedula,
                'name' => $name,
                'ape' => $ape,
                'email' => $email,
                'password' => $password,
                'id_rol' => $id_rol,
                'sexo' => $sexo,
                'celu' => $celu,
                'fecha' => $fecha,
                'dire' => $dire,
            ]);
        } catch (\Exception $e) {
            header("Location: index.php?url=form&&error=database_error");
            exit();
        }
    
        // Redireccionar según la tabla
        $location = ($tabla == "tables") ? "index.php?url=tables&&success=1" : "index.php?url=tables3&&success1=1";
        header("Location: $location");
        exit();
    }


    public function toggle()
    {
        // Verificar si todos los parámetros están presentes
        $requiredParams = ['id', 'Ecedu', 'Enom', 'Eape', 'Email', 'Epo', 'Ese', 'Ecelu', 'Efe', 'Edire', 'tabla'];
        foreach ($requiredParams as $param) {
            if (!isset($_POST[$param])) {
                die('Error: Missing parameters.');
            }
        }

        // Sanitizar y validar entradas
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $cedula = filter_input(INPUT_POST, 'Ecedu', FILTER_SANITIZE_STRING);
        $name = filter_input(INPUT_POST, 'Enom', FILTER_SANITIZE_STRING);
        $ape = filter_input(INPUT_POST, 'Eape', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'Email', FILTER_VALIDATE_EMAIL);
        $id_rol = filter_input(INPUT_POST, 'Epo', FILTER_VALIDATE_INT);
        $sexo = filter_input(INPUT_POST, 'Ese', FILTER_SANITIZE_STRING);
        $celu = filter_input(INPUT_POST, 'Ecelu', FILTER_SANITIZE_STRING);
        $fecha = filter_input(INPUT_POST, 'Efe', FILTER_SANITIZE_STRING);
        $dire = filter_input(INPUT_POST, 'Edire', FILTER_SANITIZE_STRING);
        $tabla = filter_input(INPUT_POST, 'tabla', FILTER_SANITIZE_STRING);

        if ($id === false || $email === false || $id_rol === false) {
            die('Error: Invalid parameters.');
        }

        try {
            // Buscar la tarea
            $task = empleado::find($id);
            if (!$task) {
                die('Error: Task not found.');
            }

            // Actualizar la tarea con datos validados y saneados
            $task->update([
                'cedula' => $cedula,
                'name' => $name,
                'ape' => $ape,
                'email' => $email,
                'id_rol' => $id_rol,
                'estado' => 1,
                'sexo' => $sexo,
                'celu' => $celu,
                'fecha' => $fecha,
                'dire' => $dire,
            ]);

            // Redireccionar según el valor de 'tabla'
            if ($tabla === "tables") {
                header("Location: index.php?url=tables&success=1");
                exit();
            } elseif ($tabla === "tables3") {
                header("Location: index.php?url=tables3&success1=1");
                exit();
            } else {
                die('Error: Invalid table parameter.');
            }
        } catch (\Exception $e) {
            // Manejo de excepciones
            die('Error: ' . $e->getMessage());
        }
    }

    public function delete1($id, $tableSuffix)
    {
        // Validar el ID para asegurarse de que es un número entero
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            // Manejar el error de validación aquí, por ejemplo, redirigir con un mensaje de error
            return redirect('index.php?url=error');
        }

        // Buscar el registro
        $task = empleado::find($id);

        if ($task) {
            // Llamar al método de eliminación dinámicamente
            $deleteMethod = 'delete' . $tableSuffix;
            if (method_exists($task, $deleteMethod)) {
                $task->$deleteMethod();
            } else {
                // Manejar el error de método no encontrado
                return redirect('index.php?url=error');
            }
        } else {
            // Manejar el caso en que no se encuentre el registro
            return redirect('index.php?url=error');
        }

        // Redirigir a la URL correspondiente
        return redirect("index.php?url=tables" . $tableSuffix);
    }

    public function delete()
    {
        $task = empleado::find($_POST['id']);
        $task->delete();
        return redirect('index.php?url=tables');
    }

    public function delete2()
    {
        $task = empleado::find($_POST['id']);
        $task->delete2();
        return redirect('index.php?url=tables2');
    }

    public function delete3()
    {
        $task = empleado::find($_POST['id']);
        $task->delete3();
        return redirect('index.php?url=tables3');
    }

    public function delete4()
    {
        $task = empleado::find($_POST['id']);
        $task->delete4();
        return redirect('index.php?url=tables4');
    }

    public function registrar()
    {
        if (empty($_POST['Ecedu']) || empty($_POST['Enom']) || empty($_POST['Email']) || empty($_POST['pas']) || empty($_POST['Epo'])) {
            $mensaje = "Todos los campos son obligatorios. Por favor, llene todos los campos.";
            header("Location: formulario.php?login-form&mensaje=" . urlencode($mensaje));
            exit();
        }

        $servername = "localhost";
        $username = "u246287323_root";
        $password = "u1|G9Qd|9V";
        $database = "u246287323_airsafe";

        $conn = new \mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $sql_check_cedula = "SELECT COUNT(*) AS total FROM empleado WHERE cedula = ?";
        $stmt_check_cedula = $conn->prepare($sql_check_cedula);
        $stmt_check_cedula->bind_param("s", $_POST['Ecedu']);
        $stmt_check_cedula->execute();
        $result_check_cedula = $stmt_check_cedula->get_result();
        $row_check_cedula = $result_check_cedula->fetch_assoc();

        if ($row_check_cedula['total'] > 0) {
            $mensaje = "Cédula ya registrada.";
            $stmt_check_cedula->close();
            $conn->close();
            header("Location: index.php?url=login-form&mensaje=" . urlencode($mensaje));
            exit();
        }

        $sql = "INSERT INTO empleado (cedula, name, email, password, id_rol, estado) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $password = $_POST['pas'];
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $estado = 1;

        $stmt->bind_param("sssssi", $_POST['Ecedu'], $_POST['Enom'], $_POST['Email'], $hash, $_POST['Epo'], $estado);

        $stmt->execute();
        $stmt->close();
        $conn->close();

        header("Location: index.php?url=login-form&success=1");
        exit();
    }



    //validar cedula 
    public function validar()
    {
        $usuario = "root";
        $password = "";
        $servidor = "localhost";
        $basededatos = "proyecto";
        $con = mysqli_connect($servidor, $usuario, $password) or die("No se ha podido conectar al Servidor");
        mysqli_query($con, "SET SESSION collation_connection ='utf8_unicode_ci'");
        $db = mysqli_select_db($con, $basededatos) or die("Upps! Error en conectar a la Base de Datos");

        $cedula = $_REQUEST['cedula'];

        //Verificando si existe algun cliente en bd ya con dicha cedula asignada
//Preparamos un arreglo que es el que contendrá toda la información
        $jsonData = array();
        $selectQuery = ("SELECT cedula FROM empleado WHERE cedula='" . $cedula . "' ");
        $query = mysqli_query($con, $selectQuery);
        $totalCliente = mysqli_num_rows($query);

        //Validamos que la consulta haya retornado información
        if ($totalCliente <= 0) {
            $jsonData['success'] = 0;
            // $jsonData['message'] = 'No existe Cédula ' .$cedula;
            $jsonData['message'] = '';
        } else {
            //Si hay datos entonces retornas algo
            $jsonData['success'] = 1;
            $jsonData['message'] = '<p style="color:red;">Ya existe la Cédula <strong>(' . $cedula . ')<strong></p>';
        }

        //Mostrando mi respuesta en formato Json
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsonData);
    }
    public function correo()
    {
        // verificar_email.php
        header('Content-Type: application/json');

        // Conectar a la base de datos (ajusta los parámetros según tu configuración)
        $mysqli = new \mysqli('localhost', 'usuario', 'contraseña', 'base_de_datos');

        if ($mysqli->connect_error) {
            die('Error de conexión (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
        }

        // Obtener el correo electrónico del cuerpo de la solicitud
        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['email'];

        // Preparar y ejecutar la consulta
        $stmt = $mysqli->prepare('SELECT COUNT(*) FROM empleados WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        $mysqli->close();

        // Devolver el resultado en formato JSON
        echo json_encode(['existe' => $count > 0]);
    }

}
