<?php

return [
    'required' => 'O campo :attribute é obrigatório.',
    'required_unless' => 'O campo :attribute é obrigatório, exceto quando :other estiver marcado.',
    'confirmed' => 'A confirmação de :attribute não confere.',
    'email' => 'Informe um e-mail válido em :attribute.',

    'password' => [
        'min' => 'A :attribute deve ter ao menos :min caracteres.',
        'letters' => 'A :attribute deve conter ao menos uma letra.',
        'mixed' => 'A :attribute deve conter letras maiúsculas e minúsculas.',
        'numbers' => 'A :attribute deve conter ao menos um número.',
        'symbols' => 'A :attribute deve conter ao menos um símbolo.',
        'uncompromised' => 'A :attribute aparece em vazamentos de dados. Escolha outra senha.',
    ],

    'custom' => [
        'store_domain' => [
            'required_unless' => 'Informe um domínio da loja ou selecione a opção de domínio temporário.',
        ],
    ],

    'attributes' => [
        'store_domain' => 'domínio da loja',
        'use_temp_domain' => 'domínio temporário',
        'store_name' => 'nome da loja',
        'store_admin_name' => 'nome do administrador da loja',
        'store_admin_email' => 'e-mail do administrador da loja',
        'store_admin_password' => 'senha do administrador da loja',
    ],
];
