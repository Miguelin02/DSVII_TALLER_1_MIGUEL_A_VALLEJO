<?php
require_once 'validaciones.php';
require_once 'sanitizacion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errores = [];
    $datos = [];

    // Campos a validar (edad se calcula)
    $campos = ['nombre', 'email', 'fecha_nacimiento', 'sitio_web', 'genero', 'intereses', 'comentarios'];
    foreach ($campos as $campo) {
        if (isset($_POST[$campo])) {
            $valor = $_POST[$campo];
            $valorSanitizado = call_user_func("sanitizar" . ucfirst($campo), $valor);
            $datos[$campo] = $valorSanitizado;

            if (!call_user_func("validar" . ucfirst($campo), $valorSanitizado)) {
                $errores[] = "El campo $campo no es válido.";
            }
        }
    }

    // Calcular edad desde la fecha de nacimiento
    if (!isset($errores['fecha_nacimiento'])) {
        $fechaNacimiento = new DateTime($datos['fecha_nacimiento']);
        $hoy = new DateTime();
        $edad = $hoy->diff($fechaNacimiento)->y;
        $datos['edad'] = $edad;
    }

    // Procesar foto de perfil con nombre único
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] !== UPLOAD_ERR_NO_FILE) {
        if (!validarFotoPerfil($_FILES['foto_perfil'])) {
            $errores[] = "La foto de perfil no es válida.";
        } else {
            $extension = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
            $nombreUnico = uniqid('perfil_', true) . "." . $extension;
            $rutaDestino = 'uploads/' . $nombreUnico;

            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $rutaDestino)) {
                $datos['foto_perfil'] = $rutaDestino;
            } else {
                $errores[] = "Hubo un error al subir la foto de perfil.";
            }
        }
    }

    // Si no hay errores, guardar en JSON
    if (empty($errores)) {
        $archivo = 'data.json';
        $registros = [];

        if (file_exists($archivo)) {
            $contenido = file_get_contents($archivo);
            $registros = json_decode($contenido, true) ?? [];
        }

        $registros[] = $datos;
        file_put_contents($archivo, json_encode($registros, JSON_PRETTY_PRINT));

        echo "<h2>Datos Recibidos:</h2>";
        echo "<table border='1'>";
        foreach ($datos as $campo => $valor) {
            echo "<tr><th>" . ucfirst($campo) . "</th>";
            if ($campo === 'intereses') {
                echo "<td>" . implode(", ", $valor) . "</td>";
            } elseif ($campo === 'foto_perfil') {
                echo "<td><img src='$valor' width='100'></td>";
            } else {
                echo "<td>$valor</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "<br><a href='formulario.php'>Volver al formulario</a> | <a href='resumen.php'>Ver registros</a>";
    } else {
        echo "<h2>Errores:</h2><ul>";
        foreach ($errores as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
        include 'formulario.php'; // Recargar con datos
    }
} else {
    echo "Acceso no permitido.";
}
?>
