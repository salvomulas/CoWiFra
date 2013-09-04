<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class unit_tests extends CI_Controller {
	
	public function test_history() {
		if ($this->session->userdata('is_logged_in')) {
            $this->load->model('model_history');
			$return_state = $this->model_history->add_history_entry('test_history', 'History test', 3);
			echo "test_history result: [" . $return_state . "]";
        } else {
            redirect('home/restricted');
        }
	}
	
	public function test_projects() {
		if ($this->session->userdata('is_logged_in')) {
            $this->load->model('model_projects');
            $this->load->model('model_roles');
			$insert_id = $this->model_projects->add_project($this->config->item('artifacts_location'), 'Unit Test Projekt', 'Beschreibung');
			echo "test_projects insert result: [" . $insert_id . "]<br>";
			
			$result = $this->model_roles->change_project_owner($insert_id, 2);
			echo "test_projects change owner result: [" . $result . "]<br>";
			
			$return_state = $this->model_projects->delete_project($insert_id);
			echo "test_projects delete result: [" . $return_state . "]<br>";
        } else {
            redirect('home/restricted');
        }
	}
	
	public function test_projects_delete() {
		if ($this->session->userdata('is_logged_in')) {
            $this->load->model('model_projects');

			$return_state = $this->model_projects->delete_project(3);
			echo "test_projects delete result: [" . $return_state . "]<br>";
        } else {
            redirect('home/restricted');
        }
	}
	
	public function test_roles() {
		if ($this->session->userdata('is_logged_in')) {
            $this->load->model('model_roles');
			$role = $this->model_roles->get_role_for_project_and_user(1, $this->session->userdata('userid'));
			echo "test_roles result: [" . $role . "]<br>";
			
			$role = $this->model_roles->get_role_for_project_and_user(2, $this->session->userdata('userid'));
			echo "test_roles result: [" . $role . "]<br>";
			
			$role = $this->model_roles->get_role_for_project_and_user(3, $this->session->userdata('userid'));
			echo "test_roles result: [" . $role . "]<br>";
			
			$role = $this->model_roles->get_role_for_project_and_user(4, $this->session->userdata('userid'));
			echo "test_roles result: [" . $role . "]<br>";
        } else {
            redirect('home/restricted');
        }
	}
	
	public function test_project_permissions() {
		if ($this->session->userdata('is_logged_in')) {
            $this->load->model('model_roles');
			$result = $this->model_roles->add_user_to_project(3, 1, 0);
			echo "test_project_permissions add result: [" . $result . "]<br>";
			
			$result1 = $this->model_roles->change_admin_permission(3, 1, 1);
			echo "test_project_permissions change result: [" . $result1 . "]<br>";
			
			$result2 = $this->model_roles->remove_user_from_project(3, 1);
			echo "test_project_permissions remove result: [" . $result2 . "]<br>";
        } else {
            redirect('home/restricted');
        }
	}
	
	public function test_directory_stuff() {
		if ($this->session->userdata('is_logged_in')) {
			$dir = getcwd();
			echo "current working directory: [" . $dir . "]<br>";
        } else {
            redirect('home/restricted');
        }
	}
	
}

?>
