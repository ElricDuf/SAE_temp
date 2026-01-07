<?php

namespace Config;

use CodeIgniter\Shield\Config\AuthView as ShieldAuthView;

class AuthView extends ShieldAuthView
{
    /**
     * Shield utilisera ses propres vues situées dans le dossier vendor.
     * On utilise le namespace \CodeIgniter\Shield\Views pour les désigner.
     */
    public array $views = [
        'login'                       => '\CodeIgniter\Shield\Views\login',
        'register'                    => '\CodeIgniter\Shield\Views\register',
        'layout'                      => '\CodeIgniter\Shield\Views\layout',
        'action_void'                 => '\CodeIgniter\Shield\Views\action_void',
        'magic_link_form'             => '\CodeIgniter\Shield\Views\magic_link_form',
        'magic_link_message'          => '\CodeIgniter\Shield\Views\magic_link_message',
        'magic_link_email'            => '\CodeIgniter\Shield\Views\Email\magic_link_email',
        'verification_email_email'    => '\CodeIgniter\Shield\Views\Email\verification_email_email',
        'verification_email_msg'      => '\CodeIgniter\Shield\Views\verification_email_msg',
        'verification_email_form'     => '\CodeIgniter\Shield\Views\verification_email_form',
    ];
}