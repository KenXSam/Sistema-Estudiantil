<h2>Reporte de Asistencia</h2>
<form method="GET" class="mb-3">
    <label>Curso:</label>
    <select name="id_curso" class="form-control">
        <option value="">Todos</option>
        <?php
        $cursos = $conn->query("SELECT * FROM Cursos");
        while ($curso = $cursos->fetch_assoc()) {
            echo "<option value='" . $curso['id_curso'] . "'>" . $curso['nombre_curso'] . "</option>";
        }
        ?>
    </select>

    <label>Fecha:</label>
    <input type="date" name="fecha" class="form-control">

    <label>Estado:</label>
    <select name="estado" class="form-control">
        <option value="">Todos</option>
        <option value="Presente">Presente</option>
        <option value="Tarde">Tarde</option>
        <option value="Falta">Falta</option>
        <option value="Justificado">Justificado</option>
    </select>

    <button type="submit" class="btn btn-primary mt-3">Filtrar</button>
</form>

<table class="table table-striped">
    <tr>
        <th>Fecha</th>
        <th>Alumno</th>
        <th>Curso</th>
        <th>Estado</th>
    </tr>
    <?php
    $query = "SELECT a.fecha, u.nombre AS alumno, c.nombre_curso, a.estado FROM Asistencia a
              JOIN Usuarios u ON a.id_alumno = u.id_usuario
              JOIN Cursos c ON a.id_curso = c.id_curso WHERE 1=1";

    if (!empty($_GET['id_curso'])) {
        $query .= " AND a.id_curso = " . intval($_GET['id_curso']);
    }
    if (!empty($_GET['fecha'])) {
        $query .= " AND a.fecha = '" . $_GET['fecha'] . "'";
    }
    if (!empty($_GET['estado'])) {
        $query .= " AND a.estado = '" . $_GET['estado'] . "'";
    }

    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['fecha']}</td>
            <td>{$row['alumno']}</td>
            <td>{$row['nombre_curso']}</td>
            <td>{$row['estado']}</td>
        </tr>";
    }
    ?>
</table>
