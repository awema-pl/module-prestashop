<?php

return [
    'admin' =>[

    ],
    'user' =>[
        'shop' =>[
            'error_request_prestashop_api' => 'Błąd zapytania do API PrestaShop. :error',
            'error_incompatibile_version' => 'Błąd PrestaShop. Niekompatybilna wersja (minimalna wersja: :min, maksymalna wersja: :max).',
            'http_xml_response_is_not_parsable' => 'Błąd PrestaShop. Odpowiedź HTTP XML nie jest analizowalna.',
            'http_response_is_empty' =>'Błąd PrestaShop. Odpowiedź HTTP jest pusta.',
            'bad_parameters_given' =>'Podano niepoprawne parametry.',
            'api_is_disabled' =>'Interfejs API PrestaShop jest wyłączony. Proszę go aktywować w panelu administratora PrestaShop.',
            'invalid_api_key_format' => 'Błąd API PrestaShop. Niepoprawny format klucza API.',
            'api_key_is_not_active'=> 'Błąd API PrestaShop. Klucz API jest nieaktywny.',
            'no_permission_api_key' =>'Błąd API PrestaShop. Brak uprawnień. Proszę ustawić uprawnienia w panelu administratora PrestaShop.'
        ]
    ],
];
