<?php
return [
    ["label" => "Inicio", "url" => "/", "icon" => "home"],
    ['label' => 'Usuarios', "url" => ["/usuario"], "icon" => "users"],
    ['label' => 'Clientes', 'url' => ['/cliente'], "icon" => "briefcase"],
    [
        "label" => "Uva",
        "icon" => "leaf",
        "url" => "#",
        "items" => [
            ['label' => 'Etiquetas', 'url' => ['/etiqueta']],
            ['label' => 'Cajas', 'url' => ['/caja']],
            ['label' => 'Tipos de Caja', 'url' => ['/tipocaja']],
            ['label' => 'Variedad', 'url' => ['/variedad']],
        ],
    ],
    [
        "label" => "Seguimiento",
        "icon" => "compass",
        "url" => "#",
        "items" => [
            ['label' => 'Órdenes', 'url' => ['/orden']],
            ['label' => 'Pedidos', 'url' => ['/pedido']],
        ],
    ],
    [
        "label" => "Localización",
        "icon" => "map",
        "url" => "#",
        "items" => [
            ['label' => 'Fincas', 'url' => ['/finca']],
            ['label' => 'Parcela', 'url' => ['/parcela']],
            ['label' => 'Sectores', 'url' => ['/sector']],
        ],
    ],
    ['label' => 'Stock de materiales', 'url' => ['/material'], "icon" => "table"],

];
