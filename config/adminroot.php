<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Caminho base para o painel Root
    |--------------------------------------------------------------------------
    |
    | Define o prefixo das rotas utilizadas para o painel do usuário Root.
    | Por padrão, utilizamos "adminroot", mas o valor pode ser modificado
    | via variável de ambiente ADMIN_ROOT_PATH.
    |
    */
    'path' => env('ADMIN_ROOT_PATH', 'adminroot'),
];
