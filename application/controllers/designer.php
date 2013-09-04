<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Designer extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('model_sketch');
        $this->load->model('model_projects');
        $this->load->model('model_roles');
    }
    
    public function index() {        
        if ($this->session->userdata('is_logged_in')) {
            
            $meta_data['page_title'] = 'Skizze bearbeiten: ';

            // Fetch sketch data
            $sketch_id = $this->input->get('sketch_id');
            $body_data['sketch']['version'] = $this->input->get('sketch_version');
            $sketch_data = $this->model_sketch->get_sketch_details($sketch_id);
            
            // Check if sketch ID is valid
            if ($sketch_data === false) {
                $body_data['error_message'] = 'Es ist keine Skizze mit der ID ' . $sketch_id . ' vorhanden.';
            } else {
                // Fetch project info
                $project = $this->model_projects->get_project($sketch_data->projects_id);
                
                // Check if user has permissions to access this sketch
                $user_project_permissions = $this->model_roles->get_role_for_project_and_user($project->id, $this->session->userdata('userid'));
                if ( $user_project_permissions === 'Nicht berechtigt') {
                    $body_data['error_message'] = 'Sie dürfen die Skizze mit der ID ' . $sketch_id . ' nicht öffnen, da Sie nicht für deren Projekt berechtigt sind.';
                } else {
                    $meta_data['page_title'] .= $sketch_data->name;

                    $body_data['sketch']['id'] = $sketch_data->id;
                    $body_data['sketch']['name'] = $sketch_data->name;
                    $body_data['project']['id'] = $project->id;
                    $body_data['project']['name'] = $project->name;
                }
                
            }
            
            $this->load->view("meta", $meta_data);
            $this->load->view("header");
            $this->load->view("designer", $body_data);
            //$this->load->view("footer");
            
        } else {
            redirect('home/restricted');
        }
    }    
}

?>
