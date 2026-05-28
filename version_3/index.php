<?php
session_start();
include("conexion.php");


// Si el usuario presiona el botón de editar
if (isset($_GET['editar_diario'])) {
    $id_editar = $_GET['editar_diario'];
    $sql_busca = "SELECT * FROM v3_diario WHERE id_diario = '$id_editar'";
    $res_busca = mysqli_query($conexion, $sql_busca);
    $fila_editar = mysqli_fetch_assoc($res_busca);
    
    $_SESSION['id_editando'] = $id_editar;
    $_SESSION['texto_editando'] = $fila_editar['entrada'];
    
    header("Location: index.php");
    exit();
}

// 1. Inicio variables por defecto
$login_exitoso = false;
$nombre_login = "";

// 2. Si ya hay una sesión activa, recuperamos los datos
if (isset($_SESSION['id_usuario'])) {
    $login_exitoso = true;
    $nombre_login = $_SESSION['nombre_usuario'];
}

// 3. Lógica de Login
if (isset($_POST['btn_ingresar'])) {
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM v3_usuarios WHERE correo = '$correo' AND password = '$password'";
    $resultado = mysqli_query($conexion, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario_data = mysqli_fetch_assoc($resultado);
        $_SESSION['id_usuario'] = $usuario_data['id_usuario'];
        $_SESSION['nombre_usuario'] = $usuario_data['nombre_completo'];
        $_SESSION['apodo_usuario'] = $usuario_data['apodo'];
        $_SESSION['correo_usuario'] = $usuario_data['correo'];
        $_SESSION['foto_actual'] = $usuario_data['foto'];
        $nombre_login = $_SESSION['nombre_usuario'];
        $login_exitoso = true;
    } else {
        echo "<script>alert('Datos incorrectos. Inténtalo de nuevo.');</script>";
    }
}

// 4. Lógica del Diario (Guardar)
if (isset($_POST['btn_guardar_diario'])) {
    $entrada = mysqli_real_escape_string($conexion, $_POST['entrada_texto']);
    $user_id = $_SESSION['id_usuario'];
    $sql_diario = "INSERT INTO v3_diario (id_usuario, entrada) VALUES ('$user_id', '$entrada')";
    
    if (mysqli_query($conexion, $sql_diario)) {
        echo "<script>alert('¡Pensamiento guardado, $nombre_login! ✨');</script>";
    }
    $login_exitoso = true;
}

//  Lógica del Diario (ELIMINAR / DELETE) 
if (isset($_GET['eliminar_diario'])) {
    $id_a_borrar = $_GET['eliminar_diario'];
    $sql_delete = "DELETE FROM v3_diario WHERE id_diario = '$id_a_borrar'";
    
    if (mysqli_query($conexion, $sql_delete)) {
        echo "<script>alert('Pensamiento eliminado correctamente. ✨'); window.location='index.php';</script>";
        exit();
    }
}

// LÓGICA PARA ACTUALIZAR (UPDATE)
if (isset($_POST['btn_actualizar_diario'])) {
    $id_diario = $_POST['id_diario'];
    $nuevo_texto = mysqli_real_escape_string($conexion, $_POST['entrada_texto']);
    
    $sql_update = "UPDATE v3_diario SET entrada = '$nuevo_texto' WHERE id_diario = '$id_diario'";
    
    if (mysqli_query($conexion, $sql_update)) {
        // LIMPIAMOS LAS VARIABLES PARA QUE VUELVA A MODO "NUEVO"
        unset($_SESSION['id_editando']);
        unset($_SESSION['texto_editando']);
        echo "<script>alert('Pensamiento actualizado'); window.location='index.php';</script>";
    }
}



