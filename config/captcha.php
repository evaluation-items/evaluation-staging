<?php

return [
    'generator' => \Mews\Captcha\Generators\ImageGenerator::class,

    'disable' => env('CAPTCHA_DISABLE', false),
    'characters' => ['2', '3', '4', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'm', 'n', 'p', 'q', 'r', 't', 'u', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'M', 'N', 'P', 'Q', 'R', 'T', 'U', 'X', 'Y', 'Z'],
    'default' => [
        'length' => 9,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'math' => false,
        'expire' => 60,
        'encrypt' => false,
    ],
    'math' => [
        'length' => 9,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'math' => true,
    ],

    'flat' => [
        'length' => 5,
        'width' => 150,
        'height' => 50,
        'quality' => 90,
        // 'bgImage' => true,
        // 'bgColor' => '#ecf2f4',
        'fontColors' => ['#2c3e50', '#c0392b', '#16a085', '#c0392b', '#8e44ad', '#303f9f', '#f57c00', '#795548'],
        'contrast' => -5,
    ],
    'mini' => [
        'length' => 3,
        'width' => 60,
        'height' => 32,
    ],
    'inverse' => [
        'length' => 5,
        'width' => 120,
        'height' => 36,
        'quality' => 90,
        'sensitive' => true,
        'angle' => 12,
        'sharpen' => 10,
        'blur' => 2,
        'invert' => true,
        'contrast' => -5,
    ],
    'image' => [
    'format' => 'png', // Can be 'jpeg' or 'png'
    'mime' => 'image/png', // Matches the format
],
'clean' => [
    'length' => 4,
    'width' => 130,
    'height' => 40,
    'quality' => 100,

    // Solid background
    'bgImage' => false,
    'bgColor' => '#f4f6f9',

    // Plain text only
    'fontColors' => ['#000000'],
    'fontSize' => 22,

    // 🚫 Disable ALL noise sources
    'drawLines' => false,
    'drawCurves' => false,

    // 🔥 THESE TWO ARE CRITICAL
    'lines' => 0,
    'curves' => 0,

    // 🚫 Disable distortion
    'angle' => 0,
    'contrast' => 0,
    'sharpen' => 0,
    'blur' => 0,
],
];
