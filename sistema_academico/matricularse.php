<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Alumno') {
    header("Location: login.php");
    exit();
}
include('config.php');
include('header.php');

$id_alumno = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_bloque = $_POST['id_bloque'];

    // Obtener cursos del bloque
    $sql_cursos = "SELECT id_curso FROM Bloque_Cursos WHERE id_bloque = ?";
    $stmt = $conn->prepare($sql_cursos);
    $stmt->bind_param("i", $id_bloque);
    $stmt->execute();
    $result_cursos = $stmt->get_result();
    
    while ($row = $result_cursos->fetch_assoc()) {
        $id_curso = $row['id_curso'];
        $sql_matricula = "INSERT INTO Matricula (id_alumno, id_curso) VALUES (?, ?)";
        $stmt_matricula = $conn->prepare($sql_matricula);
        $stmt_matricula->bind_param("ii", $id_alumno, $id_curso);
        $stmt_matricula->execute();
    }
    echo "<p>Matrícula completada.</p>";
}

// Obtener bloques disponibles
$sql = "SELECT * FROM Bloques";
$result = $conn->query($sql);
?>

<h2>Matricularse</h2>
<form method="POST">
    <select name="id_bloque" required>
        <?php while ($row = $result->fetch_assoc()): ?>
            <option value="<?php echo $row['id_bloque']; ?>"><?php echo $row['nombre']; ?></option>
        <?php endwhile; ?>
    </select>
    <input type="submit" value="Matricularse">
</form>

<?php include('footer.php'); ?>
