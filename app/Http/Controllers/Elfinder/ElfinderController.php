<?php namespace App\Http\Controllers\Elfinder;

use Log;
use Auth;
use File;
use Barryvdh\Elfinder\Session\LaravelSession;
use Barryvdh\Elfinder\Connector;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\Storage\Memory;
use League\Flysystem\Filesystem;

class ElfinderController extends \Barryvdh\Elfinder\ElfinderController
{

    public function showIndex()
    {
        return $this->app['view']
            ->make($this->package . '::elfinder')
            ->with($this->getViewVars());
    }

    public function showCKeditor4()
    {
        $input = Request::input();
        $type = empty($input['type']) ? : $input['type'];
        return $this->app['view']
            ->make($this->package . '::ckeditor4')
            ->with($this->getViewVars())->with(['type' => $type]);
    }

    public function showPopup($input_id)
    {
        $type = Request::input('type');
        return $this->app['view']
            ->make($this->package . '::standalonepopup')
            ->with($this->getViewVars())
            ->with(compact('input_id', 'type'));
    }

    public function showFilePicker($input_id)
    {
        $type = Request::input('type');
        return $this->app['view']
            ->make($this->package . '::filepicker')
            ->with($this->getViewVars())
            ->with(compact('input_id','type'));
    }

    protected function connectors(){
        $roots = $this->app->config->get('elfinder.roots', []);
        if (empty($roots)) {
            $dirs = (array) $this->app['config']->get('elfinder.dir', []);
            foreach ($dirs as $dir) {
                $roots[] = [
                    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                    'path' => public_path($dir), // path to files (REQUIRED)
                    'URL' => url($dir), // URL to files (REQUIRED)
                    'accessControl' => $this->app->config->get('elfinder.access') // filter callback (OPTIONAL)
                ];
            }

            $disks = (array) $this->app['config']->get('elfinder.disks', []);
            foreach ($disks as $key => $root) {
                if (is_string($root)) {
                    $key = $root;
                    $root = [];
                }
                $disk = app('filesystem')->disk($key);
                if ($disk instanceof FilesystemAdapter) {
                    $filesystem = $disk->getDriver();
                    if (method_exists($filesystem, 'getAdapter')) {
                        $adapter = $filesystem->getAdapter();
                        if ( ! $adapter instanceof CachedAdapter) {
                            $adapter = new CachedAdapter($adapter, new Memory());
                            $filesystem = new Filesystem($adapter);
                        }
                    }
                    $defaults = [
                        'driver' => 'Flysystem',
                        'filesystem' => $filesystem,
                        'alias' => $key,
                    ];
                    $roots[] = array_merge($defaults, $root);
                }
            }
        }

        if (app()->bound('session.store')) {
            $sessionStore = app('session.store');
            $session = new LaravelSession($sessionStore);
        } else {
            $session = null;
        }

        $rootOptions = $this->app->config->get('elfinder.root_options', array());
        foreach ($roots as $key => $root) {
            $roots[$key] = array_merge($rootOptions, $root);
        }

        $opts = $this->app->config->get('elfinder.options', array());
        $opts = array_merge($opts, ['roots' => $roots, 'session' => $session]);

        // run elFinder
        $connector = new Connector(new \elFinder($opts));
        $connector->run();
        return $connector->getResponse();
    }

    public function showConnector()
    {
        $type = Request::input('type');

        $roots = $this->getRoots();

        $tempRoots = $roots;
        $roots = [];

        if(in_array($type, ['images', 'video'])){
            if(!empty($tempRoots['images']) && $type == 'images'){
                $roots['images'] = $tempRoots['images'];
            }
            if(!empty($tempRoots['video']) && $type == 'video'){
                $roots['video'] = $tempRoots['video'];
            }
        } else {
            // make user home dir and return home & share dirs;
            if(!empty($tempRoots['share'])){
                $roots['share'] = $tempRoots['share'];
            }

            $user = Auth::guard('admin')->user();
            $path = $this->getUserHomeDir($user);
            $roots[$user->email] = [
                'driver' => 'LocalFileSystem',
                'path' => $path,
                'startPath' => $path,
                'URL' => '/files/admin/user/'.$user->id,
                'alias' => 'Home',
            ];
        }

        if (app()->bound('session.store')) {
            $sessionStore = app('session.store');
            $session = new LaravelSession($sessionStore);
        } else {
            $session = null;
        }

        $rootOptions = $this->app->config->get('elfinder.root_options', array());
        foreach ($roots as $key => $root) {
            $roots[$key] = array_merge($rootOptions, $root);
        }

        $opts = $this->app->config->get('elfinder.options', array());
        $opts = array_merge($opts, ['roots' => $roots, 'session' => $session]);

        // run elFinder
        $connector = new Connector(new \elFinder($opts));
        $connector->run();
        return $connector->getResponse();
    }

    protected function getRoots(){
        $roots = $this->app->config->get('elfinder.roots', []);
        if (empty($roots)) {
            $dirs = (array) $this->app['config']->get('elfinder.dir', []);
            foreach ($dirs as $dir) {
                $roots[] = [
                    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                    'path' => public_path($dir), // path to files (REQUIRED)
                    'URL' => url($dir), // URL to files (REQUIRED)
                    'accessControl' => $this->app->config->get('elfinder.access') // filter callback (OPTIONAL)
                ];
            }

            $disks = (array) $this->app['config']->get('elfinder.disks', []);
            foreach ($disks as $key => $root) {
                if (is_string($root)) {
                    $key = $root;
                    $root = [];
                }
                $disk = app('filesystem')->disk($key);
                if ($disk instanceof FilesystemAdapter) {
                    $filesystem = $disk->getDriver();
                    if (method_exists($filesystem, 'getAdapter')) {
                        $adapter = $filesystem->getAdapter();
                        if ( ! $adapter instanceof CachedAdapter) {
                            $adapter = new CachedAdapter($adapter, new Memory());
                            $filesystem = new Filesystem($adapter);
                        }
                    }
                    $defaults = [
                        'driver' => 'Flysystem',
                        'filesystem' => $filesystem,
                        'alias' => $key,
                    ];
                    $roots[] = array_merge($defaults, $root);
                }
            }
        }

        return $roots;
    }

    protected function getUserHomeDir($user){
        $path = public_path().'/files/admin/user/'.$user->id;
        if(!File::exists($path)) {
            File::makeDirectory($path, $mode = 0755, true, true);
        }

        return $path;
    }

}