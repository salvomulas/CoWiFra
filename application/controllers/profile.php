<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Profile extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('model_users');
    }
    
    function _remap($method) {
        $param_offset = 2;

        // Default to index
        if ( ! method_exists($this, $method))
        {
            // We need one more param
            $param_offset = 1;
            $method = 'index';
        }

        // Since all we get is $method, load up everything else in the URI
        $params = array_slice($this->uri->rsegment_array(), $param_offset);

        // Call the determined method with all params
        call_user_func_array(array($this, $method), $params);
    }
    
    /**
     * Displays the profile of a user
     * @author SR
     * @param int $user_id User ID
     */
    public function index($user_id = NULL) {
        if ($this->session->userdata('is_logged_in')) {
            
            // If no user ID specified, display user's own profile
            $user_id = !$user_id ? $this->session->userdata('userid') : $user_id;
            $user_data = $this->model_users->get_user($user_id);

            // Check if user ID is valid
            if (count($user_data) === 0) {
                die('Es ist kein Benutzer mit der ID ' . $user_id . ' vorhanden.');
            } else {
                //
                // Retrieve user's projects
                //
                
                $user_project_data['user_projects_owned'] = array();
                $user_project_data['user_projects_member'] = array();
                
                $this->db->select('p.id AS id, p.name AS name, p.owner AS owner');
                $this->db->from('projects_has_users AS pu');
                $this->db->join('projects AS p', 'pu.projects_id = p.id', 'inner');
                $this->db->where('pu.users_id', $user_id);
                $this->db->where('p.deleted', 0);
                $this->db->order_by('name', 'ASC');
                
                $query = $this->db->get();
                
                foreach ($query->result() as $project) {
                    // Assign project to own/other projects
                    if ($project->owner === $user_id) {
                        $user_project_data['user_projects_owned'][] = $project;
                    } else {
                        $user_project_data['user_projects_member'][] = $project;
                    }
                }                
                
                // Display message if no owned/member projects
                $body_data['message_no_projects_owned'] = (count($user_project_data['user_projects_owned']) === 0)
                        ? 'Keine eigenen Projekte' 
                        : NULL;
                $body_data['message_no_projects_member'] = (count($user_project_data['user_projects_member']) === 0)
                        ? 'Keine &uuml;brigen Projekte'
                        : NULL;
                
                $meta_data['page_title'] = 'Benutzerprofil: ' . $user_data->username;
                $body_data = array_merge($body_data, $this->user_data_to_array($user_data), $user_project_data);
                
                $this->load->view("meta", $meta_data);
                $this->load->view("header");
                $this->load->view("profile_view", $body_data);
                //$this->load->view("footer");
            }

        } else {
            redirect('home/restricted');
        }
    }
    
    /**
     * Allows the user to edit his own profile data
     * @author SR
     */
    public function edit() {
        if ($this->session->userdata('is_logged_in')) {
            
            //
            // Set up form validation
            //
            $this->form_validation->set_rules('firstname', 'Vorname', 'required|trim|xss_clean');
            $this->form_validation->set_rules('lastname', 'Nachname', 'required|trim|xss_clean');
            $this->form_validation->set_rules('email', 'E-Mail', 'required|trim|valid_email');
            $this->form_validation->set_rules('jobdescription', 'Jobbeschreibung', 'trim|xss_clean');
            $this->form_validation->set_rules('company', 'Firma', 'trim|xss_clean');

            $password_required = $this->input->post('password') ? 'required|' : '' ;
            $this->form_validation->set_rules('oldpassword', 'Altes Passwort', $password_required . 'trim|callback__validate_old_password');
            $this->form_validation->set_rules('password', 'Passwort', 'trim');
            $this->form_validation->set_rules('password_chk', 'Confirm Password', $password_required . 'trim|matches[password]');
            $user_data = $this->model_users->get_user($this->session->userdata('userid'));
            
            $this->form_validation->set_message('_validate_old_password', 'Das eingegebene Passwort stimmt nicht mit dem alten Passwort &uuml;berein. Bitte geben Sie das alte Passwort nochmals ein.');
            
            $body_data = array();
            
            if ($this->form_validation->run() == FALSE) {
                $meta_data['page_title'] = 'Benutzerprofil bearbeiten';
                $body_data = $this->user_data_to_array($user_data);

                $this->load->view("meta", $meta_data);
                $this->load->view("header");
                $this->load->view("profile_edit", $body_data);
                //$this->load->view("footer");                
            } else {
                // Save profile data to the database
                $update_success = $this->model_users->edit_user_details(
                        $this->session->userdata('userid'),
                        $this->input->post('firstname'),
                        $this->input->post('lastname'),
                        $this->input->post('email'),
                        $this->input->post('jobdescription'),
                        $this->input->post('company')
                );
                
                if ($update_success) {
                    $body_data['success_messages'][] = 'Ihr Benutzerprofil wurde aktualisiert.';
                }
                
                // Password change requested?
                if (strlen($this->input->post('password')) > 0) {
                    $change_success = $this->model_users->change_user_password(
                            $this->session->userdata('userid'),
                            $this->input->post('oldpassword'),
                            $this->input->post('password')
                            );
                    
                    if($change_success) {
                        $body_data['success_messages'][] = 'Ihr Passwort wurde neu gesetzt. Bitte geben Sie bei der nächsten Anmeldung das neue Passwort an.';
                    } else {
                        $body_data['error_messages'][] = 'Beim &Uuml;bernehmen des neuen Passworts tauchte ein Problem auf. Bitte versuchen Sie es nochmals. Bitte beachten Sie, dass das neue Passwort nicht gleich sein kann wie das aktuelle.';
                    }
                }
                
                // Redirect user if changes were successful, otherwise provide link to edit profile
                if (isset($body_data['success_messages']) && !isset($body_data['error_messages']) ) {
                    header('refresh:3;url=/profile');
                    $body_data['success_messages'][] = 'Sie werden automatisch zu ihrem <a href="/profile">Profil</a> weitergeleitet...';
                } else if (isset($body_data['error_messages'])) {
                    $body_data['error_messages'][] = 'Sie k&ouml;nnen ihr Profil <a href="/profile/edit">hier</a> erneut bearbeiten.';
                } else {
                    header('refresh:3;url=/profile');
                    $body_data['info_messages'][] = 'Es wurden keine &Auml;nderungen an Ihrem Profil durchgef&uuml;hrt.<br />Sie werden automatisch zu ihrem <a href="/profile">Profil</a> weitergeleitet...';
                }
                
                $body_data['page_title'] = 'Benutzerprofil bearbeiten';
                $body_data['breadcrumbs'] = array(
                  array('name'  => 'Profil von ' . $this->session->userdata('username'),
                        'url'   => '/profile'),                    
                  array('name'  => 'Profil bearbeiten',
                        'url'   => '/profile/edit')
                );
                
                $meta_data['page_title'] = 'Benutzerprofil bearbeiten';
                
                // Show a message and redirect thereafter to the profile view                
                $this->load->view("meta", $meta_data);
                $this->load->view("header");
                $this->load->view("show_message", $body_data);
                //$this->load->view("footer");
            }
        }
    }
    
    
    /**
     * Assigns user data to an array for passing on to the view
     * @param Object $user_data
     * @return Array
     */
    private function user_data_to_array($user_data) {
        $user_array['user_id'] = $user_data->id;
        $user_array['user_created'] = $user_data->create_timestamp;
        $user_array['user_name'] = $user_data->username;
        $user_array['user_firstname'] = $user_data->firstname;
        $user_array['user_lastname'] = $user_data->lastname;
        $user_array['user_email'] = $user_data->email;
        $user_array['user_job_description'] = $user_data->job_description;
        $user_array['user_company'] = $user_data->company;

        return $user_array;
    }
    
    /**
     * Form validation method to compare the old password input with the hashed user password.
     * @author SR
     * @param String $old_password
     * @return boolean
     */
    public function _validate_old_password($old_password) {
        if (sizeof($old_password) > 0) {
            $db_password = $this->model_users->get_user($this->session->userdata('userid'))->password;
            $old_password_hashed = sha1($old_password);

            return $db_password === $old_password_hashed;
        } else {
            return true;
        }
    }
    
}

?>