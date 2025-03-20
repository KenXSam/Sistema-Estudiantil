<?php
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id_usuario, nombre, contraseña, tipo FROM Usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['contraseña'])) {
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['tipo'] = $row['tipo'];

            if ($row['tipo'] == 'Gerente') {
                header("Location: dashboard_gerente.php");
                exit();
            } elseif ($row['tipo'] == 'Profesor') {
                header("Location: dashboard_profesor.php");
                exit();
            } else {
                header("Location: dashboard_alumno.php");
                exit();
            }
        } else {
            echo "<p>Contraseña incorrecta. <a href='login.php'>Intentar de nuevo</a></p>";
        }
    } else {
        echo "<p>Usuario no encontrado. <a href='register.php'>Regístrate aquí</a></p>";
    }
    $stmt->close();
}
$conn->close();
 
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Gerente') {
    header("Location: login.php");
    exit();
}

?>
