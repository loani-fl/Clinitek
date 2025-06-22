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
        'nombres' => [
            'required' => 'Los Nombres son obligatorios.',
            'max' => 'Nombres no debe tener más de 50 caracteres.',
            'regex' => 'Nombres solo debe contener letras y espacios.',
        ],
        'apellidos' => [
            'required' => 'Los Apellidos son obligatorios.',
            'max' => 'Apellidos no debe tener más de 50 caracteres.',
            'regex' => 'Apellidos solo debe contener letras y espacios.',
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
            'before_or_equal' => 'El empleado debe tener al menos 18 años.',
        ],
        'direccion' => [
            'required' => 'La Dirección es obligatoria.',
            'max' => 'La Dirección no puede tener más de 350 caracteres.',
        ],
        'observaciones' => [
            'max' => 'Las Observaciones no pueden tener más de 350 caracteres.',
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
        'nombres' => 'Nombres',
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


