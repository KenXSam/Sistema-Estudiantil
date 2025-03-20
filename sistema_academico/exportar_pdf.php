<?php
require('fpdf.php'); // Asegúrate de que está en la misma carpeta
include('config.php'); // Conexión a la BD

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Reporte de Asistencia', 0, 1, 'C');
        $this->Ln(10);
    }
}

// Crear el PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 10);

// Encabezados de la tabla
$pdf->Cell(40, 10, 'Fecha', 1, 0, 'C');
$pdf->Cell(60, 10, 'Alumno', 1, 0, 'C');
$pdf->Cell(50, 10, 'Curso', 1, 0, 'C');
$pdf->Cell(30, 10, 'Estado', 1, 1, 'C');

// Obtener datos de la base de datos
$sql = "SELECT a.fecha, u.nombre AS alumno, c.nombre_curso, a.estado 
        FROM Asistencia a
        JOIN Usuarios u ON a.id_alumno = u.id_usuario
        JOIN Cursos c ON a.id_curso = c.id_curso";
$result = $conn->query($sql);

$pdf->SetFont('Arial', '', 10);

while ($row = $result->fetch_assoc()) {
    $pdf->Cell(40, 10, utf8_decode($row['fecha']), 1);
    $pdf->Cell(60, 10, utf8_decode($row['alumno']), 1);
    $pdf->Cell(50, 10, utf8_decode($row['nombre_curso']), 1);
    $pdf->Cell(30, 10, utf8_decode($row['estado']), 1);
    $pdf->Ln();
}

// Salida del PDF
$pdf->Output();
?>
