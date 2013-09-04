<?php

class model_roles extends CI_Model {
    public function __construct() {
    	parent::__construct();
	}
    
    /**
     * Get role of currently logged in user in the specified project.
     * @author MP
     * @param $projects_id Project ID
     * @return role name
     */
    public function get_role_for_project_and_user($projects_id, $users_id) {
    	//Checking if the current user is project owner.
    	$this->db->where('owner', $users_id);
    	$this->db->where('id', $projects_id);
        $result = $this->db->get('projects');
        if ($result) {
            if ($result->num_rows() > 0) {
                return 'Besitzer';
            } else {
                //User is not owner.
                //Checking if the current user is project administrator.
                $this->db->where('users_id', $users_id);
                $this->db->where('projects_id', $projects_id);
                $this->db->select('projects_id, users_id, BIN(is_admin) as is_admin');
                $result2 = $this->db->get('projects_has_users');
                if ($result2) {
                    $row = $result2->row();
                    if (!$row) {
                        return 'Nicht berechtigt';
                    }
                    else if ($row->is_admin == 1) {
                        return 'Administrator';
                    }
                    else {
                        return 'Benutzer';
                    }
                }
                else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
    
    /**
     * Give a user the permission to work on a project.
     * @author MP
     * @param $projects_id Project ID
     * @param $users_id User ID
     * @param $is_admin 0 (no admin permission) or 1 (admin permission)
     * @return true if successful, false if not
     */
    public function add_user_to_project($projects_id, $users_id, $is_admin) {
    	if ($is_admin !== 0 and $is_admin !== 1) {
            return false;
    	}
    	
    	$data = array(
            'projects_id' => $projects_id,
            'users_id' => $users_id,
            'is_admin' => $is_admin,
        );
        
        $query = $this->db->insert('projects_has_users', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Remove a user's permission to work on a project.
     * @author MP
     * @param $projects_id Project ID
     * @param $users_id User ID
     * @return true if successful, false if not
     */
    public function remove_user_from_project($projects_id, $users_id) {
    	$this->db->where('projects_id', $projects_id);
    	$this->db->where('users_id', $users_id);
        $query = $this->db->delete('projects_has_users');
		
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Change admin permission for user.
     * @author MP
     * @param $projects_id Project ID
     * @param $users_id User ID
     * @param $is_admin 0 (no admin permission) or 1 (admin permission)
     * @return true if successful, false if not
     */
    public function change_admin_permission($projects_id, $users_id, $is_admin) {
    	if ($is_admin !== 0 and $is_admin !== 1) {
    		return false;
    	}
    	
    	$data = array(
            'projects_id' => $projects_id,
            'users_id' => $users_id,
            'is_admin' => $is_admin,
        );
        
        $this->db->where('projects_id', $projects_id);
    	$this->db->where('users_id', $users_id);
        $query = $this->db->update('projects_has_users', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Change project owner.
     * @author MP
     * @param $projects_id Project ID
     * @param $users_id User ID of new project owner.
     * @return true if successful, false if not
     */
    public function change_project_owner($projects_id, $users_id) {
    	$data = array(
            'owner' => $users_id,
        );
        
        $this->db->where('id', $projects_id);
        $query = $this->db->update('projects', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
    
}

?>