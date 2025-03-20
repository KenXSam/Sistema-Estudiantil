<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Alumno') {
    header("Location: login.php");
    exit();
}
include('config.php');
include('header.php');
?>

<h2>Bienvenido, <?php echo $_SESSION['nombre']; ?> (Alumno)</h2>
<p>Aquí puedes ver tus cursos, notas y asistencia.</p>

<ul>
    <li><a href="inscribirse_curso.php">Inscribirse en Curso</a></li>
    <li><a href="entregar_tarea.php">Entregar Tarea/Evidencia</a></li>
    <li><a href="ver_asistencia.php">Ver Asistencia</a></li>
    <li><a href="ver_notas.php">Ver Notas</a></li> 
</ul>

<a href="ver_horario.php">
    <button class="btn btn-primary">Ver Mi Horario</button>
</a>

<p><a href="logout.php">Cerrar Sesión</a></p>

<?php include('footer.php'); ?>
