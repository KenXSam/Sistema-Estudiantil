<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Gerente') {
    header("Location: login.php");
    exit();
}
include('config.php');
include('header.php');

$sql = "SELECT n.nota, n.comentario, n.fecha_asignacion, u.nombre AS alumno, c.nombre_curso, e.titulo AS entrega
        FROM Notas n
        JOIN Usuarios u ON n.id_alumno = u.id_usuario
        JOIN Cursos c ON n.id_curso = c.id_curso
        JOIN Entregas e ON n.id_entrega = e.id_entrega
        ORDER BY n.fecha_asignacion DESC";
$result = $conn->query($sql);
?>

<h2 class="text-center mb-4">Reporte de Notas</h2>

<table class="table table-striped">
    <tr>
        <th>Fecha</th>
        <th>Alumno</th>
        <th>Curso</th>
        <th>Tarea</th>
        <th>Nota</th>
        <th>Comentario</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['fecha_asignacion']; ?></td>
        <td><?php echo $row['alumno']; ?></td>
        <td><?php echo $row['nombre_curso']; ?></td>
        <td><?php echo $row['entrega']; ?></td>
        <td><?php echo $row['nota']; ?></td>
        <td><?php echo $row['comentario']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include('footer.php'); ?>

