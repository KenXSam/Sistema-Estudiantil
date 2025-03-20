<?php
// registrar_asistencia.php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Profesor') {
    header("Location: login.php");
    exit();
}
include('config.php');
include('header.php');

// Procesar el formulario cuando se envía por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_curso = $_POST['id_curso'];
    $fecha = $_POST['fecha'];
    
    // Insertar asistencia para cada alumno
    if (isset($_POST['asistencia']) && is_array($_POST['asistencia'])) {
        foreach ($_POST['asistencia'] as $id_alumno => $estado) {
            $sql = "INSERT INTO Asistencia (id_alumno, id_curso, fecha, estado) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiss", $id_alumno, $id_curso, $fecha, $estado);
            if (!$stmt->execute()) {
                echo "<p>Error al insertar asistencia para el alumno $id_alumno: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
        echo "<p>Asistencia registrada exitosamente.</p>";
    }
}

// Mostrar cursos asignados al profesor
$sql = "SELECT * FROM Cursos WHERE id_profesor = " . $_SESSION['id_usuario'];
$result = $conn->query($sql);
?>

<h2>Registrar Asistencia</h2>
<form method="POST" action="registrar_asistencia.php">
    <label for="id_curso">Selecciona el Curso:</label>
    <!-- Al seleccionar un curso, se recarga la página pasando el id del curso por GET -->
    <select name="id_curso" required onchange="window.location.href='registrar_asistencia.php?curso=' + this.value;">
        <option value="">-- Selecciona un curso --</option>
        <?php while($row = $result->fetch_assoc()): ?>
            <!-- Si ya se seleccionó un curso, lo marcamos como seleccionado -->
            <option value="<?php echo $row['id_curso']; ?>" <?php if(isset($_GET['curso']) && $_GET['curso'] == $row['id_curso']) echo 'selected'; ?>>
                <?php echo $row['nombre_curso']; ?>
            </option>
        <?php endwhile; ?>
    </select>
    
    <label for="fecha">Fecha:</label>
    <input type="date" name="fecha" required>
    
    <?php
    // Si se recibe el parámetro 'curso' en la URL, se listan los alumnos inscritos
    if (isset($_GET['curso'])) {
        $id_curso_selected = intval($_GET['curso']); // Aseguramos que sea un entero
        $sql_students = "SELECT u.id_usuario, u.nombre, u.apellido FROM Usuarios u
                         JOIN Matricula m ON u.id_usuario = m.id_alumno
                         WHERE m.id_curso = $id_curso_selected";
        $students = $conn->query($sql_students);
        if ($students && $students->num_rows > 0) {
            echo "<h3>Lista de Alumnos</h3>";
            while ($student = $students->fetch_assoc()) {
                echo "<p>" . $student['nombre'] . " " . $student['apellido'] . " ";
                echo "<select name='asistencia[" . $student['id_usuario'] . "]'>";
                echo "<option value='Presente'>Presente</option>";
                echo "<option value='Tarde'>Tarde</option>";
                echo "<option value='Falta'>Falta</option>";
                echo "<option value='Justificado'>Justificado</option>";
                echo "</select></p>";
            }
        } else {
            echo "<p>No hay alumnos inscritos en este curso.</p>";
        }
    } else {
        echo "<p>Para registrar asistencia, selecciona un curso.</p>";
    }
    ?>
    
    <input type="submit" value="Registrar Asistencia">
</form>

<?php
include('footer.php');
?>
