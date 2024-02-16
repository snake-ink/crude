<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'O campo :attribute precisa ser aceito.',
    'accepted_if' => 'O campo :attribute precisa ser aceito se :other for :value.',
    'active_url' => 'O campo :attribute não é uma URL válida.',
    'after' => 'O campo :attribute precisa ser uma data posterior a :date.',
    'after_or_equal' => 'O campo :attribute precisa ser uma data posterior ou igual a :date.',
    'alpha' => 'O campo :attribute só pode conter letras.',
    'alpha_dash' => 'O campo :attribute só pode conter letras, números, hífens e sublinhados.',
    'alpha_num' => 'O campo :attribute só pode conter letras e números.',
    'array' => 'O campo :attribute precisa ser um array.',
    'before' => 'O campo :attribute precisa ser uma data anterior a :date.',
    'before_or_equal' => 'O campo :attribute precisa ser uma data anterior ou igual a :date.',
    'between' => [
        'array' => 'O campo :attribute precisa conter entre :min e :max itens.',
        'file' => 'O campo :attribute precisa ser um arquivo entre :min e :max kilobytes.',
        'numeric' => 'O campo :attribute precisa ser entre :min e :max.',
        'string' => 'O campo :attribute precisa ter entre :min e :max caracteres.',
    ],
    'boolean' => 'O campo :attribute precisa ser "true" ou "false".',
    'confirmed' => 'A confirmação do campo :attribute não bate com o original.',
    'current_password' => 'A senha está incorreta.',
    'date' => 'O campo :attribute não é uma data válida.',
    'date_equals' => 'O campo :attribute precisa ser uma data igual a :date.',
    'date_format' => 'O campo :attribute não segue o formato de data :format.',
    'declined' => 'O campo :attribute precisa ser declinado.',
    'declined_if' => 'O campo :attribute precisa ser declinado quando :other for :value.',
    'different' => 'Os campos :attribute e :other precisam ser diferentes.',
    'digits' => 'O campo :attribute precisa ter :digits dígitos.',
    'digits_between' => 'O campo :attribute precisa ter entre :min e :max dígitos.',
    'dimensions' => 'A imagem :attribute tem dimensões inválidas.',
    'distinct' => 'O campo :attribute tem valor duplicado.',
    'doesnt_end_with' => 'O campo :attribute não pode terminar com um dos seguintes valores: :values.',
    'doesnt_start_with' => 'O campo :attribute não pode começar com um dos seguintes valores: :values.',
    'email' => 'O campo :attribute precisa ser um endereço de email válido.',
    'ends_with' => 'O campo :attribute precisa terminar com um desses valores: :values.',
    'enum' => 'O campo :attribute contém um valor inválido.',
    'exists' => 'O campo :attribute selecionado precisa existir.',
    'file' => 'O campo :attribute precisa ser um arquivo.',
    'filled' => 'O campo :attribute precisa ter um valor.',
    'gt' => [
        'array' => 'O campo :attribute precisa conter mais do que :value itens.',
        'file' => 'O campo :attribute precisa ser um arquivo maior do que :value kilobytes.',
        'numeric' => 'O campo :attribute precisa ser maior do que :value.',
        'string' => 'O campo :attribute precisa ter mais do que :value caracteres.',
    ],
    'gte' => [
        'array' => 'O campo :attribute precisa conter :value itens ou mais.',
        'file' => 'O campo :attribute precisa ser um arquivo igual a :value kilobytes ou maior.',
        'numeric' => 'O campo :attribute precisa ser maior ou igual a :value.',
        'string' => 'O campo :attribute precisa ter :value caracteres ou mais.',
    ],
    'image' => 'O campo :attribute precisa ser uma imagem.',
    'in' => 'O campo :attribute selecionado é inválido.',
    'in_array' => 'O campo :attribute não existe em :other.',
    'integer' => 'O campo :attribute precisa ser um número inteiro.',
    'ip' => 'O campo :attribute precisa ser um endereço IP válido.',
    'ipv4' => 'O campo :attribute precisa ser um endereço IPv4 válido.',
    'ipv6' => 'O campo :attribute precisa ser um endereço IPv6 válido.',
    'json' => 'O campo :attribute precisa ser uma string JSON válida.',
    'lt' => [
        'array' => 'O campo :attribute precisa conter menos do que :value itens.',
        'file' => 'O campo :attribute precisa ser um arquivo menor do que :value kilobytes.',
        'numeric' => 'O campo :attribute precisa ser menor do que :value.',
        'string' => 'O campo :attribute precisa ter menos do que :value caracteres.',
    ],
    'lte' => [
        'array' => 'O campo :attribute precisa conter :value itens ou menos.',
        'file' => 'O campo :attribute precisa ser um arquivo igual a :value kilobytes ou menor.',
        'numeric' => 'O campo :attribute precisa ser menor ou igual a :value.',
        'string' => 'O campo :attribute precisa ter :value caracteres ou menos.',
    ],
    'mac_address' => 'O campo :attribute precisa ser um endereço MAC válido.',
    'max' => [
        'array' => 'O campo :attribute não pode conter mais do que :max itens.',
        'file' => 'O campo :attribute não pode ser um arquivo maior do que :max kilobytes.',
        'numeric' => 'O campo :attribute não pode ser maior do que :max.',
        'string' => 'O campo :attribute não pode ter mais do que :max caracteres.',
    ],
    'mimes' => 'O arquivo :attribute só pode ser do(s) tipo(s): :values.',
    'mimetypes' => 'O arquivo :attribute só pode ser do(s) tipo(s): :values.',
    'min' => [
        'array' => 'O campo :attribute precisa ter no mínimo :min itens.',
        'file' => 'O campo :attribute precisa ser um arquivo de no mínimo :min kilobytes.',
        'numeric' => 'O campo :attribute precisa ser no mínimo :min.',
        'string' => 'O campo :attribute precisa ter no mínimo :min caracteres.',
    ],
    'multiple_of' => 'O campo :attribute precisa ser um múltiplo de :value.',
    'not_in' => 'O campo :attribute selecionado é inválido.',
    'not_regex' => 'O campo :attribute está no formato incorreto.',
    'numeric' => 'O campo :attribute precisa ser um número.',
    'password' => [
        'letters' => 'O campo :attribute precisa conter no mínimo uma letra.',
        'mixed' => 'O campo :attribute precisa conter no mínimo uma letra maiúscula e uma letra minúscula.',
        'numbers' => 'O campo :attribute precisa conter pelo menos um número.',
        'symbols' => 'O campo :attribute precisa conter pelo menos um caractere especial.',
        'uncompromised' => 'O campo :attribute fornecido já apareceu em um vazamento de dados. Por favor, escolha um(a) :attribute diferente.',
    ],
    'present' => 'O campo :attribute precisa estar presente.',
    'prohibited' => 'O campo :attribute é proibido.',
    'prohibited_if' => 'O campo :attribute é proibido se :other for :value.',
    'prohibited_unless' => 'O campo :attribute é proibido a menos que :other esteja em :values.',
    'prohibits' => 'O campo :attribute proibe o campo :other de estar presente.',
    'regex' => 'O campo :attribute está no formato incorreto.',
    'required' => 'O campo :attribute é obrigatório.',
    'required_array_keys' => 'O campo :attribute precisa conter valores para as seguintes chaves: :values.',
    'required_if' => 'O campo :attribute é obrigatório quando :other é :value.',
    'required_unless' => 'O campo :attribute é obrigatório, exceto quando :other é :values.',
    'required_with' => 'O campo :attribute é obrigatório quando :values está presente.',
    'required_with_all' => 'O campo :attribute é obrigatório quando :values estão presentes.',
    'required_without' => 'O campo :attribute é obrigatório quando :values não está presente.',
    'required_without_all' => 'O campo :attribute é obrigatório quando nenhum desses valores está presente: :values.',
    'same' => 'Os campos :attribute e :other precisam ser idênticos.',
    'size' => [
        'array' => 'O campo :attribute precisa conter :size itens.',
        'file' => 'O campo :attribute precisa ser um arquivo de :size kilobytes.',
        'numeric' => 'O campo :attribute precisa ser :size.',
        'string' => 'O campo :attribute precisa ter :size caracteres.',
    ],
    'starts_with' => 'O campo :attribute precisa começar com um dos seguintes valores: :values.',
    'string' => 'O campo :attribute precisa ser uma string.',
    'timezone' => 'O campo :attribute precisa ser um fuso horário válido.',
    'unique' => 'O campo :attribute já foi registrado com esse valor.',
    'uploaded' => 'O upload do arquivo :attribute falhou.',
    'url' => 'O campo :attribute precisa ser uma URL válida.',
    'uuid' => 'O campo :attribute precisa ser um UUID válido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'example' => 'Exemplo',
    ],
];
