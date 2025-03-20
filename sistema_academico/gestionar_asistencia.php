<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Gerente') {
    header("Location: login.php");
    exit();
}
include('config.php');
include('header.php');

$sql = "SELECT a.fecha, u.nombre AS alumno, c.nombre_curso, a.estado 
        FROM Asistencia a
        JOIN Usuarios u ON a.id_alumno = u.id_usuario
        JOIN Cursos c ON a.id_curso = c.id_curso
        ORDER BY a.fecha DESC";
$result = $conn->query($sql);
?>

<h2 class="text-center mb-4">Gestión de Asistencia</h2>

<table class="table table-striped">
    <tr>
        <th>Fecha</th>
        <th>Alumno</th>
        <th>Curso</th>
        <th>Estado</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['fecha']; ?></td>
        <td><?php echo $row['alumno']; ?></td>
        <td><?php echo $row['nombre_curso']; ?></td>
        <td><?php echo $row['estado']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include('footer.php'); ?>
