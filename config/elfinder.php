<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Upload dir
    |--------------------------------------------------------------------------
    |
    | The dir where to store the images (relative from public)
    |
    */
    'dir' => ['files'],

    /*
    |--------------------------------------------------------------------------
    | Filesystem disks (Flysytem)
    |--------------------------------------------------------------------------
    |
    | Define an array of Filesystem disks, which use Flysystem.
    | You can set extra options, example:
    |
    | 'my-disk' => [
    |        'URL' => url('to/disk'),
    |        'alias' => 'Local storage',
    |    ]
    */
    'disks' => [
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes group config
    |--------------------------------------------------------------------------
    |
    | The default group settings for the elFinder routes.
    |
    */

    'route' => [
        'prefix' => 'elfinder',
        //'middleware' => 'replace-this-with-your-middleware', //Set to null to disable middleware filter
        'middleware' => ['web', 'admin.auth:admin'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Access filter
    |--------------------------------------------------------------------------
    |
    | Filter callback to check the files
    |
    */

    'access' => 'Barryvdh\Elfinder\Elfinder::checkAccess',

    /*
    |--------------------------------------------------------------------------
    | Roots
    |--------------------------------------------------------------------------
    |
    | By default, the roots file is LocalFileSystem, with the above public dir.
    | If you want custom options, you can set your own roots below.
    |
    */

    'roots' => [
        'images' => [
            'driver'     => 'LocalFileSystem',
            'path'       => base_path().'/public/files/images',
            'startPath'  => base_path().'/public/files/images',
            'URL'        => '/files/images',
            'alias'      => ' 图 片',],
        'video' => [
            'driver'     => 'LocalFileSystem',
            'path'       => base_path().'/public/files/video',
            'startPath'  => base_path().'/public/files/video',
            'URL'        => '/files/video',
            'alias'      => ' 视 频',],
        'share' => [
            'driver'     => 'LocalFileSystem',
            'path'       => base_path().'/public/files/admin/share',
            'startPath'  => base_path().'/public/files/admin/share',
            'URL'        => '/files/admin/share',
            'alias'      => ' 共 享 盘',],
    ],

    /*
      |--------------------------------------------------------------------------
      | Options
      |--------------------------------------------------------------------------
      |
      | These options are merged, together with 'roots' and passed to the Connector.
      | See https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1
      |
    */

    'options' => array(),
    
    /*
      |--------------------------------------------------------------------------
      | Root Options
      |--------------------------------------------------------------------------
      |
      | These options are merged, together with every root by default.
      | See https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1#root-options
      |
    */
    'root_options' => [
    ],

);
