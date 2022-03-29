# Flatter

## Simple flatter, allows you to flatten and inflate multidimensional arrays

Usage:
```php
$data = [
    'array' => [
        'key' => 'value',
        'inner' => [
            'key' => 1,
            'with_separator' => 2,
        ],
    ],
];

$flattenData = (new \Flatter($data))->flatten();
// $flattenData = [
//     'array_key' => 'value',
//     'array_inner_key' => 1,
//     'array_inner_with_separator' => 2,
// ]

$inflatedData = (new \Flatter($flattenData))->inflate();
// $inflatedData = [
//     'array' => [
//         'key' => 'value',
//         'inner' => [
//             'key' => 1,
//             'with' => [
//                 'separator' => 2,
//             ],
//         ],
//     ],
// ];
```

With custom separator:
```php
$flattenData = (new \Flatter($data))->withCompositeKeySeparator('#')->flatten();
// $flattenData = [
//     'array#key' => 'value',
//     'array#inner#key' => 1,
//     'array#inner#with_separator' => 2,
// ]

$inflatedData = (new \Flatter($data))->withCompositeKeySeparator('#')->inflate();
// $inflatedData = [
//     'array' => [
//         'key' => 'value',
//         'inner' => [
//             'key' => 1,
//             'with_separator' => 2,
//         ],
//     ],
// ];
```

With escaping separator in original keys:
```php
$flattenData = (new \Flatter($data))->escapingSeparatorInKeys()->flatten();
// $flattenData = [
//     'array_key' => 'value',
//     'array_inner_key' => 1,
//     'array_inner_with\_separator' => 2,
// ]

$inflatedData = (new \Flatter($flattenData))->escapingSeparatorInKeys()->inflate();
// $inflatedData = [
//     'array' => [
//         'key' => 'value',
//         'inner' => [
//             'key' => 1,
//             'with_separator' => 2,
//         ],
//     ],
// ];
```

With closure on keys/values:
```php
$flattenData = (new \Flatter($data))
    ->applyClosureToKeys(static function (string $key) { return strtoupper($key); })
    ->applyClosureToValues(static function ($value) { return (string)$value; })
    ->flatten();
// $flattenData = [
//     'ARRAY_KEY' => 'value',
//     'ARRAY_INNER_KEY' => '1',
//     'ARRAY_INNER_WITH_SEPARATOR' => '2',
// ]

$inflatedData = (new \Flatter($flattenData))
    ->applyClosureToKeys(static function (string $key) { return strtolower($key); })
    ->applyClosureToValues(static function ($value) { return "--{$value}--"; })
    ->inflate();
// $inflatedData = [
//     'array' => [
//         'key' => '--value--',
//         'inner' => [
//             'key' => '--1--',
//             'with' => [
//                 'separator' => '--2--',
//             ],
//         ],
//     ],
// ]
```

## Disclaimer

All information and source code are provided AS-IS, without express or implied warranties.
Use of the source code or parts of it is at your sole discretion and risk.
Citymobil LLC takes reasonable measures to ensure the relevance of the information posted in this repository, but it does not assume responsibility for maintaining or updating this repository or its parts outside the framework established by the company independently and without notifying third parties.


Вся информация и исходный код предоставляются в исходном виде, без явно выраженных или подразумеваемых гарантий. Использование исходного кода или его части осуществляются исключительно по вашему усмотрению и на ваш риск. Компания ООО "Ситимобил" принимает разумные меры для обеспечения актуальности информации, размещенной в данном репозитории, но она не принимает на себя ответственности за поддержку или актуализацию данного репозитория или его частей вне рамок, устанавливаемых компанией самостоятельно и без уведомления третьих лиц.
