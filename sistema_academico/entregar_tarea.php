<?php
// entregar_tarea.php
session_start();
if(!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Alumno'){
    header("Location: login.php");
    exit();
}
include('config.php');
include('header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_curso = $_POST['id_curso'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha_entrega = date('Y-m-d'); // Fecha actual
    $id_alumno = $_SESSION['id_usuario'];
    
    // Procesar subida de archivo (simplificado)
    $archivo = "";
    if(isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0){
        $nombreArchivo = basename($_FILES['archivo']['name']);
        $destino = "uploads/" . $nombreArchivo;
        if(move_uploaded_file($_FILES['archivo']['tmp_name'], $destino)){
            $archivo = $destino;
        }
    }
    
    $sql = "INSERT INTO Entregas (id_alumno, id_curso, titulo, descripcion, fecha_entrega, archivo, estado)
            VALUES (?, ?, ?, ?, ?, ?, 'Pendiente')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissss", $id_alumno, $id_curso, $titulo, $descripcion, $fecha_entrega, $archivo);
    
    if ($stmt->execute()) {
        echo "<p>Tarea entregada exitosamente.</p>";
    } else {
        echo "<p>Error al entregar tarea: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Mostrar cursos en los que el alumno está inscrito
$sql = "SELECT c.id_curso, c.nombre_curso FROM Cursos c
        JOIN Matricula m ON c.id_curso = m.id_curso
        WHERE m.id_alumno = " . $_SESSION['id_usuario'];
$result = $conn->query($sql);
?>

<h2>Entregar Tarea/Evidencia</h2>
<form method="POST" action="entregar_tarea.php" enctype="multipart/form-data">
    <label for="id_curso">Selecciona el Curso:</label>
    <select name="id_curso" required>
        <?php while($row = $result->fetch_assoc()): ?>
            <option value="<?php echo $row['id_curso']; ?>"><?php echo $row['nombre_curso']; ?></option>
        <?php endwhile; ?>
    </select>
    
    <label for="titulo">Título de la Tarea:</label>
    <input type="text" name="titulo" required>
    
    <label for="descripcion">Descripción:</label>
    <textarea name="descripcion" required></textarea>
    
    <label for="archivo">Adjuntar Archivo:</label>
    <input type="file" name="archivo">
    
    <input type="submit" value="Entregar Tarea">
</form>

<?php
include('footer.php');
?>
