<?php

return [
    // Mensajes globales
    'required' => ':attribute es obligatorio.',
    'max' => ':attribute no puede tener más de :max caracteres.',
    'digits' => ':attribute debe tener exactamente :digits dígitos.',
    'email' => ':attribute debe ser un correo electrónico válido.',
    'unique' => ':attribute ya está registrado.',
    'date' => ':attribute debe ser una fecha válida.',
    'before_or_equal' => ':attribute no es válido.',
    'in' => 'El valor seleccionado para :attribute no es válido.',
    'exists' => 'El valor seleccionado para :attribute es inválido.',
    'numeric' => ':attribute debe ser un número.',
    'between' => ':attribute debe ser un número válido con hasta 2 decimales.',

    // Mensajes personalizados
    'custom' => [
        'nombre' => [
        'required' => 'Los Nombres son obligatorios.',
        'regex' => 'Los Nombres solo deben contener letras y espacios.',
    ],
        'apellidos' => [
        'required' => 'Los Apellidos son obligatorios.',
        'regex' => 'Los Apellidos solo deben contener letras y espacios.',
    ],
        'identidad' => [
            'required' => 'La identidad es obligatoria.',
            'digits' => 'La identidad debe contener exactamente 13 dígitos.',
        ],
        'telefono' => [
            'required' => 'El Teléfono es obligatorio.',
            'digits' => 'El teléfono debe contener exactamente 8 dígitos numéricos.',
            'regex' => 'El teléfono debe comenzar con 2, 3, 8 o 9 y contener exactamente 8 dígitos numéricos.',
        ],
        'correo' => [
            'required' => 'El correo es obligatorio.',
            'max' => 'El correo no debe tener más de 30 caracteres.',
            'email' => 'El correo debe ser un correo válido.',
            'unique' => 'El correo ya está en uso.',
        ],
        'fecha_nacimiento' => [
        'required' => 'La Fecha de nacimiento es obligatoria.',
        'date' => 'La Fecha de nacimiento debe ser una fecha válida.',
        'before_or_equal' => 'La Fecha de nacimiento debe ser antes o igual a hoy menos 18 años.',
    ],
        'fecha_ingreso' => [
        'required' => 'La Fecha de ingreso es obligatoria.',
        'date' => 'La Fecha de ingreso debe ser una fecha válida.',
        'after_or_equal' => 'La Fecha de ingreso no puede ser anterior al mes pasado.',
        'before_or_equal' => 'La Fecha de ingreso no puede ser posterior al próximo mes.',
    ],
        'direccion' => [
            'required' => 'La Dirección es obligatoria.',
            'max' => 'La Dirección no puede tener más de 50 caracteres.',
        ],
        'observaciones' => [
            'max' => 'Las Observaciones no pueden tener más de 50 caracteres.',
        ],
        'turno_asignado' => [
            'required' => 'El Turno asignado es obligatorio.',
            'in' => 'El Turno asignado no es válido.',
        ],
        'estado' => [
            'required' => 'El Estado es obligatorio.',
            'in' => 'El Estado no es válido.',
        ],
    ],

    // Nombres legibles para los atributos
    'attributes' => [
        'nombre' => 'Nombres',
        'apellidos' => 'Apellidos',
        'identidad' => 'Identidad',
        'telefono' => 'Teléfono',
        'direccion' => 'Dirección',
        'correo' => 'Correo electrónico',
        'fecha_ingreso' => 'Fecha de ingreso',
        'fecha_nacimiento' => 'Fecha de nacimiento',
        'genero' => 'Género',
        'estado_civil' => 'Estado civil',
        'puesto_id' => 'Puesto',
        'salario' => 'Sueldo',
        'observaciones' => 'Observaciones',
        'turno_asignado' => 'Turno asignado',
        'estado' => 'Estado',
    ],
];
