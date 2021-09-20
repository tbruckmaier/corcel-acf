<?php

return [
    'classMapping' => [
        // here you can configure custom classes by post_type
        // 'gallery' => CustomGallery::class,
    ],

    // the class to use when returning a value from a user field
    // 'user_class' => CustomUser::class,

    // which time zone shall DateTime assume? null means to automatically read
    // it from the wp_options table (option "timezone_string")
    'timezone_string' => null,
];
