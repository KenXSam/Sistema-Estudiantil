<?php
// register.php
include('header.php');
?>
<h2>Registro de Usuario</h2>
<form action="register_process.php" method="POST">
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" required>
    
    <label for="apellido">Apellido:</label>
    <input type="text" name="apellido" required>
    
    <label for="correo">Correo:</label>
    <input type="email" name="correo" required>
    
    <label for="contraseña">Contraseña:</label>
    <input type="password" name="contraseña" required>
    
    <label for="tipo">Tipo de Usuario:</label>
    <select name="tipo" required>
        <option value="Alumno">Alumno</option>
        <option value="Profesor">Profesor</option>
        
    </select>
    
    <input type="submit" value="Registrarse">
</form>
<?php
include('footer.php');
?>
