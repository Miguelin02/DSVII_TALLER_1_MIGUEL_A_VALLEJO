<?php
$texto = "dos por dos es cuatro";

function contar_palabras_repetidas($texto) {
    $palabras = explode(" ", $texto);
    $contador = array();
    foreach ($palabras as $palabra) {
        if (isset($contador[$palabra])) {
            $contador[$palabra]++;
        } else {
            $contador[$palabra] = 1;
        }
    }
    return $contador;
}
print_r(contar_palabras_repetidas($texto)); 

echo "\n";
function capitalizar_palabras($texto) {
    $palabras = explode(" ", $texto);
    $palabras_capitalizadas = array_map(function($palabra) {
        return ucfirst(strtolower($palabra));
    }, $palabras);
    return implode(" ", $palabras_capitalizadas);
};
echo capitalizar_palabras($texto);