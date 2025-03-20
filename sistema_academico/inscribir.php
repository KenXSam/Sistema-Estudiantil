<?php
include 'db.php';

$alumno_id = $_POST['alumno_id'];
$bloques = $_POST['bloques'];

foreach ($bloques as $bloque_id) {
    $conn->query("INSERT INTO inscripciones (alumno_id, bloque_id) VALUES ('$alumno_id', '$bloque_id')");
}

header("Location: horario.php?alumno_id=$alumno_id");
?>
