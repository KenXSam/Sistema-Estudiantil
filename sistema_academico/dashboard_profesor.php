<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Profesor') {
    header("Location: login.php");
    exit();
}
include('config.php');
include('header.php');
?>

<h2>Bienvenido, <?php echo $_SESSION['nombre']; ?> (Profesor)</h2>
<p>Aquí puedes gestionar cursos y asistencia.</p>

<ul>
    <li><a href="gestionar_cursos.php">Gestionar Cursos</a></li>
    <li><a href="gestionar_horarios.php">Gestionar Horario</a></li>
    <li><a href="registrar_asistencia.php">Registrar Asistencia</a></li>
    <li><a href="evaluar_entregas.php">Evaluar Entregas y Asignar Notas</a></li>
</ul>

<p><a href="logout.php">Cerrar Sesión</a></p>

<?php include('footer.php'); ?>
