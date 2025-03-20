

<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Gerente') {
    header("Location: login.php");
    exit();
}
include('config.php');
include('header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO Usuarios (nombre, correo, contraseña, tipo) VALUES (?, ?, ?, 'Subadmin')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre, $correo, $password);
    $stmt->execute();
}


// Obtener estadísticas
$total_alumnos = $conn->query("SELECT COUNT(*) AS total FROM Usuarios WHERE tipo = 'Alumno'")->fetch_assoc()['total'];
$total_profesores = $conn->query("SELECT COUNT(*) AS total FROM Usuarios WHERE tipo = 'Profesor'")->fetch_assoc()['total'];
$total_cursos = $conn->query("SELECT COUNT(*) AS total FROM Cursos")->fetch_assoc()['total'];
?>

<h2 class="text-center mb-4">Panel de Gerente</h2>
<div class="row text-center">
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h3><?php echo $total_alumnos; ?></h3>
            <p>Alumnos Registrados</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h3><?php echo $total_profesores; ?></h3>
            <p>Profesores Registrados</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h3><?php echo $total_cursos; ?></h3>
            <p>Cursos Disponibles</p>
        </div>
    </div>
</div>

<h3 class="mt-4">Opciones de Gestión</h3>
<ul class="list-group">
    <li class="list-group-item"><a href="gestionar_profesores.php">Gestionar Profesores</a></li>
    <li class="list-group-item"><a href="gestionar_alumnos.php">Gestionar Alumnos</a></li>
    <li class="list-group-item"><a href="gestionar_cursos.php">Gestionar Cursos</a></li>
    <li class="list-group-item"><a href="gestionar_horarios.php">Gestionar Horarios</a></li>
    <li class="list-group-item"><a href="gestionar_asistencia.php">Gestionar Asistencia</a></li>
    <li class="list-group-item"><a href="reporte_asistencia.php">Reporte de Asistencia</a></li>
    <li class="list-group-item"><a href="reporte_notas.php">Reporte de Notas</a></li>
</ul>

<h3>Notificaciones</h3>
<ul class="list-group">
    <?php
    $sql = "SELECT * FROM Notificaciones WHERE visto = FALSE ORDER BY fecha DESC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<li class='list-group-item'>" . $row['mensaje'] . " <small>(" . $row['fecha'] . ")</small></li>";
        }
    } else {
        echo "<li class='list-group-item'>No hay notificaciones nuevas.</li>";
    }
    ?>
</ul>


<?php include('footer.php'); ?>


