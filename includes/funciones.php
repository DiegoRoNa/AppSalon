<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);//sanitiza el HTML
    return $s;
}

//FUNCION PARA MOSTRAR EL TOTAL A PAGAR
function esUltimo(string $actual, string $proximo): bool{
    
    if ($actual !== $proximo) {
        return true;
    }

    return false;
}

//AUTENCIACION DEL USUARIO
function isAuth() : void {
    if (!isset($_SESSION['login'])) {
        header('Location: /');
    }
}

//AUTENCIACION DEL ADMIN
function isAdmin() : void {
    if (!isset($_SESSION['admin'])) {
        header('Location: /');
    }
}