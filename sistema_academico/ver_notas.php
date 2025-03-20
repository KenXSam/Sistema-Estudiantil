<?php
// ver_notas.php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Alumno') {
    header("Location: login.php");
    exit();
}
include('config.php');
include('header.php');

$id_alumno = $_SESSION['id_usuario'];

// Consulta para obtener las notas, uniendo con Cursos y Entregas para obtener información adicional
$sql = "SELECT n.nota, n.comentario, n.fecha_asignacion, c.nombre_curso, e.titulo AS entrega
        FROM Notas n
        JOIN Cursos c ON n.id_curso = c.id_curso
        JOIN Entregas e ON n.id_entrega = e.id_entrega
        WHERE n.id_alumno = ?
        ORDER BY n.fecha_asignacion DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_alumno);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Mi Historial de Notas</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>Fecha</th>
        <th>Curso</th>
        <th>Tarea/Evidencia</th>
        <th>Nota</th>
        <th>Comentario</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['fecha_asignacion']; ?></td>
        <td><?php echo $row['nombre_curso']; ?></td>
        <td><?php echo $row['entrega']; ?></td>
        <td><?php echo $row['nota']; ?></td>
        <td><?php echo $row['comentario']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<?php
include('footer.php');
?>
