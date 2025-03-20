<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo'] != 'Gerente') {
    header("Location: login.php");
    exit();
}


include('config.php');
include('header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['eliminar'])) {
        $id_profesor = $_POST['id_profesor'];
        $sql = "DELETE FROM Usuarios WHERE id_usuario = ? AND tipo = 'Profesor'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_profesor);
        $stmt->execute();
        echo "<p>Profesor eliminado.</p>";
    } elseif (isset($_POST['agregar'])) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO Usuarios (nombre, apellido, correo, contraseña, tipo) VALUES (?, ?, ?, ?, 'Profesor')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $apellido, $correo, $contraseña);
        $stmt->execute();
        echo "<p>Profesor agregado.</p>";
    }
}

// Obtener lista de profesores
$sql = "SELECT * FROM Usuarios WHERE tipo = 'Profesor'";
$result = $conn->query($sql);
?>
<h2 class="text-center mb-4">Gestionar Profesores</h2>
<div class="row">
    <div class="col-md-6">
        <h4>Agregar Profesor</h4>
        <form method="POST" class="card p-3 shadow-sm">
            <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre" required>
            <input type="text" name="apellido" class="form-control mb-2" placeholder="Apellido" required>
            <input type="email" name="correo" class="form-control mb-2" placeholder="Correo" required>
            <input type="password" name="contraseña" class="form-control mb-2" placeholder="Contraseña" required>
            <button type="submit" name="agregar" class="btn btn-success w-100">Agregar</button>
        </form>
    </div>
    <div class="col-md-6">
        <h4>Eliminar Profesor</h4>
        <form method="POST" class="card p-3 shadow-sm">
            <select name="id_profesor" class="form-control mb-2" required>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['nombre'] . " " . $row['apellido']; ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" name="eliminar" class="btn btn-danger w-100">Eliminar</button>
        </form>
    </div>
</div>
<h2 class="text-center mb-4">Gestionar Profesores</h2>
<div class="row">
    <div class="col-md-6">
        <h4>Agregar Profesor</h4>
        <form method="POST" class="card p-3 shadow-sm">
            <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre" required>
            <input type="text" name="apellido" class="form-control mb-2" placeholder="Apellido" required>
            <input type="email" name="correo" class="form-control mb-2" placeholder="Correo" required>
            <input type="password" name="contraseña" class="form-control mb-2" placeholder="Contraseña" required>
            <button type="submit" name="agregar" class="btn btn-success w-100">Agregar</button>
        </form>
    </div>
    <div class="col-md-6">
        <h4>Eliminar Profesor</h4>
        <form method="POST" class="card p-3 shadow-sm">
            <select name="id_profesor" class="form-control mb-2" required>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_usuario']; ?>"><?php echo $row['nombre'] . " " . $row['apellido']; ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" name="eliminar" class="btn btn-danger w-100">Eliminar</button>
        </form>
    </div>
</div>

<?php include('footer.php'); ?>


