<?php
include("conexion.php");

if (isset($_POST['btn_registrar'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    // Insertamos en la nueva tabla usuarios_app
    $sql = "INSERT INTO v3_usuarios (nombre_completo, correo, password, id_rol) 
            VALUES ('$nombre', '$correo', '$password', 1)";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>
                alert('¡Cuenta creada con éxito! Ya puedes iniciar sesión.');
                window.location.href='index.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Cuenta | Mental Refuge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
</head>
<body style="background: linear-gradient(135deg, #5dade2 0%, #ffffff 100%); height: 100vh; display: flex; align-items: center;">

<div class="container">
    <div class="login-card shadow-lg mx-auto" style="max-width: 400px; background: white; padding: 30px; border-radius: 20px;">
        <h3 class="text-center fw-bold" style="color: #5dade2;">Únete al Refugio</h3>
        <p class="text-center text-muted small">Crea tu espacio personal hoy</p>
        
        <form method="POST">
            <div class="mb-3 text-start">
                <label class="small fw-bold">Nombre Completo</label>
                <input type="text" name="nombre" class="form-control btn-round" placeholder="Ej. Dylan Castellanos" required>
            </div>
            <div class="mb-3 text-start">
                <label class="small fw-bold">Correo Electrónico</label>
                <input type="email" name="correo" class="form-control btn-round" placeholder="tu@correo.com" required>
            </div>
            <div class="mb-3 text-start">
                <label class="small fw-bold">Contraseña</label>
                <input type="password" name="password" class="form-control btn-round" placeholder="••••••••" required>
            </div>
            <button type="submit" name="btn_registrar" class="btn btn-main w-100 btn-round shadow-sm">Registrarme</button>
            <div class="text-center mt-3">
                <a href="index.php" class="small text-decoration-none">Ya tengo cuenta, volver al inicio</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>