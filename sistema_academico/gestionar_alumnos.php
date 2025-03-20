<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Gerente') {
    header("Location: login.php");
    exit();
}
include('config.php');
include('header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['agregar'])) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO Usuarios (nombre, apellido, correo, contraseña, tipo) VALUES (?, ?, ?, ?, 'Alumno')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $apellido, $correo, $contraseña);
        $stmt->execute();
        echo "<p class='alert alert-success'>Alumno agregado correctamente.</p>";
    }

    if (isset($_POST['eliminar'])) {
        $id_alumno = $_POST['id_alumno'];
        $sql = "DELETE FROM Usuarios WHERE id_usuario = ? AND tipo = 'Alumno'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_alumno);
        $stmt->execute();
        echo "<p class='alert alert-danger'>Alumno eliminado.</p>";
    }
}

$alumnos = $conn->query("SELECT * FROM Usuarios WHERE tipo='Alumno'");
?>

<h2 class="text-center mb-4">Gestionar Alumnos</h2>

<!-- Formulario para Agregar Alumno -->
<div class="card p-4 shadow-sm mb-4">
    <h4>Agregar Alumno</h4>
    <form method="POST">
        <input type="text" name="nombre" class="form-control mb-3" placeholder="Nombre" required>
        <input type="text" name="apellido" class="form-control mb-3" placeholder="Apellido" required>
        <input type="email" name="correo" class="form-control mb-3" placeholder="Correo" required>
        <input type="password" name="contraseña" class="form-control mb-3" placeholder="Contraseña" required>
        <button type="submit" name="agregar" class="btn btn-success w-100">Agregar Alumno</button>
    </form>
</div>

<!-- Lista de Alumnos -->
<h4>Lista de Alumnos</h4>
<table class="table table-striped">
    <tr>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Acciones</th>
    </tr>
    <?php while ($alumno = $alumnos->fetch_assoc()): ?>
    <tr>
        <td><?php echo $alumno['nombre'] . " " . $alumno['apellido']; ?></td>
        <td><?php echo $alumno['correo']; ?></td>
        <td>
            <form method="POST" class="d-inline">
                <input type="hidden" name="id_alumno" value="<?php echo $alumno['id_usuario']; ?>">
                <button type="submit" name="eliminar" class="btn btn-danger btn-sm">Eliminar</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include('footer.php'); ?>
