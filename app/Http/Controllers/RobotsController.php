<?php namespace App\Http\Controllers;

use App;
use Robots;
use Response;

class RobotsController extends Controller
{    

    function index(){
    
        // If on the live server, serve a nice, welcoming robots.txt.
        if (App::environment() == 'production')
        {
            Robots::addUserAgent('*');
            Robots::addSitemap(url('sitemap.xml'));

            Robots::addDisallow('/customer/register');
            Robots::addDisallow('/customer/login');
        } else {
            // If you're on any other server, tell everyone to go away.
            Robots::addDisallow('*');
        }

        return Response::make(Robots::generate(), 200, array('Content-Type' => 'text/plain'));
    }

}