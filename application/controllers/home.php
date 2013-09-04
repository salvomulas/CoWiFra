<?php

/*
 * Controller for signup, login, logout, index pages.
 */

//TODO MP 29.07.2013 this check doesn't seem to do anything to me.
//should be !defined(config_item('BASEPATH'))
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
	
    public function index() {
        $data['page_title'] = "Willkommen";
        $this->load->view("meta", $data);
        $this->load->view("header");
        $this->load->view("home");
    }

    public function signup_validation() {
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.username]');
        $this->form_validation->set_rules('firstname', 'First Name', 'required|trim');
        $this->form_validation->set_rules('lastname', 'Last Name', 'required|trim');
        $this->form_validation->set_rules('email', 'eMail', 'required|trim|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');
        $this->form_validation->set_rules('password_chk', 'Confirm Password', 'required|trim|matches[password]');

        $this->form_validation->set_message('is_unique', "Benutzername oder E-Mail Adresse ist schon vergeben.");

        if ($this->form_validation->run()) {

            $key = sha1(uniqid());
            $this->load->library('email', array('mailtype' => 'html'));
            $this->load->model('model_users');
            $this->email->from('registrations@webre', "Registration WebRE");
            $this->email->to($this->input->post('email'));
            $this->email->subject("Please confirm your account");

            $message = '<p>Dear ' . $this->input->post('firstname') . '<p><br><p><a href="' . base_url() . 'home/confirm/' . $key . '">Click here</a> to confirm your account</p>';

            $this->email->message($message);

            if ($this->model_users->add_temp_user($key)) {
                if ($this->email->send()) {
                    $body_data['success_messages'][] = "Bitte schauen Sie in Ihren Posteingang, um die Registration abzuschliessen. Sie sollten ein E-Mail erhalten haben mit einem Aktivierungslink.";
                } else {
                    $body_data['error_messages'][] = "Mailversand war nicht erfolgreich, bitte wiederholen Sie die Registration.";
                }
            } else {
                $body_data['error_messages'][] = "Der Benutzer konnte nicht in die Datenbank geschrieben werden.";
            }
            
            header('refresh:3;url=/');
            
            $meta_data['page_title'] = 'Registrierung';
            $body_data['page_title'] = $meta_data['page_title'];

            // Show success/error message(s)
            $this->load->view('header');
            $this->load->view('meta', $meta_data);
            $this->load->view('show_message', $body_data);
            
        } else {
            $this->index();
        }
    }
    
    public function confirm($key) {
        $this->load->model('model_users');
        if ($this->model_users->is_key_valid($key)) {
            if ($this->model_users->add_user($key)) {
                $body_data['success_messages'][] = "Sie k&ouml;nnen sich jetzt anmelden. Vielen Dank!";
            } else {
                $body_data['error_messages'][] = "Fehler bei der Registrierung.";
            }
        } else {
            $body_data['error_messages'][] = "Der angegebene Aktivierungsschl&uuml;ssel war ung&uuml;ltig!";
        }
        
        header('refresh:3;url=/');

        $meta_data['page_title'] = 'Registrierung';
        $body_data['page_title'] = $meta_data['page_title'];

        // Show success/error message(s)
        $this->load->view('header');
        $this->load->view('meta', $meta_data);
        $this->load->view('show_message', $body_data);
    }

    public function login_validation() {

        $this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clean|callback_validate_credentials');
        $this->form_validation->set_rules('password', 'Password', 'required|sha1');
        
        if ($this->form_validation->run()) {
            
            $this->load->model('model_users');
            $username = $this->input->post('username');
            $userid = $this->model_users->get_id_for_username($username);

            $data = array(
                'username' => $username,
                'userid' => $userid,
                'is_logged_in' => 1,
            );
            $this->session->set_userdata($data);
            redirect('dashboard');
        } else {
            $this->index();
        }
    }

    public function validate_credentials() {
        $this->load->model('model_users');

        if ($this->model_users->can_log_in()) {
            return true;
        } else {
            $this->form_validation->set_message('validate_credentials', 'Benutzername/Passwort ung&uuml;ltig.');
            return false;
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('/');
    }

    public function restricted() {
        $body_data['error_messages'][] = 'Sie sind nicht berechtigt, diese Seite aufzurufen.';
        if (!$this->session->userdata('is_logged_in')) {
            $body_data['error_messages'][] = 'Bitte melden Sie sich an und versuchen nochmals, die Seite zu laden.';
        }
        
        $meta_data['page_title'] = 'Zugriff verweigert';
        $body_data['page_title'] = $meta_data['page_title'];

        // Show success/error message(s)
        $this->load->view('header');
        $this->load->view('meta', $meta_data);
        $this->load->view('show_message', $body_data);
    }

}

?>