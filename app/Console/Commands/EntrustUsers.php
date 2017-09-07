<?php

namespace App\Console\Commands;

use Log;
use Illuminate\Console\Command;
use Illuminate\Hashing\BcryptHasher;

class EntrustUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entrust:users
 {--create : add new user }
 {--passwd : change user password }
         ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'chaohun admin users management';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('create')) {
            return $this->createNewUser();
        }

        if ($this->option('passwd')) {
            return $this->changeUserPassword();
        }

        return $this->getUsers();
    }


    protected function getUsers()
    {
        $userClass = $this->getUserClass();

        $headers = ['id', 'name', 'email'];
        $users   = $userClass::get($headers);

        $this->table($headers, $users);
    }

    protected function changeUserPassword(){
        $userClass = $this->getUserClass();
        if (is_null($email = $this->ask('Pls input your email!'))) {
            $this->error('You should input user email.');

            return;
        }

        if (is_null($password = $this->secret('Password'))) {
            $this->error('You should specify password.');

            return;
        }

        $haser = new BcryptHasher();
        try {
            $user = $userClass::where(['email' => $email])->first();
            if($user){
                $user->password = $haser->make($password);
                $user->save();
            }
            $this->info("User [{$user->name}]'s password updated.");
        } catch (\Exception $e) {
            $this->error('Something went wrong. User not updated');

            return;
        }
    }

    protected function createNewUser(){

        $userClass = $this->getUserClass();

        if (is_null($email = $this->ask('Email'))) {
            $this->error('You should specify email.');

            return;
        }

        if (! is_null($userClass::where('email', $email)->first())) {
            $this->error("User with same email [{$email}] exists.");

            return;
        }

        if (is_null($password = $this->secret('Password'))) {
            $this->error('You should specify password.');

            return;
        }

        $passwordConfirm = $this->secret('Password Confirm');

        if ($password !== $passwordConfirm) {
            $this->error('Password confirm failed.');

            return;
        }

        $name = $this->ask('User Name');

        $haser = new BcryptHasher();
        try {
            $user = $userClass::create([
                'email' => $email,
                'password' => $haser->make($password),
                'name' => $name,
            ]);

            $this->info("User [{$user->id}] created.");
        } catch (\Exception $e) {
            $this->error('Something went wrong. User not created');

            return;
        }
    }


    /**
     * @return string
     * @throws \Exception
     */
    protected function getUserClass()
    {
        if (is_null($userClass = config('entrust.user'))) {
            throw new \Exception('User class not specified in config/auth.php providers.');
        }

        return $userClass;
    }
}
