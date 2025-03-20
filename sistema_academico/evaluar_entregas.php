<?php
// evaluar_entregas.php
session_start();
if(!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Profesor'){
    header("Location: login.php");
    exit();
}
include('config.php');
include('header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_entrega = $_POST['id_entrega'];
    $nota = $_POST['nota'];
    $comentario = $_POST['comentario'];
    
    // Obtener id_alumno e id_curso de la entrega
    $sql_entrega = "SELECT id_alumno, id_curso FROM Entregas WHERE id_entrega = ?";
    $stmt_entrega = $conn->prepare($sql_entrega);
    $stmt_entrega->bind_param("i", $id_entrega);
    $stmt_entrega->execute();
    $result_entrega = $stmt_entrega->get_result();
    $data = $result_entrega->fetch_assoc();
    $id_alumno = $data['id_alumno'];
    $id_curso = $data['id_curso'];
    $stmt_entrega->close();
    
    // Insertar la nota
    $sql = "INSERT INTO Notas (id_alumno, id_curso, id_entrega, nota, comentario)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiids", $id_alumno, $id_curso, $id_entrega, $nota, $comentario);
    
    if ($stmt->execute()) {
        echo "<p>Nota asignada exitosamente.</p>";
    } else {
        echo "<p>Error al asignar nota: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Mostrar entregas pendientes de evaluación para los cursos del profesor
$sql = "SELECT e.id_entrega, e.titulo, e.descripcion, u.nombre, u.apellido, c.nombre_curso
        FROM Entregas e
        JOIN Usuarios u ON e.id_alumno = u.id_usuario
        JOIN Cursos c ON e.id_curso = c.id_curso
        WHERE c.id_profesor = " . $_SESSION['id_usuario'];
$result = $conn->query($sql);
?>

<h2>Evaluar Entregas</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>Título</th>
        <th>Descripción</th>
        <th>Alumno</th>
        <th>Curso</th>
        <th>Acción</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['titulo']; ?></td>
        <td><?php echo $row['descripcion']; ?></td>
        <td><?php echo $row['nombre'] . " " . $row['apellido']; ?></td>
        <td><?php echo $row['nombre_curso']; ?></td>
        <td>
            <form method="POST" action="evaluar_entregas.php">
                <input type="hidden" name="id_entrega" value="<?php echo $row['id_entrega']; ?>">
                <label for="nota">Nota:</label>
                <input type="number" name="nota" min="0" max="20" step="0.1" required>
                <br>
                <label for="comentario">Comentario:</label>
                <textarea name="comentario" required></textarea>
                <br>
                <input type="submit" value="Asignar Nota">
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php
include('footer.php');
?>
