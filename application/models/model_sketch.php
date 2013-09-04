<?php

class model_sketch extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->model('model_history');
    }
    
    //
    // Methods for sketch details
    //
    
    /**
     * Creates a new sketch entry assigned to a project.
     * @author SR
     * @param String $name
     * @param String $description
     * @param int $owner User ID of the sketch owner
     * @param int $project_id ID of the project the sketch is assigned to
     * @return boolean Returns the ID of the new sketch if successfully stored in the database, otherwise returns false.
     */
    public function new_sketch($name, $description, $owner, $projects_id) {
        $data = array(
            'create_timestamp'  => date('Y-m-d H:i:s'),
            'name'              => $name,
            'description'       => $description,
            'owner'             => $owner,
            'projects_id'       => $projects_id,
        );
        
        $this->db->insert('sketches', $data);
        $insert_id = $this->db->insert_id();
        
        if ($this->db->affected_rows() === 1) {
        	$this->model_history->add_history_entry('model_sketch.new_sketch', 'Skizze "' . $name . '" erstellt', $projects_id);
            return $insert_id;
        } else {
            return false;
        }
        
    }
    
    /**
     * Gets the details of a sketch (name, description, creationtime, etc.)
     * @author SR
     * @param type $sketch_id
     * @return mixed Returns the data on success, returns 'false' on failure.
     */
    public function get_sketch_details($sketch_id) {
        $this->db->where('id', $sketch_id);
        $this->db->where('deleted', 0);
        $query = $this->db->get('sketches');
        
        if ($query->num_rows() === 1) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    /**
     * Gets the number of sketches for a specific project (non-deleted sketches)
     * @author MP
     * @param type $projects_id
     * @return type
     */
    public function get_sketch_count_for_project($projects_id) {
    $this->db->select('count(*) as count');
            $this->db->where('projects_id', $projects_id);
            $this->db->where('deleted', 0);
            $query_sketches_count = $this->db->get('sketches');

            $sketches_count = '';
            if ($query_sketches_count) {
                    $sketches_count_result = $query_sketches_count->row();
                    $sketches_count = $sketches_count_result->count;
            }
            return $sketches_count;
    }
    
    /**
     * Marks a sketch as being deleted.
     * @author SR
     * @param type $sketch_id
     * @return boolean True if successfully deleted, otherwise false.
     */
    public function delete_sketch($sketch_id){
        $sketch = $this->get_sketch_details($sketch_id);
        if (!$sketch) {
        	return false;
        }
        
        $data = array('deleted' => 1);
        
        $this->db->where('id', $sketch_id);
        $this->db->update('sketches', $data);
        
        $returncode = $this->db->affected_rows() === 1;
        
        if ($returncode) {
        	$this->model_history->add_history_entry('model_sketch.delete_sketch', 'Skizze "' . $sketch->name . '" gelöscht', $sketch->projects_id);
        }
        
        return $returncode;
    }
    
    
    //
    // Methods for sketch versions
    //
    
    /**
     * Add new version of a sketch to database. Data is read from POST params.
     * @author MP
     * @return True, if the Insert statement worked. False otherwise. 
     */
    public function add_version($sketch_id = NULL, $sketch_data = NULL, $description = NULL, $timestamp = NULL, $creator = NULL) {
        $sketch_id = ($sketch_id == NULL ? $this->input->post('sketch_id') : $sketch_id);
        $sketch_data = ($sketch_data == NULL ? $this->input->post('data') : $sketch_data);
        $description = ($description == NULL ? $this->input->post('comment') : $description);
        
        $data = array(
            'sketches_id'       => $sketch_id,
            'create_timestamp'  => ($timestamp == NULL ? date('Y-m-d H:i:s') : $timestamp),
            'creator'           => ($creator == NULL ? $this->session->userdata('userid') : $creator),
            'data'              => $sketch_data,
            'description'       => $description,
        );
        
        $query = $this->db->insert('sketch_archives', $data);
        
        $this->db->select('name, projects_id');
        $this->db->where('id', $sketch_id);
        $data_query = $this->db->get('sketches');
        $data_row = $data_query->row();
        
        if ($query) {
        	$query2 = $this->model_history->add_history_entry('model_sketch.add_version', 'Neue Version der Skizze "' . $data_row->name . '" erstellt', $data_row->projects_id);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Gets the ID of the latest sketch version
     * @author SR
     * @param int $sketch_id
     * @return mixed Returns the latest version's ID, or false on failure (if no versions available, or invalid sketch ID)
     */
    public function get_latest_version_id($sketch_id) {
        $this->db->select('MAX(id) as id');
        $this->db->where('sketches_id', $sketch_id);
    	$id_query = $this->db->get('sketch_archives');
        
        if ($id_query->num_rows() === 1) {
            return $id_query->row()->id;
        } else {
            return false;
        }
        
    }
    
 
    /**
     * @author SR
     * @param int $sketch_version_id ID of the sketch version
     * @return SqlRow Returns the details of a specific sketch version
     */
    public function get_sketch_version($sketch_version_id) {
        $this->db->where('id', $sketch_version_id);
        $query = $this->db->get('sketch_archives');

        if ($query->num_rows() === 1) {
            return $query->row();
        } else {
            return false;
        }
    }
    
    /**
     * Gets the number of sketch versions for a specific sketch.
     * @author SR
     * @param int $sketch_id
     * @return mixed Returns the number of sketch versions, or false on failure
     */
    public function get_sketch_version_count($sketch_id) {
        $this->db->select('COUNT(*) as count');
        $this->db->where('sketches_id', $sketch_id);
        $query = $this->db->get('sketch_archives');

        if ($query) {
            return $query->row()->count;
        } else {
            return false;
        }
    }
    

}

?>
