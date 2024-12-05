<?php

use App\Libraries\CIAuth;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Voter;
use App\Models\Results;
use App\Models\Setting;
use App\Models\SocialMedia;

if ( !function_exists('get_user') ) {
    function get_user() {
        if ( CIAuth::check() ) {
            $user = new User();
            return $user->asObject()->where('id', CIAuth::id())->first();
        } else {
            return null;
        }
    }
}

if ( !function_exists('get_voter') ) {
    function get_voter() {
        if ( CIAuth::checkVoter() ) {
            $user = new Voter();
            return $user->asObject()->where('id', CIAuth::idVoter())->first();
        } else {
            return null;
        }
    }
}

if ( !function_exists('get_settings') ) {
    function get_settings() {
        $settings = new Setting();
        $settings_data = $settings->asObject()->first();

        if ( !$settings_data ) {
            $data = array(
                'blog_title' => 'Ventora Digital',
                'blog_email' => 'info@ventoradigital.com',
                'blog_phone' => null,
                'blog_meta_keywords' => null,
                'blog_meta_description' => null,
                'blog_logo' => null,
                'blog_favicon' => null,
            );
            $settings->save($data);
            $new_settings_data = $settings->asObject()->first();
            return $new_settings_data;
        } else {
            return $settings_data;
        }
    }
}

if ( !function_exists('get_social_media') ) {
    function get_social_media() {
        $result = null;
        $social_media = new SocialMedia();
        $social_media_data = $social_media->asObject()->first();

        if ( !$social_media_data ) {
            $data = array(
                'facebook_url' => null,
                'twitter_url' => null,
                'instagram_url' => null,
                'youtube_url' => null,
                'github_url' => null,
                'linkedin_url' => null
            );
            $social_media->save($data);
            $new_social_media_data = $social_media->asObject()->first();
            $result = $new_social_media_data;
        } else {
            $result = $social_media_data;
        }
        return $result;
    }
}

if ( !function_exists('current_route_name') ) {
    function current_route_name() {
        $router = \Config\Services::router();
        $route_name = $router->getMatchedRouteOptions()['as'];
        return $route_name;
    }
}

if ( !function_exists('get_candidates') ) {
    function get_candidates() {
        $candidates = new Candidate();
        $candidates_data = $candidates->asObject()->findAll();

        if ( !$candidates_data ) {
            return null;
        } else {
            return $candidates_data;
        }
    }
}

if ( !function_exists('get_voter_choice') ) {
    function get_voter_choice() {
        if ( CIAuth::checkVoter() ) {
            $result = new Results();
            return $result->asObject()->where('user_id', CIAuth::userIdVoter())->first();
        } else {
            return null;
        }
    }
}

if ( !function_exists('get_voter_choice_details') ) {
    function get_voter_choice_details() {
        if ( CIAuth::checkVoter() ) {
            $result = new Results();
            $candidate_number = $result->asObject()->where('user_id', CIAuth::userIdVoter())->first()->candidate_number;
            $candidate = new Candidate();
            return $candidate->asObject()->where('candidate_number', $candidate_number)->first();
        } else {
            return null;
        }
    }
}

if ( !function_exists('count_voters') ) {
    function count_voters() {
        $voters = new Voter();
        $voters_data = $voters->asObject()->findAll();

        if ( !$voters_data ) {
            return null;
        } else {
            return count($voters_data);
        }
    }
}

if ( !function_exists('count_votes') ) {
    function count_votes() {
        $results = new Results();
        $results_data = $results->asObject()->findAll();

        if ( !$results_data ) {
            return null;
        } else {
            return count($results_data);
        }
    }
}

if ( !function_exists('get_votes_blank') ) {
    function get_votes_blank() {
        $results = new Results();
        $results_data = $results->asObject()->where('candidate_number', 3)->findAll();

        if ( !$results_data ) {
            return null;
        } else {
            return count($results_data);
        }
    }
}

if ( !function_exists('get_votes_candidates') ) {
    function get_votes_candidates() {
        $builder = new Results();
        $candidates = [1, 2];
        $results_data = $builder->select('name, candidate_number, COUNT(candidate_number) votes')
                        ->join('candidates', 'candidate_number', 'left')
                        ->whereIn('candidate_number', $candidates)
                        ->groupBy('candidate_number')->orderBy('votes', 'DESC');

        if ( !$results_data ) {
            return null;
        } else {
            return $results_data->get()->getResult();
        }
    }
}
