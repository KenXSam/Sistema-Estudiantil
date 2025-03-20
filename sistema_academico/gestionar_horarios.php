<?php
session_start();
if (!isset($_SESSION['id_usuario']) || ($_SESSION['tipo'] != 'Gerente' && $_SESSION['tipo'] != 'Profesor')) {
    header("Location: login.php");
    exit();
}
include('config.php');
include('header.php');

$id_usuario = $_SESSION['id_usuario'];
$es_gerente = ($_SESSION['tipo'] == 'Gerente');

// Insertar nuevo horario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_curso = $_POST['id_curso'];
    $id_profesor = $es_gerente ? $_POST['id_profesor'] : $id_usuario; // Si es profesor, se asigna a sí mismo
    $dia = $_POST['dia'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $aula = $_POST['aula'];

    $sql = "INSERT INTO Horarios (id_curso, id_profesor, dia, hora_inicio, hora_fin, aula) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissss", $id_curso, $id_profesor, $dia, $hora_inicio, $hora_fin, $aula);
    $stmt->execute();
    echo "<p>Horario agregado exitosamente.</p>";
}

// Obtener cursos y profesores
$cursos = $conn->query("SELECT * FROM Cursos");
if ($es_gerente) {
    $profesores = $conn->query("SELECT * FROM Usuarios WHERE tipo='Profesor'");
}

?>

<h2 class="text-center mb-4">Gestionar Horarios</h2>

<!-- FORMULARIO PARA AGREGAR HORARIO -->
<form method="POST" class="card p-4 shadow-sm">
    <label>Curso:</label>
    <select name="id_curso" class="form-control mb-3">
        <?php while ($curso = $cursos->fetch_assoc()): ?>
            <option value="<?php echo $curso['id_curso']; ?>"><?php echo $curso['nombre_curso']; ?></option>
        <?php endwhile; ?>
    </select>

    <?php if ($es_gerente): ?>
    <label>Profesor:</label>
    <select name="id_profesor" class="form-control mb-3">
        <?php while ($profesor = $profesores->fetch_assoc()): ?>
            <option value="<?php echo $profesor['id_usuario']; ?>"><?php echo $profesor['nombre']; ?></option>
        <?php endwhile; ?>
    </select>
    <?php else: ?>
    <input type="hidden" name="id_profesor" value="<?php echo $id_usuario; ?>">
    <?php endif; ?>

    <label>Día:</label>
    <select name="dia" class="form-control mb-3">
        <option value="Lunes">Lunes</option>
        <option value="Martes">Martes</option>
        <option value="Miércoles">Miércoles</option>
        <option value="Jueves">Jueves</option>
        <option value="Viernes">Viernes</option>
        <option value="Sábado">Sábado</option>
    </select>

    <label>Hora de inicio:</label>
    <input type="time" name="hora_inicio" class="form-control mb-3" required>

    <label>Hora de fin:</label>
    <input type="time" name="hora_fin" class="form-control mb-3" required>

    <label>Aula:</label>
    <input type="text" name="aula" class="form-control mb-3" required>

    <button type="submit" class="btn btn-success w-100">Guardar Horario</button>
</form>

<?php include('footer.php'); ?>
