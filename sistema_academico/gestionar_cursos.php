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

// **Procesar Creación de Curso**
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear'])) {
    $nombre_curso = $_POST['nombre_curso'];
    $descripcion = $_POST['descripcion'];
    $id_profesor = $es_gerente ? $_POST['id_profesor'] : $id_usuario; // Gerente elige, Profesor asignado a sí mismo

    $sql = "INSERT INTO Cursos (nombre_curso, descripcion, id_profesor) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nombre_curso, $descripcion, $id_profesor);
    $stmt->execute();
    echo "<p class='alert alert-success'>Curso creado correctamente.</p>";
}

// **Procesar Eliminación de Curso**
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar'])) {
    $id_curso = $_POST['id_curso'];
    $sql = "DELETE FROM Cursos WHERE id_curso = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_curso);
    $stmt->execute();
    echo "<p class='alert alert-danger'>Curso eliminado.</p>";
}

// **Obtener Cursos Disponibles**
$sql = $es_gerente ? "SELECT * FROM Cursos" : "SELECT * FROM Cursos WHERE id_profesor = $id_usuario";
$result = $conn->query($sql);
$profesores = $conn->query("SELECT * FROM Usuarios WHERE tipo='Profesor'");
?>

<h2 class="text-center mb-4">Gestionar Cursos</h2>

<!-- Formulario para Crear Curso -->
<div class="card p-4 shadow-sm mb-4">
    <h4>Crear Nuevo Curso</h4>
    <form method="POST">
        <input type="text" name="nombre_curso" class="form-control mb-3" placeholder="Nombre del Curso" required>
        <textarea name="descripcion" class="form-control mb-3" placeholder="Descripción"></textarea>
        
        <?php if ($es_gerente): ?>
        <label>Asignar Profesor:</label>
        <select name="id_profesor" class="form-control mb-3">
            <?php while ($profesor = $profesores->fetch_assoc()): ?>
                <option value="<?php echo $profesor['id_usuario']; ?>"><?php echo $profesor['nombre']; ?></option>
            <?php endwhile; ?>
        </select>
        <?php endif; ?>

        <button type="submit" name="crear" class="btn btn-success w-100">Crear Curso</button>
    </form>
</div>

<!-- Lista de Cursos -->
<h4>Lista de Cursos</h4>
<table class="table table-striped">
    <tr>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Profesor</th>
        <th>Acciones</th>
    </tr>
    <?php while ($curso = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $curso['nombre_curso']; ?></td>
        <td><?php echo $curso['descripcion']; ?></td>
        <td>
            <?php
            $id_prof = $curso['id_profesor'];
            $prof_query = $conn->query("SELECT nombre FROM Usuarios WHERE id_usuario = $id_prof");
            echo $prof_query->fetch_assoc()['nombre'];
            ?>
        </td>
        <td>
            <form method="POST" class="d-inline">
                <input type="hidden" name="id_curso" value="<?php echo $curso['id_curso']; ?>">
                <button type="submit" name="eliminar" class="btn btn-danger btn-sm">Eliminar</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include('footer.php'); ?>
