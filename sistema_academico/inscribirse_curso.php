<?php
// inscribirse_curso.php
session_start();
if(!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Alumno'){
    header("Location: login.php");
    exit();
}
include('config.php');
include('header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_curso = $_POST['id_curso'];
    $id_alumno = $_SESSION['id_usuario'];
    
    // Verificar si ya está inscrito
    $check_sql = "SELECT * FROM Matricula WHERE id_alumno = ? AND id_curso = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $id_alumno, $id_curso);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows == 0) {
        $sql = "INSERT INTO Matricula (id_alumno, id_curso) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_alumno, $id_curso);
        if ($stmt->execute()) {
            echo "<p>Inscripción realizada exitosamente.</p>";
        } else {
            echo "<p>Error al inscribirse: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Ya estás inscrito en este curso.</p>";
    }
    $check_stmt->close();
}

// Mostrar cursos disponibles
$sql = "SELECT * FROM Cursos";
$result = $conn->query($sql);
?>

<h2>Inscribirse en un Curso</h2>
<form method="POST" action="inscribirse_curso.php">
    <label for="id_curso">Selecciona un Curso:</label>
    <select name="id_curso" required>
        <?php while($row = $result->fetch_assoc()): ?>
            <option value="<?php echo $row['id_curso']; ?>"><?php echo $row['nombre_curso']; ?></option>
        <?php endwhile; ?>
    </select>
    <input type="submit" value="Inscribirse">
</form>

<?php
include('footer.php');
?>
