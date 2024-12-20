<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use App\Models\User;
use Config\Services;
USE App\Libraries\Hash;
use App\Models\Candidate;
use App\Models\Results;
use App\Models\Setting;
use App\Models\SocialMedia;
use CodeIgniter\I18n\Time;
use Dompdf\Dompdf;
use SSP;
use SawaStacks\CodeIgniter\Slugify;

class AdminController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions'];
    protected $db;

    public function __construct()
    {
        require_once APPPATH.'ThirdParty/ssp.php';
        $this->db = db_connect();
    }

    public function index()
    {
        $data = [
            'pageTitle' => 'Votación Virtual',
        ];
        return view('backend/pages/home-admin', $data);
    }

    public function logoutHandler()
    {
        CIAuth::forget();
        return redirect()->route('admin.login.form')->with('fail', 'Sesión finalizada.');
    }

    public function profile()
    {
        $data = array(
            'pageTitle' => 'Votación Virtual'
        );
        return view('backend/pages/profile', $data);
    }

    public function updatePersonalDetails()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $user_id = CIAuth::id();

        if ( $request->isAJAX() ) {
            $this->validate([
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'El nombre es obligatorio.'
                    ]
                ],
                'username' => [
                    'rules' => 'required|min_length[4]|is_unique[users.username,id,' . $user_id . ']',
                    'errors' => [
                        'required' => 'El usuario es obligatorio.',
                        'min_length' => 'El usuario debe contener al menos 4 caracteres de longitud.',
                        'is_unique' => 'El usuario ya existe.'
                    ]
                ]
            ]);

            if ( $validation->run() == FALSE ) {
                $errors = $validation->getErrors();
                return json_encode(['status' => 0, 'error' => $errors]);
            } else {
                $user = new User();
                $update = $user->where( 'id', $user_id)->set([
                                        'name' => $request->getVar('name'),
                                        'username' => $request->getVar('username'),
                                        'bio' => $request->getVar('bio'),
                                ])->update();

                if ( $update ) {
                    $user_info = $user->find($user_id);
                    return json_encode(['status' => 1, 'user_info' => $user_info, 'msg' => 'Tus datos personales han sido actualizados.']);
                } else {
                    return json_encode(['status' => 0, 'msg' => 'Algo salió mal. Intente nuevamente.']);
                }
            }
        }
    }

    public function updateProfilePicture()
    {
        $request = \Config\Services::request();
        $user_id = CIAuth::id();

        $user = new User();
        $user_info = $user->asObject()->where('id', $user_id)->first();

        $path = 'images/users/';
        $file = $request->getFile('user_profile_file');

        $old_picture = $user_info->picture;
        $new_filename = 'UIMG_' . $user_id . $file->getRandomName();

        // Image manipulation
        $upload_image = \Config\Services::image()->withFile($file)->resize(450,450,true,'height')->save($path . $new_filename);

        if ( $upload_image ) {
            if ( $old_picture != null && file_exists($path . $old_picture)) {
                unlink($path . $old_picture);
            }
            $user->where('id', $user_info->id)->set(['picture' => $new_filename])->update();
            echo json_encode(['status' => 1, 'msg' => 'Tu foto de perfil ha sido actualizada.']);
        } else {
            echo json_encode(['status' => 0, 'msg' => 'Algo salió mal. Intente nuevamente.']);
        }
    }

    public function changePassword()
    {
        $request = \Config\Services::request();

        if ( $request->isAJAX() ) {
            $validation = \Config\Services::validation();
            $user_id = CIAuth::id();
            $user = new User();
            $user_info = $user->asObject()->where('id', $user_id)->first();

            // Validate the form
            $this->validate([
                'current_password' => [
                    'rules' => 'required|min_length[5]|check_current_password[current_password]',
                    'errors' => [
                        'required' => 'La contraseña actual es obligatoria.',
                        'min_length' => 'La contraseña actual debe contener al menos 5 caracteres de longitud.',
                        'check_current_password' => 'La contraseña actual es incorrecta.'
                    ]
                ],
                'new_password' => [
                    'rules' => 'required|min_length[5]|max_length[45]|is_password_strong[new_password]',
                    'errors' => [
                        'required' => 'La nueva contraseña es obligatoria.',
                        'min_length' => 'La nueva contraseña debe contener al menos 5 caracteres de longitud.',
                        'max_length' => 'La nueva contraseña no debe exceder 45 caracteres de longitud.',
                        'is_password_strong' => 'La nueva contraseña debe contener al menos 1 mayúscula, 1 minúscula, 1 número y 1 carácter especial.'
                    ]
                ],
                'confirm_new_password' => [
                    'rules' => 'required|matches[new_password]',
                    'errors' => [
                        'required' => 'Confirme la nueva contraseña.',
                        'matches' => 'Las contraseñas no coinciden.'
                    ]
                ]
            ]);

            if ( $validation->run() == FALSE ) {
                $errors = $validation->getErrors();
                return $this->response->setJSON(['status' => 0, 'token' => csrf_hash(), 'error' => $errors]);
            } else {
                // Update user (admin) password in DB
                $user->where('id', $user_info->id)->set(['password' => Hash::make($request->getVar('new_password'))])->update();

                // Send email notification to user (admin) email address
                $mail_data = array(
                    'user' => $user_info,
                    'new_password' => $request->getVar('new_password')
                );
        
                $view = \Config\Services::renderer();
                $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates/password-changed-email-template');
        
                $mailConfig = array(
                    'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                    'mail_from_name' => env('EMAIL_FROM_NAME'),
                    'mail_recipient_email' => $user_info->email,
                    'mail_recipient_name' => $user_info->name,
                    'mail_subject' => 'Password Changed',
                    'mail_body' => $mail_body
                );

                sendEmail($mailConfig);
                return $this->response->setJSON(['status' => 1, 'token' => csrf_hash(), 'msg' => 'Tu contraseña ha sido actualizada.']);
            }
        }
    }

    public function settings()
    {
        $data = [
            'pageTitle' => 'Votación Virtual'
        ];
        return view('backend/pages/settings', $data);
    }

    public function updateGeneralSettings()
    {
        $request = \Config\Services::request();

        if ( $request->isAJAX() ) {
            $validation = \Config\Services::validation();

            $this->validate([
                'blog_title' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Blog title is required.'
                    ]
                ],
                'blog_email' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => 'Blog email is required.',
                        'valid_email' => 'Invalid email address.'
                    ]
                ]
            ]);

            if ( $validation->run() == FALSE ) {
                $errors = $validation->getErrors();
                return $this->response->setJSON(['status' => 0, 'token' => csrf_hash(), 'error' => $errors]);
            } else {
                $settings = new Setting();
                $setting_id = $settings->asObject()->first()->id;
                $update = $settings->where('id', $setting_id)->set([
                                'blog_title' => $request->getVar('blog_title'),
                                'blog_email' => $request->getVar('blog_email'),
                                'blog_phone' => $request->getVar('blog_phone'),
                                'blog_meta_keywords' => $request->getVar('blog_meta_keywords'),
                                'blog_meta_description' => $request->getVar('blog_meta_description')
                            ])->update();

                if ( $update ) {
                    return $this->response->setJSON(['status' => 1, 'token' => csrf_hash(), 'msg' => 'General settings have been updated successfully.']);
                } else {
                    return $this->response->setJSON(['status' => 0, 'token' => csrf_hash(), 'msg' => 'Something went wrong.']);
                } 
            } 
        }
    }

    public function updateBlogLogo()
    {
        $request = \Config\Services::request();

        if ( $request->isAJAX() ) {
            $settings = new Setting();
            $path = 'images/blog/';
            $file = $request->getFile('blog_logo');
            $settings_data = $settings->asObject()->first();
            $old_blog_logo = $settings_data->blog_logo;
            $new_filename = 'TaxBlog_logo' . $file->getRandomName();

            if ( $file->move($path, $new_filename) ) {

                if ( $old_blog_logo != null && file_exists($path . $old_blog_logo) ) {
                    unlink($path . $old_blog_logo);
                }
                $update = $settings->where('id', $settings_data->id)->set(['blog_logo' => $new_filename])->update();

                if ( $update ) {
                    return $this->response->setJSON(['status' => 1, 'token' => csrf_hash(), 'msg' => 'Done! Tax Management logo has been successfully updated.']);
                } else {
                    return $this->response->setJSON(['status' => 0, 'token' => csrf_hash(), 'msg' => 'Something went wrong on updating new logo info.']);
                }
                
            } else {
                return $this->response->setJSON(['status' => 0, 'token' => csrf_hash(), 'msg' => 'Something went wrong on uploading new logo.']);
            }
        } 
    }

    public function updateBlogFavicon()
    {
        $request = \Config\Services::request();

        if ( $request->isAJAX() ) {
            $settings = new Setting();
            $path = 'images/blog/';
            $file = $request->getFile('blog_favicon');
            $settings_data = $settings->asObject()->first();
            $old_blog_favicon = $settings_data->blog_favicon;
            $new_filename = 'Tax_favicon_' . $file->getRandomName();

            if ( $file->move($path, $new_filename) ) {

                if ( $old_blog_favicon != null && file_exists($path . $old_blog_favicon) ) {
                    unlink($path . $old_blog_favicon);
                }
                $update = $settings->where('id', $settings_data->id)->set(['blog_favicon' => $new_filename])->update();

                if ( $update ) {
                    return $this->response->setJSON(['status' => 1, 'token' => csrf_hash(), 'msg' => 'Done! Tax Management favicon has been successfully updated.']);
                } else {
                    return $this->response->setJSON(['status' => 0, 'token' => csrf_hash(), 'msg' => 'Something went wrong on updating new favicon file.']);
                }
                
            } else {
                return $this->response->setJSON(['status' => 0, 'token' => csrf_hash(), 'msg' => 'Something went wrong on uploading new favicon file.']);
            }
        } 
    }

    public function updateSocialMedia()
    {
        $request = \Config\Services::request();

        if ( $request->isAJAX() ) {
            $validation = \Config\Services::validation();
            $this->validate([
                'facebook_url' => [
                    'rules' => 'permit_empty|valid_url_strict',
                    'errors' => [
                        'valid_url_strict' => 'Invalid facebook page URL.'
                    ]
                ],
                'twitter_url' => [
                    'rules' => 'permit_empty|valid_url_strict',
                    'errors' => [
                        'valid_url_strict' => 'Invalid twitter URL.'
                    ]
                ],
                'instagram_url' => [
                    'rules' => 'permit_empty|valid_url_strict',
                    'errors' => [
                        'valid_url_strict' => 'Invalid instagram URL.'
                    ]
                ],
                'youtube_url' => [
                    'rules' => 'permit_empty|valid_url_strict',
                    'errors' => [
                        'valid_url_strict' => 'Invalid YouTube channel URL.'
                    ]
                ],
                'github_url' => [
                    'rules' => 'permit_empty|valid_url_strict',
                    'errors' => [
                        'valid_url_strict' => 'Invalid GitHub URL.'
                    ]
                ],
                'linkedin_url' => [
                    'rules' => 'permit_empty|valid_url_strict',
                    'errors' => [
                        'valid_url_strict' => 'Invalid LinkedIn URL.'
                    ]
                ],
            ]);

            if ( $validation->run() == FALSE ) {
                $errors = $validation->getErrors();
                return $this->response->setJSON(['status' => 0, 'token' => csrf_hash(), 'error' => $errors]);
            } else {
                $social_media = new SocialMedia();
                $social_media_id = $social_media->asObject()->first()->id;
                $update = $social_media->where('id', $social_media_id)->set([
                                'facebook_url' => $request->getVar('facebook_url'),
                                'twitter_url' => $request->getVar('twitter_url'),
                                'instagram_url' => $request->getVar('instagram_url'),
                                'youtube_url' => $request->getVar('youtube_url'),
                                'github_url' => $request->getVar('github_url'),
                                'linkedin_url' => $request->getVar('linkedin_url'),
                            ])->update();

                if ( $update ) {
                    return $this->response->setJSON(['status' => 1, 'token' => csrf_hash(), 'msg' => 'Done! Tax Management social media has been successfully updated.']);
                } else {
                    return $this->response->setJSON(['status' => 0, 'token' => csrf_hash(), 'msg' => 'Something went wrong on updating social media.']);
                }    
            }
        }
    }

    public function categories()
    {
        $data = [
            'pageTitle' => 'Categories'
        ];
        return view('backend/pages/categories', $data);
    }

    public function getCategories()
    {
        //DB details
        $dbDetails = array(
            "host" => $this->db->hostname,
            "user" => $this->db->username,
            "pass" => $this->db->password,
            "db"   => $this->db->database
        );

        $table = "categories";
        $primaryKey = "id";
        $columns = array(
            array(
                "db" => "id",
                "dt" => 0
            ),
            array(
                "db" => "name",
                "dt" => 1
            ),
            array(
                "db" => "id",
                "dt" => 2,
                "formatter" => function ($d, $row) {
                    return "(x) will be added later.";
                }
            ),
            array(
                "db" => "id",
                "dt" => 3,
                "formatter" => function ($d, $row) {
                    return "<div class='btn-group'>
                                <button class='btn btn-sm btn-link p-0 mx-1 editCategoryBtn' data-id='" . $row['id'] . "'>Edit</button>
                                <button class='btn btn-sm btn-link p-0 mx-1 deleteCategoryBtn' data-id='" . $row['id'] . "'>Delete</button>
                            </div>";
                }
            ),
            array(
                "db" => "ordering",
                "dt" => 4
            )
        );

        return json_encode(
            SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
        );
    }

    public function getVotingResults()
    {
        $request = \Config\Services::request();

        if ( $request->isAJAX() ) {
            $candidates = new Candidate();
            $candidatesArray = $candidates->getCandidatesData();
            $results = new Results();
            $resultsArray = $results->getResultsData();
            
            $votingResults = $candidatesArray;

            foreach ($candidatesArray as $key => $rowCandidates) {
                foreach ($resultsArray as $rowResults) {
                    if ($rowCandidates->candidate_number == $rowResults->candidate_number) {
                        $votingResults[$key] = array(
                            'name' => $rowCandidates->name,
                            'candidate_number' => $rowResults->candidate_number,
                            'total' => $rowResults->total
                        );
                        continue 2;
                    }
                }
            }

            // Sort an array by column value
            $total = array_column($votingResults, 'total');
            array_multisort($total, SORT_DESC, $votingResults);

            return $this->response->setJSON($votingResults);
        }
    }

    public function participants()
    {
        $data = [
            'pageTitle' => 'Votación Virtual'
        ];
        return view('backend/pages/participants', $data);
    }

    public function getParticipants()
    {
        //DB details
        $dbDetails = array(
            "host" => $this->db->hostname,
            "user" => $this->db->username,
            "pass" => $this->db->password,
            "db"   => $this->db->database
        );

        $table = "voters";
        $primaryKey = "id";
        $columns = array(
            array(
                "db" => "id",
                "dt" => 0,
                "formatter" => function ($d, $row) {
                    $zerofill = sprintf('%06d', $d);
                    return $zerofill;
                }
            ),
            array(
                "db" => "name",
                "dt" => 1
            ),
            array(
                "db" => "user_id",
                "dt" => 2,
                "formatter" => function ($d, $row) {
                    $user_id_hidden = substr_replace($d, "***", 5, 3);
                    return $user_id_hidden;
                }
            ),
            array(
                "db" => "status",
                "dt" => 3,
                "formatter" => function ($d, $row) {
                    if ($d == 1) {
                        return "<div class='btn-group'>
                                    <button class='btn btn-sm btn-success p-1' style='min-width: 96px; pointer-events: none;'>Habilitado</button>
                                </div>";
                    } else {
                        return "<div class='btn-group'>
                                    <button class='btn btn-sm btn-danger p-1' style='min-width: 96px; pointer-events: none;'>Inhabilitado</button>
                                </div>";
                    }
                }
            )
        );

        return json_encode(
            SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns)
        );
    }

    public function resultsPDF()
    {
        $zero = 0;

        $blank_votes = get_votes_blank();

        ( empty(count_voters()) ) ? $total_voters = 0 : $total_voters = count_voters();

        if ( empty(count_votes()) ) {
            $total_votes = $zero;
            $blank_votes_pct = $zero;
            $total_votes_pct = $zero;
        } else {
            $total_votes = count_votes();
            $blank_votes_pct = $blank_votes / $total_votes * 100;
            $total_votes_pct = (get_votes_candidates()[0]->votes + get_votes_candidates()[1]->votes + $blank_votes) / $total_votes * 100;
        }
        
        if ( get_votes_candidates()[0]->votes != 0 ) {
            $candidate_1 = get_votes_candidates()[0]->name;
            $candidate_1_votes = get_votes_candidates()[0]->votes;
            $candidate_1_valid_votes = $candidate_1_votes / (get_votes_candidates()[0]->votes + get_votes_candidates()[1]->votes) * 100;
            $candidate_1_casted_votes = $candidate_1_votes / $total_votes * 100;

            $candidate_2 = get_votes_candidates()[1]->name;
            $candidate_2_votes = get_votes_candidates()[1]->votes;
            $candidate_2_valid_votes = $candidate_2_votes / (get_votes_candidates()[0]->votes + get_votes_candidates()[1]->votes) * 100;
            $candidate_2_casted_votes = $candidate_2_votes / $total_votes * 100;

            $total_valid_votes = get_votes_candidates()[0]->votes + get_votes_candidates()[1]->votes;
            $total_valid_votes_pct = $total_valid_votes / $total_valid_votes * 100;
            $total_valid_votes_pct_casted = $total_valid_votes / $total_votes * 100;
        } else {
            $candidate_1 = get_candidates()[0]->name;
            $candidate_1_votes = $zero;
            $candidate_1_valid_votes = $zero;
            $candidate_1_casted_votes = $zero;

            $candidate_2 = get_candidates()[1]->name;
            $candidate_2_votes = $zero;
            $candidate_2_valid_votes = $zero;
            $candidate_2_casted_votes = $zero;

            $total_valid_votes = $zero;
            $total_valid_votes_pct = $zero;
            $total_valid_votes_pct_casted = $zero;
        }
        
        $current_time = Time::now('America/Lima')->format('YmdHis');
        $string_time = "ACTUALIZADO EL " . Time::now('America/Lima')->format('d/m/Y') . " A LAS " . Time::now('America/Lima')->format('H:i');

        $dompdf = new Dompdf();
        
        $dompdf->loadHtml(
            '<html>
                <head>
                    <style>
                        @page {
                            margin: 128px 50px 80px 50px;
                        }
                        header {
                            position: fixed;
                            top: -84px;
                            height: 66px;
                            width: 100%;
                        }
                        .title-pdf {
                            font-family: "Comic Sans MS", cursive, sans-serif;
                            font-size: 11pt;
                        }
                    </style>
                </head>
                <body>
                    <header>
                        <div style="width:98%; height:100%; margin:auto; border-bottom: 1.5px solid #99B1D5;">
                            <div style="display:table; float:left; height:100%;">
                                <div style="display:table-cell; vertical-align:bottom; width:40%; height:100%;">
                                    <img src="' . ImageToDataUrl('images/blog/logo-sindicato-pdf.png') . '" alt="logo-sindicato" height="60" style="padding-bottom:3px;">
                                </div>
                            </div>
                            <div style="display:table; float:right; width:60%; height:100%; text-align:right;">
                                <text class="title-pdf" style="display:table-cell; vertical-align:bottom; height:100%; color:#005187;"><i>JUNTA DIRECTIVA DEL SINDICATO DE LA LIBERTAD</i></text>
                            </div>
                        </div>
                    </header>
                    <main>
                        <br>
                        <table style="width:100%; font-size:10pt; font-family:Inter, sans-serif; text-align:center; color:white; border:2px solid #007BCC; border-collapse:collapse;">
                            <tr style="background:#0089E3; border:2px solid #007BCC; border-collapse:collapse;">
                                <th style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">ELECCIONES DE LA JUNTA DIRECTIVA DEL SINDICATO DE LA LIBERTAD 2025-2026</th>
                            </tr>
                            <tr style="color:black; border:2px solid #007BCC; border-collapse:collapse;">
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">PRESENTACIÓN DE RESULTADOS</td>
                            </tr>
                        </table>
                        <br><br>
                        <table style="width:100%; font-size:10pt; font-family:Inter, sans-serif; text-align:center; color:white; border:2px solid #007BCC; border-collapse:collapse;">
                            <tr style="background:#0089E3; border:2px solid #007BCC; border-collapse:collapse;">
                                <th style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">ACTUALIZACIÓN</th>
                                <th style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">ELECTORES HÁBILES</th>
                            </tr>
                            <tr style="color:black; border:2px solid #007BCC; border-collapse:collapse;">
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . $string_time . '</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format($total_voters, 0, '', ',') . '</td>
                            </tr>
                        </table>
                        <br><br>
                        <table style="width:100%; font-size:10pt; font-family:Inter, sans-serif; text-align:center; color:white; border:2px solid #007BCC; border-collapse:collapse;">
                            <tr style="background:#0089E3; border:2px solid #007BCC; border-collapse:collapse;">
                                <th colspan="2" style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">PARTICIPACIÓN</th>
                                <th colspan="2" style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">AUSENTISMO</th>
                            </tr>
                            <tr style="color:black; border:2px solid #007BCC; border-collapse:collapse;">
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">TOTAL ASISTENTES</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">% TOTAL ASISTENTES</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">TOTAL AUSENTES</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">% TOTAL AUSENTES</td>
                            </tr>
                            <tr style="color:black; border:2px solid #007BCC; border-collapse:collapse;">
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format($total_votes, 0, '', ',') . '</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format($total_votes / $total_voters * 100, 3, '.', '') . ' %</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format($total_voters - $total_votes, 0, '', ',') . '</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format(($total_voters - $total_votes) / $total_voters * 100, 3, '.', '') . ' %</td>
                            </tr>
                        </table>
                        <br><br>
                        <table style="width:100%; font-size:10pt; font-family:Inter, sans-serif; text-align:center; color:white; border:2px solid #007BCC; border-collapse:collapse;">
                            <tr style="background:#0089E3; border:2px solid #007BCC; border-collapse:collapse;">
                                <th style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">LISTAS INSCRITAS</th>
                                <th style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">TOTAL</th>
                                <th style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">% VÁLIDOS</th>
                                <th style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">% EMITIDOS</th>
                            </tr>
                            <tr style="color:black; border:2px solid #007BCC; border-collapse:collapse;">
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . $candidate_1 . '</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format($candidate_1_votes, 0, '', ',') . '</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format($candidate_1_valid_votes, 3, '.', '') . ' %</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format($candidate_1_casted_votes, 3, '.', '') . ' %</td>
                            </tr>
                            <tr style="color:black; border:2px solid #007BCC; border-collapse:collapse;">
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . $candidate_2 . '</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format($candidate_2_votes, 0, '', ',') . '</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format($candidate_2_valid_votes, 3, '.', '') . ' %</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format($candidate_2_casted_votes, 3, '.', '') . ' %</td>
                            </tr>
                            <tr style="color:black; border:2px solid #007BCC; border-collapse:collapse;">
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">TOTAL DE VOTOS VÁLIDOS</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format($total_valid_votes, 0, '', ',') . '</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format($total_valid_votes_pct, 3, '.', '') . ' %</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format($total_valid_votes_pct_casted, 3, '.', '') . ' %</td>
                            </tr>
                            <tr style="color:black; border:2px solid #007BCC; border-collapse:collapse;">
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">VOTOS EN BLANCO</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format($blank_votes, 0, '', ',') . '</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;"></td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format($blank_votes_pct, 3, '.', '') . ' %</td>
                            </tr>
                            <tr style="color:black; border:2px solid #007BCC; border-collapse:collapse;">
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">TOTAL DE VOTOS EMITIDOS</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format($total_votes, 0, '', ',') . '</td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;"></td>
                                <td style="padding:0.25em; border:2px solid #007BCC; border-collapse:collapse;">' . number_format($total_votes_pct, 3, '.', '') . ' %</td>
                            </tr>
                        </table>
                    </main>
                </body>
            </html>'
        );

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        // add pagination
        $canvas = $dompdf->getCanvas(); // get the canvas
        // add the page number and total number of pages
        $canvas->page_script('
            $text = "$PAGE_NUM / $PAGE_COUNT";
            $pdf->text(543, 796, $text, \'Helvetica\', 7, array(0,0,0));
        ');

        // $dompdf->stream("elecciones-junta-directiva-" . $current_time . ".pdf", array("Attachment" => false));
        $dompdf->stream("elecciones-junta-directiva-" . $current_time . ".pdf");
    }

}
