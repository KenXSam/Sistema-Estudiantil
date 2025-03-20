<?php
// crear_curso.php
session_start();
if(!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Profesor'){
    header("Location: login.php");
    exit();
}
include('config.php');
include('header.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_curso = $_POST['nombre_curso'];
    $descripcion = $_POST['descripcion'];
    $id_profesor = $_SESSION['id_usuario'];

    $sql = "INSERT INTO Cursos (nombre_curso, descripcion, id_profesor) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nombre_curso, $descripcion, $id_profesor);
    
    if ($stmt->execute()) {
        // Insertar notificación para el Gerente
        $mensaje = "Nuevo curso creado: " . $nombre_curso;
        $sql_notif = "INSERT INTO Notificaciones (mensaje) VALUES (?)";
        $stmt_notif = $conn->prepare($sql_notif);
        $stmt_notif->bind_param("s", $mensaje);
        $stmt_notif->execute();

        echo "<p>Curso creado exitosamente.</p>";
    }
}





if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_curso = $_POST['nombre_curso'];
    $descripcion = $_POST['descripcion'];
    $id_profesor = $_SESSION['id_usuario'];
    
    $sql = "INSERT INTO Cursos (nombre_curso, descripcion, id_profesor) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nombre_curso, $descripcion, $id_profesor);
    
    if ($stmt->execute()) {
        echo "<p>Curso creado exitosamente.</p>";
    } else {
        echo "<p>Error al crear el curso: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
?>

<h2>Crear Curso</h2>
<form method="POST" action="crear_curso.php">
    <label for="nombre_curso">Nombre del Curso:</label>
    <input type="text" name="nombre_curso" required>
    
    <label for="descripcion">Descripción:</label>
    <textarea name="descripcion" required></textarea>
    
    <input type="submit" value="Crear Curso">
</form>

<?php include('footer.php'); ?>