// LÓGICA DE FOTO
if (isset($_POST['btn_subir_foto']) && isset($_FILES['foto_usuario'])) {
    $nombre_foto = $_FILES['foto_usuario']['name'];
    $ruta_temporal = $_FILES['foto_usuario']['tmp_name'];
    
    if (!empty($nombre_foto)) {
        // Creamos un nombre único con la hora actual
        $nombre_final = time() . "_" . $nombre_foto;
        $carpeta_destino = "uploads/" . $nombre_final;

        // Intentamos mover el archivo de la memoria a la carpeta uploads
        if (move_uploaded_file($ruta_temporal, $carpeta_destino)) {
            $user_id = $_SESSION['id_usuario']; 
            
            // Actualizamos la base de datos
            $sql_foto = "UPDATE v3_usuarios SET foto = '$carpeta_destino' WHERE id_usuario = '$user_id'";
            
            if (mysqli_query($conexion, $sql_foto)) {
                $_SESSION['foto_actual'] = $carpeta_destino;
                echo "<script>alert('¡Foto guardada en tu Refugio! ✨'); window.location='index.php?seccion=perfil';</script>";
            }
        } else {
            echo "<script>alert('Error: La Mac no permitió guardar el archivo. Revisa los permisos de la carpeta uploads.');</script>";
        }
    }
}

// LÓGICA PARA ACTUALIZAR NOMBRE Y CORREO
if (isset($_POST['btn_actualizar_datos'])) {
    $nuevo_nombre = mysqli_real_escape_string($conexion, $_POST['nuevo_nombre']);
    $nuevo_correo = mysqli_real_escape_string($conexion, $_POST['nuevo_correo']);
    $user_id = $_SESSION['id_usuario'];

    $sql_update = "UPDATE v3_usuarios SET nombre_completo = '$nuevo_nombre', correo = '$nuevo_correo' WHERE id_usuario = '$user_id'";

    if (mysqli_query($conexion, $sql_update)) {
        // Actualizamos las variables de sesión para que el cambio sea instantáneo en la pantalla
        $_SESSION['nombre_usuario'] = $nuevo_nombre;
        $_SESSION['correo_usuario'] = $nuevo_correo;
        
        echo "<script>alert('¡Datos actualizados correctamente! ✨'); window.location='index.php?seccion=perfil';</script>";
    } else {
        echo "<script>alert('Error al actualizar los datos.');</script>";
    }
}

// LÓGICA PARA GUARDAR SOLO EL APODO PERSONALIZADO
if (isset($_POST['btn_actualizar_apodo'])) {
    $nuevo_apodo = mysqli_real_escape_string($conexion, $_POST['nuevo_apodo']);
    $user_id = $_SESSION['id_usuario'];

    $sql_update_apodo = "UPDATE v3_usuarios SET apodo = '$nuevo_apodo' WHERE id_usuario = '$user_id'";

    if (mysqli_query($conexion, $sql_update_apodo)) {
        $_SESSION['apodo_usuario'] = $nuevo_apodo;
        echo "<script>alert('¡Apodo guardado con éxito! ✨'); window.location='index.php?seccion=perfil';</script>";
    } else {
        echo "<script>alert('Hubo un error al guardar tu apodo.');</script>";
    }
}

// LÓGICA PARA GUARDAR EL AVATAR ELEGIDO
if (isset($_GET['nuevo_avatar'])) {
    $avatar_url = $_GET['nuevo_avatar'];
    $user_id = $_SESSION['id_usuario'];
    
    // Actualizamos la base de datos con la URL del avatar
    $sql_avatar = "UPDATE v3_usuarios SET foto = '$avatar_url' WHERE id_usuario = '$user_id'";
    
    if (mysqli_query($conexion, $sql_avatar)) {
        $_SESSION['foto_actual'] = $avatar_url; // Actualizamos la sesión
        echo "<script>window.location='index.php?seccion=perfil';</script>";
        exit();
    }
}

// 5. Cerrar Sesión
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental Refuge | Tu Espacio Seguro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<div id="login-container" <?php if($login_exitoso) echo 'style="display:none;"'; ?>>
    <div class="login-card shadow-lg mx-auto">
        <div class="mb-4 text-center">
            <h1 class="fw-bold" style="color: var(--main-color); letter-spacing: -1px;">🧠 Mental Refuge</h1>
            <p class="text-muted small">Acompañamiento seguro e integral</p>
        </div>
        
        <form method="POST">
            <div class="text-start mb-3">
                <label class="small fw-bold mb-1 ms-2">Usuario o Correo</label>
                <input type="email" name="correo" class="form-control btn-round bg-light border-0 p-3" placeholder="tu@correo.com" required>
            </div>
            <div class="text-start mb-3">
                <label class="small fw-bold mb-1 ms-2">Contraseña</label>
                <input type="password" name="password" class="form-control btn-round bg-light border-0 p-3" placeholder="••••••••" required>
            </div>

            <div class="form-check mb-4 text-start ms-2">
                <input class="form-check-input" type="checkbox" id="datosCheckbox" required>
                <label class="form-check-label small text-muted" for="datosCheckbox">Acepto el <span class="text-primary">Tratamiento de Datos Personales</span>.</label>
            </div>
            
            <button type="submit" name="btn_ingresar" class="btn btn-main w-100 btn-round shadow-lg py-3 mb-3">Ingresar al Refugio</button>
        </form>

        <div class="d-flex justify-content-between px-2 mb-4">
            <a href="#" class="small text-decoration-none text-primary">Olvidé mi clave</a>
            <a href="registro.php" class="small text-decoration-none fw-bold text-primary">Crear Cuenta</a>
        </div>

        <div class="mt-2 text-center border-top pt-4">
            <p class="small text-muted mb-3">O inicia sesión con:</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#" class="social-btn"><i class="fab fa-google"></i></a>
                <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-btn"><i class="fab fa-apple"></i></a>
            </div>
        </div>
    </div>
