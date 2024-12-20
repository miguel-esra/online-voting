<?php

namespace App\Libraries;

use App\Models\Results;
use App\Models\User;
use App\Models\Voter;
use CodeIgniter\I18n\Time;

class CIAuth
{
    public static function setCIAuth($result)
    {
        $session    = session();
        $array      = ['logged_in_user' => true];
        $userdata   = $result;
        $session->set('userdata', $userdata);
        $session->set($array);
    }

    public static function setCIAuthVoter($result)
    {
        $session    = session();
        $array      = ['logged_in_voter' => true];
        $userdata   = $result;
        $session->set('userdata', $userdata);
        $session->set($array);
    }

    public static function check() 
    {
        $session    = session();
        return $session->has('logged_in_user');
    }

    public static function checkVoter() 
    {
        $session    = session();
        return $session->has('logged_in_voter');
    }

    public static function id()
    {
        $session    = session();
        if ($session->has('logged_in_user')) {
            if ($session->has('userdata')) {
                return $session->get('userdata')['id'];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public static function idVoter()
    {
        $session    = session();
        if ($session->has('logged_in_voter')) {
            if ($session->has('userdata')) {
                return $session->get('userdata')['id'];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public static function userIdVoter()
    {
        $session    = session();
        if ($session->has('logged_in_voter')) {
            if ($session->has('userdata')) {
                return $session->get('userdata')['user_id'];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public static function forget() 
    {
        $session    = session();
        $session->remove('logged_in_user');
        $session->remove('userdata');
    }

    public static function forgetVoter() 
    {
        $session    = session();
        $session->remove('logged_in_voter');
        $session->remove('userdata');
    }

    public static function user() 
    {
        $session    = session();
        if ($session->has('logged_in_user')) {
            if ($session->has('userdata')) {
                $user = new User();
                return $user->asObject()->where('id', CIAuth::id())->first();
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public static function voter() 
    {
        $session    = session();
        if ($session->has('logged_in_voter')) {
            if ($session->has('userdata')) {
                $user = new Voter();
                return $user->asObject()->where('id', CIAuth::idVoter())->first();
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public static function maintenance()
    {    
        $session = session();
        $session->remove('maintenance_mode');
        // specific maintenance date and time
        $scheduled_time =  Time::create(2024, 12, 16, 0, 0, 0, 'America/Lima');
        $current_time = Time::now('America/Lima');
        $diff = $current_time->difference($scheduled_time);

        if ( $diff->getSeconds() <= 0 ) {
            if ( $session->has('logged_in_voter') ) {
                $session->remove('logged_in_voter');
                $session->remove('userdata');
            }
            $array = ['maintenance_mode' => true];
            $session->set($array);
        } 

        return $session->has('maintenance_mode');
    }

}
