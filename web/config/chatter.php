<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Uploaded File Storage
    |--------------------------------------------------------------------------
    |
    | Users can upload images in posts and comments.
    | These are stored in the database by default.  In production, you'll want
    | to store them somewhere else.  To do that, set this value to the name of
    | one of the disks defined in 'config/filesystems.php'.
    |
    */

    'uploaded_file_storage' => env('UPLOADED_FILE_STORAGE', 'database'),

    ];