</div>

<div id="main-app" class="container-fluid p-0" <?php if(!$login_exitoso) echo 'style="display:none;"'; ?>>
    <div class="row g-0">
        <nav class="col-md-3 col-lg-2 sidebar p-4 shadow-sm min-vh-100">
            <div class="text-center mb-5">
               <img src="<?php echo isset($_SESSION['foto_actual']) ? $_SESSION['foto_actual'] : 'https://cdn-icons-png.flaticon.com/512/6997/6997662.png'; ?>" 
     id="nav-avatar" 
     class="avatar-img-nav mb-2 shadow" 
     style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                <h6 id="nav-name" class="fw-bold mt-2 text-primary"><?php echo $nombre_login; ?></h6>
            </div>
            <div class="nav flex-column gap-2">
                <a class="nav-link nav-link-custom active" href="index.php?seccion=refugio"><i class="fas fa-home me-2"></i> Mi Refugio</a>
                <a class="nav-link nav-link-custom" href="index.php?seccion=citas"><i class="fas fa-calendar-alt me-2"></i> Mis Citas</a>
                <a class="nav-link nav-link-custom" href="index.php?seccion=test"><i class="fas fa-heartbeat me-2"></i> Autoevaluación</a>
                <a class="nav-link nav-link-custom" href="index.php?seccion=comunidad"><i class="fas fa-users me-2"></i> Comunidad</a>
                <a class="nav-link nav-link-custom" href="index.php?seccion=perfil"><i class="fas fa-user-circle me-2"></i> Mi Perfil</a>
                <hr>
                <a class="nav-link nav-link-custom text-danger fw-bold" href="index.php?logout=true"><i class="fas fa-power-off me-2"></i> Salir</a>

        </nav>

       <main class="col-md-9 col-lg-10 ms-sm-auto content-area p-4">
            <?php
            $seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'refugio';

            // SECCIÓN: DASHBOARD
            if ($seccion == 'refugio') { ?>
                <div class="p-4">
                    <div class="header-bg text-center mb-4 px-4 py-5 rounded-4 shadow-sm text-white" style="background: var(--main-color);">
                        <h2 class="fw-bold" id="welcome-text">Hola, <?php echo explode(' ', $nombre_login)[0]; ?>! ✨</h2>
                        <p>¿Qué tal tu energía hoy? Recuerda que este es tu lugar seguro.</p>
                        <button class="btn emergency-btn shadow">🚨 EMERGENCIA</button>
                    </div>

                    <div class="card card-custom p-4 text-center mb-4 shadow-sm border-0">
                        <h5 class="fw-bold mb-4">¿Cómo te sientes hoy?</h5>
                        <div class="d-flex justify-content-around flex-wrap gap-2">
                            <span class="emoji-btn fs-1" style="cursor:pointer;" onclick="setMood('Radiante', this)">😊</span>
                            <span class="emoji-btn fs-1" style="cursor:pointer;" onclick="setMood('En Calma', this)">🧘‍♂️</span>
                            <span class="emoji-btn fs-1" style="cursor:pointer;" onclick="setMood('Agotada', this)">😓</span>
                            <span class="emoji-btn fs-1" style="cursor:pointer;" onclick="setMood('Triste', this)">😢</span>
                            <span class="emoji-btn fs-1" style="cursor:pointer;" onclick="setMood('Molesta', this)">😠</span>
                        </div>
                        <p id="mood-label" class="mt-3 small fw-bold text-primary text-center"></p>
                    </div>

                    <div class="row">
                        <div class="col-lg-8 mb-4">
                            <div class="card card-custom p-4 shadow-sm border-0">
                                <h5 class="fw-bold mb-3"><i class="fas fa-pen-fancy text-primary me-2"></i> Mi Diario Emocional</h5>
<form method="POST">
    <?php if(isset($_SESSION['id_editando'])) { ?>
        <input type="hidden" name="id_diario" value="<?php echo $_SESSION['id_editando']; ?>">
    <?php } ?>

    <textarea name="entrada_texto" id="diary-entry" class="form-control mb-3 border-0 bg-light p-3 rounded-4" rows="6" required><?php echo $_SESSION['texto_editando'] ?? ''; ?></textarea>

    <?php if(isset($_SESSION['id_editando'])) { ?>
        <button type="submit" name="btn_actualizar_diario" class="btn btn-primary w-100 btn-round">Guardar Cambios</button>
        <a href="index.php" class="btn btn-outline-secondary btn-sm w-100 mt-2 btn-round">Cancelar</a>
    <?php } else { ?>
        <button type="submit" name="btn_guardar_diario" class="btn btn-main w-100 btn-round">Guardar Pensamiento</button>
    <?php } ?>
</form>
<div class="mt-4">
<h6 class="fw-bold text-muted small">Pensamientos Recientes:</h6>
<div id="diary-history" class="mt-2">
<?php
$user_id = $_SESSION['id_usuario'];
$consulta_diario = "SELECT * FROM v3_diario WHERE id_usuario = '$user_id' ORDER BY id_diario DESC";
$res_diario = mysqli_query($conexion, $consulta_diario);
while ($fila = mysqli_fetch_assoc($res_diario)) { ?>
<div class="card p-3 mb-2 shadow-sm border-0 bg-light" style="border-radius: 15px;">
<div class="d-flex justify-content-between align-items-center">
<div>
<p class="m-0 small text-dark"><?php echo $fila['entrada']; ?></p>
<small class="text-muted" style="font-size: 0.7rem;">Publicado el: <?php echo $fila['fecha_registro'] ?? 'Reciente'; ?></small>
</div>
<a href="index.php?editar_diario=<?php echo $fila['id_diario']; ?>" class="btn btn-sm text-primary border-0">
    <i class="fas fa-edit"></i>
</a>

    <a href="index.php?eliminar_diario=<?php echo $fila['id_diario']; ?>" 
       class="btn btn-sm text-danger border-0" 
       onclick="return confirm('¿Seguro que quieres borrar este pensamiento?')">
        <i class="fas fa-trash"></i>
    </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card card-custom p-4 text-white shadow mb-3 border-0" style="background: var(--main-color); border-radius: 20px;">
                                <h6 class="fw-bold m-0"><i class="fas fa-robot me-2"></i> Kathe - Chatbot</h6>
                                <p class="small mt-2 mb-3">Orientación emocional básica 24/7.</p>
                                <button class="btn btn-light btn-round btn-sm w-100 fw-bold" onclick="alert('Kathe se está preparando...')">Hablar ahora</button>
                            </div>
                        </div>
                    </div>
                </div>

            <?php 
            } elseif ($seccion == 'citas') { ?>
                <div class="p-4 text-center">
                    <div class="card card-custom p-5 shadow-sm border-0 rounded-4">
                        <i class="fas fa-calendar-check fa-4x text-primary mb-4"></i>
                        <h3 class="fw-bold">Mis Citas</h3>
                        <p class="text-muted">Gestiona tus consultas médicas aquí.</p>
                        <button class="btn btn-main px-5 btn-round py-2 shadow">Agendar Nueva Cita</button>
                    </div>
                </div>

            <?php 
            } elseif ($seccion == 'test') { ?>
                <div class="p-4">
                    <h3 class="fw-bold text-primary mb-4">Autoevaluación</h3>
                    <div class="alert alert-info border-0 shadow-sm">Realiza un test para medir tu bienestar emocional.</div>
                </div>

            <?php 
            } elseif ($seccion == 'comunidad') { ?>
                <div class="p-4">
                    <h3 class="fw-bold text-primary mb-4">Comunidad</h3>
                    <div class="alert alert-success border-0 shadow-sm">Conéctate con otros usuarios.</div>
                </div>

           <?php 
            } elseif ($seccion == 'perfil') { ?>
                <div class="p-4">
                    <h3 class="fw-bold mb-4">Personalización del Refugio</h3>
                    <div class="row">
                        <div class="col-md-12 text-center">
<img src="<?php echo isset($_SESSION['foto_actual']) ? $_SESSION['foto_actual'] : 'https://cdn-icons-png.flaticon.com/512/6997/6997662.png'; ?>" 
     id="big-avatar" 
     class="avatar-img-big mb-3 shadow" 
     style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">                            
                           <form action="index.php?seccion=perfil" method="POST" enctype="multipart/form-data">
                                <label for="subir-foto" class="btn btn-outline-primary btn-sm btn-round w-100 mb-2" style="cursor:pointer;">
                                    <i class="fas fa-camera me-2"></i> Elegir mi foto
                                </label>
                                <input type="file" id="subir-foto" name="foto_usuario" style="display: none;" onchange="previewImage(event)">
                                <button type="submit" name="btn_subir_foto" class="btn btn-main btn-sm w-100 btn-round">Guardar mi foto</button>
                            </form>

                            <p class="small text-muted">O elige un avatar inclusivo:</p>
<div class="d-flex justify-content-center flex-wrap gap-3">
    <?php
    $lista_avatares = [
        "https://cdn-icons-png.flaticon.com/512/6997/6997662.png",
        "https://cdn-icons-png.flaticon.com/512/4140/4140037.png",
        "https://cdn-icons-png.flaticon.com/512/4140/4140048.png",
        "https://cdn-icons-png.flaticon.com/512/4139/4139948.png",
        "https://cdn-icons-png.flaticon.com/512/4139/4139951.png"
    ];

    foreach ($lista_avatares as $url) {
        echo "<a href='index.php?seccion=perfil&nuevo_avatar=$url'>
                <img src='$url' class='avatar-img-nav shadow-sm' style='cursor:pointer; width: 45px; border-radius: 50%;'>
              </a>";
    }
    ?>
</div>

 <div class="card card-custom p-4 shadow-sm border-0 mb-4">
    <h6 class="fw-bold mb-3">Identidad y Personalización</h6>
    <form method="POST">
        <div class="mb-3">
            <label class="small text-primary fw-bold">¿Cómo quieres que te llamemos? (Apodo)</label>
            <input type="text" name="nuevo_apodo" class="form-control border-primary" 
                   value="<?php echo $_SESSION['apodo_usuario'] ?? ''; ?>" placeholder="Ej: Kathe">
            <small class="text-muted italic">Este es tu nombre público en el refugio.</small>
        </div>

        <hr>

        <div class="mb-2">
            <label class="small text-muted fw-bold">Nombre Completo (Verificado)</label>
            <input type="text" class="form-control bg-light border-0 text-muted" 
                   value="<?php echo $_SESSION['nombre_usuario']; ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="small text-muted fw-bold">Correo Electrónico</label>
            <input type="email" class="form-control bg-light border-0 text-muted" 
                   value="<?php echo $_SESSION['correo_usuario']; ?>" readonly>
        </div>
        
        <button type="submit" name="btn_actualizar_apodo" class="btn btn-main btn-sm w-100 btn-round">Guardar mi Apodo</button>
    </form>
</div>

                            <div class="card card-custom p-4 shadow-sm border-0">
                                <h6 class="fw-bold mb-3">Colores del Refugio</h6>
                                <div class="d-flex gap-3">
                                    <div class="theme-circle" style="background: #5dade2; width: 30px; height: 30px; border-radius: 50%; cursor:pointer;" onclick="setTheme('#5dade2', '#f8fbff')"></div>
                                    <div class="theme-circle" style="background: #48c9b0; width: 30px; height: 30px; border-radius: 50%; cursor:pointer;" onclick="setTheme('#48c9b0', '#f0fff4')"></div>
                                    <div class="theme-circle" style="background: #af7ac5; width: 30px; height: 30px; border-radius: 50%; cursor:pointer;" onclick="setTheme('#af7ac5', '#f5eef8')"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </main>
    </div>
</div>

<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('big-avatar');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<script src="scripts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
