<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=reporte_asistencia.xls");

include('config.php');

echo "Fecha\tAlumno\tCurso\tEstado\n";

$sql = "SELECT a.fecha, u.nombre AS alumno, c.nombre_curso, a.estado 
        FROM Asistencia a
        JOIN Usuarios u ON a.id_alumno = u.id_usuario
        JOIN Cursos c ON a.id_curso = c.id_curso";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo $row['fecha'] . "\t" . $row['alumno'] . "\t" . $row['nombre_curso'] . "\t" . $row['estado'] . "\n";
}
?>
