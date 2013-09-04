<?php

class model_history extends CI_Model {
	function __construct() {
        parent::__construct();
    }
    
    /**
     * Add new history entry to database.
     * @author MP
     * @return True, if the Insert statement worked. False otherwise. 
     */
    public function add_history_entry($php_function, $action_description, $projects_id) {
        $data = array(
            'action_timestamp' => date('Y-m-d H:i:s'),
            'php_function' => $php_function,
            'action_description' => $action_description,
            'projects_id' => $projects_id,
            'users_id' => $this->session->userdata('userid'),
        );
        
        $query = $this->db->insert('history', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
    
    public function get_last_project_activity_ts($projects_id) {
    	$this->db->select('max(action_timestamp) as timestamp');
        $this->db->where('projects_id', $projects_id);
        $query_history = $this->db->get('history');
        $timestamp = '';
        if ($query_history) {
            $max = $query_history->row();
            $timestamp = $max->timestamp;
        }

        return $timestamp;
    }
    
    public function get_recent_actions_for_project($projects_id, $number_of_actions = 10) {
    	$query_history = $this->db->query(
            'select action_timestamp, action_description, username, users_id AS userid ' .
            'from history inner join users on history.users_id = users.id ' .
            'where projects_id = ? ' .
            'order by action_timestamp desc ' .
            'limit ?',
			
            array($projects_id, $number_of_actions)
            );
    	
    	if ($query_history) {
    		return $query_history->result();
    	} else {
    		return false;
    	}
    }
    
}

?>