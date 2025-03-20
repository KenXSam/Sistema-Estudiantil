<?php
// ver_asistencia.php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Alumno') {
    header("Location: login.php");
    exit();
}
include('config.php');
include('header.php');

$id_alumno = $_SESSION['id_usuario'];

// Obtener el historial de asistencia del alumno
$sql = "SELECT a.fecha, a.estado, c.nombre_curso 
        FROM Asistencia a
        JOIN Cursos c ON a.id_curso = c.id_curso
        WHERE a.id_alumno = ?
        ORDER BY a.fecha DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_alumno);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    echo "<p>No se encontraron registros de asistencia.</p>";
    echo "<p>Registros encontrados: " . $result->num_rows . "</p>";

}
?>


<h2>Mi Historial de Asistencia</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>Fecha</th>
        <th>Curso</th>
        <th>Estado</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['fecha']; ?></td>
        <td><?php echo $row['nombre_curso']; ?></td>
        <td><?php echo $row['estado']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<?php
include('footer.php');
?>
