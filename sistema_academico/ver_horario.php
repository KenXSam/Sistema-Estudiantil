<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Alumno') {
    header("Location: login.php");
    exit();
}
include('config.php');
include('header.php');

$id_alumno = $_SESSION['id_usuario'];

// Consulta para obtener los horarios del alumno
$sql = "SELECT c.nombre_curso, h.dia, h.hora_inicio, h.hora_fin, h.aula, u.nombre AS profesor 
        FROM Matricula m
        JOIN Horarios h ON m.id_curso = h.id_curso
        JOIN Cursos c ON h.id_curso = c.id_curso
        JOIN Usuarios u ON h.id_profesor = u.id_usuario
        WHERE m.id_alumno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_alumno);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<h2 class="text-center mb-4">Mi Horario de Clases</h2>

<?php if ($resultado->num_rows > 0): ?>
<table border="1" class="table">
    <tr>
        <th>Curso</th>
        <th>Profesor</th>
        <th>Día</th>
        <th>Hora Inicio</th>
        <th>Hora Fin</th>
        <th>Aula</th>
    </tr>
    <?php while ($fila = $resultado->fetch_assoc()): ?>
    <tr>
        <td><?php echo $fila['nombre_curso']; ?></td>
        <td><?php echo $fila['profesor']; ?></td>
        <td><?php echo $fila['dia']; ?></td>
        <td><?php echo $fila['hora_inicio']; ?></td>
        <td><?php echo $fila['hora_fin']; ?></td>
        <td><?php echo $fila['aula']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
<p>No tienes horarios asignados.</p>
<?php endif; ?>

<?php include('footer.php'); ?>
