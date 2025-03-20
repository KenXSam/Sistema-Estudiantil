<?php include('header.php'); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h2 class="text-center mb-4">Iniciar Sesión</h2>
            <form action="login_process.php" method="POST" class="card p-4 shadow-sm">
                <label>Correo:</label>
                <input type="email" name="email" class="form-control mb-3" required>
                
                <label>Contraseña:</label>
                <input type="password" name="password" class="form-control mb-3" required>
                
                <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
            </form>
        </div>
    </div>
</div>
<?php include('footer.php'); ?>
