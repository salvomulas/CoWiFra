<?php

class model_artifacts extends CI_Model {
	function __construct() {
        parent::__construct();
    }
    
    /**
     * Add new artifact to database.
     * @author MP
     * @return Inserted ID (always > 0 -> a true value), if the Insert statement worked. False otherwise. 
     */
    public function add_artifact($name, $name_on_fs, $projects_id, $description = NULL) {
    	$this->load->model('model_history');
    	
        $data = array(
            'create_timestamp' => date('Y-m-d H:i:s'),
            'name' => $name,
            'path_on_fs' => $name_on_fs,
            
            //insert NULL to db if void
            'description' => $description ? $description : NULL,
            
            'owner' => $this->session->userdata('userid'),
            'projects_id' => $projects_id,
        );
        
        $query = $this->db->insert('artifacts', $data);
        if ($query) {
            $insert_id = $this->db->insert_id();
            
            $this->model_history->add_history_entry('model_artifacts.add_artifact', 'Neue Datei hochgeladen', $projects_id);
            return $insert_id;
        } else {
            return false;
        }
    }
    
    
    /**
     * Delete an artifact from the database and filesystem.
     * @author MP
     * @return True, if the deletion worked. False otherwise. 
     */
    public function delete_artifact($id) {
    	$this->load->model('model_history');
    	$this->load->model('model_projects');
    	
    	$artifact = $this->get_artifact($id);
    	if (!$artifact) {
            return false;
    	}
    	
    	$project = $this->model_projects->get_project($artifact->projects_id);
    	if (!$project) {
            return false;
    	}
    	
    	$unlink_returncode = unlink($this->config->item('artifacts_location') . '/' . $project->artifacts_directory . '/' . $artifact->path_on_fs);
    	if (!$unlink_returncode) {
            return false;
    	}
    	
        $this->db->where('id', $id);
        $query = $this->db->delete('artifacts');
        
        if ($query) {
            $this->model_history->add_history_entry('model_artifacts.delete_artifact', 'Datei gelöscht', $project->id);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Get an artifact's details from the database
     * @author MP
     * @return Returns the database row if successful, otherwise false.
     */    
    public function get_artifact($id) {
    	$this->db->where('id', $id);
        $artifact = $this->db->get('artifacts');
        if ($artifact) {
            return $artifact->row();
        } else {
            return false;
        }
    }
    
}

?>