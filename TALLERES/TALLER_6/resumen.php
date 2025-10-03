<?php
$archivo = 'data.json';

if (!file_exists($archivo)) {
    echo "<h2>No hay registros aún</h2>";
    exit;
}

$contenido = file_get_contents($archivo);
$registros = json_decode($contenido, true);

echo "<h2>Resumen de registros</h2>";
echo "<table border='1'>";
echo "<tr><th>Nombre</th><th>Email</th><th>Edad</th><th>Fecha Nacimiento</th><th>Género</th><th>Intereses</th><th>Comentarios</th><th>Foto</th></tr>";

foreach ($registros as $registro) {
    echo "<tr>";
    echo "<td>{$registro['nombre']}</td>";
    echo "<td>{$registro['email']}</td>";
    echo "<td>{$registro['edad']}</td>";
    echo "<td>{$registro['fecha_nacimiento']}</td>";
    echo "<td>{$registro['genero']}</td>";
    echo "<td>" . implode(", ", $registro['intereses']) . "</td>";
    echo "<td>{$registro['comentarios']}</td>";
    echo "<td><img src='{$registro['foto_perfil']}' width='80'></td>";
    echo "</tr>";
}
echo "</table>";
echo "<br><a href='formulario.php'>Volver al formulario</a>";
?>
