<?php

class model_projects extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Add new project to database.
     * @author MP
     * @return Inserted ID (always > 0 -> a true value), if the Insert statement worked. False otherwise. 
     */
    public function add_project($artifacts_location, $name, $description = NULL, $general_requirements = NULL) {
        //TODO MP 29.07.2013: transaction (create project + role) with rollback in case of an error
        $artifacts_directory = sha1(uniqid());
        $data = array(
            'create_timestamp' => date('Y-m-d H:i:s'),
            'name' => $name,
            'description' => $description,
            'general_requirements' => $general_requirements,
            'owner' => $this->session->userdata('userid'),
            'artifacts_directory' => $artifacts_directory,
            'deleted' => 0,
        );
        
        $query = $this->db->insert('projects', $data);
        if ($query) {
            $insert_id = $this->db->insert_id();
            
            //give the owner admin permissions
            $this->load->model('model_roles');
            $return_state = $this->model_roles->add_user_to_project($insert_id, $this->session->userdata('userid'), 1);
            
            if ($return_state) {
            	//create a directory for the artifacts of the project
            	$artifacts_path = $artifacts_location . '/' . $artifacts_directory;
                $mkdir_return = mkdir($artifacts_path);
	            
                //security feature: create an index.html file to prevent directory access.
                $html = <<<HTML
<html>
<head>
        <title>403 Forbidden</title>
</head>
<body>

<p>Directory access is forbidden.</p>

</body>
</html>
HTML;

                $file_return = file_put_contents($artifacts_path . '/' . 'index.html', $html);

                if ($mkdir_return and $file_return) {
                    return $insert_id;
                } else {
                    return false;
                }
            } else {
            	return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * Set deleted flag of a project in the database.
     * Also marks its sketches as deleted.
     * @author MP
     * @return True, if the Update statement worked. False otherwise. 
     */
    public function delete_project($id) {
        $data = array(
            'deleted' => 1,
        );

		//set project deleted flag to 1
        $this->db->where('id', $id);
        $query = $this->db->update('projects', $data);
        
        if ($query) {
        	//set sketch deleted flag to 1 for every sketch of the project
            $this->db->where('projects_id', $id);
            $query = $this->db->update('sketches', $data);
            if ($query) {
            	return true;
            } else {
            	return false;
            }
        } else {
            return false;
        }
    }
    
    public function get_project($id) {
    	$this->db->where('id', $id);
        $project = $this->db->get('projects');
        if ($project) {
            return $project->row();
        } else {
            return false;
        }
    }
    
}

?>