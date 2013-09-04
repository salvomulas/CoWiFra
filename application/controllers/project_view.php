<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class project_view extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('model_projects');
        $this->load->model('model_roles');
    }
    
    /**
     * Remaps the method to call when loading the index with args.
     * Example:
     *  /controller/2 Would normally try to load a method called "2"
     *  /controller/2 After remap, index is loaded with "2" as a parameter.
     * @author Colin Williams http://ellislab.com/forums/viewthread/135187/#668213
     * @param string $method
     */
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
	
    public function index($project_id) {
        if ($this->session->userdata('is_logged_in')) {
            $this->load->model('model_users');
            $this->load->model('model_history');
            $this->load->model('model_sketch');

            $role_current_user = $this->model_roles->get_role_for_project_and_user($project_id, $this->session->userdata('userid'));
            if ($role_current_user) {
                if ($role_current_user == 'Nicht berechtigt') {
                    $body_data['error_messages'][] = 'Sie sind nicht berechtigt fuer dieses Projekt.';
                } else {
                    
                    $project_data['role_current_user'] = $role_current_user;
                    $project_data['id_current_user'] = $this->session->userdata('userid');

                    $project = $this->model_projects->get_project($project_id);
                    if ($project) {
                        $project_data['project'] = $project;

                        $owner = $this->model_users->get_user($project->owner);
                        if ($owner) {
                            $project_data['owner'] = $owner;
                            $project_data['stats']['last_activity'] = $this->model_history->get_last_project_activity_ts($project_id);
                            $project_data['stats']['sketches_count'] = $this->model_sketch->get_sketch_count_for_project($project_id);

                            $query_users = $this->db->query(
                                'SELECT pu.users_id AS userid, u.username AS username, concat(u.firstname, " ", u.lastname) AS firstname_lastname, u.company AS company ' .
                                'FROM projects_has_users AS pu INNER JOIN users AS u ON pu.users_id = u.id ' .
                                'WHERE pu.projects_id = ? AND u.deleted = 0 ' .
                                'ORDER BY u.username',
                                array($project_id)
                            );

                            if ($query_users) {
                                $project_data['stats']['team_member_count'] = 0;

                                if ($query_users->num_rows() > 0) {
                                    foreach ($query_users->result() as $row) {
                                        $my_role = $this->model_roles->get_role_for_project_and_user($project_id, $row->userid);

                                        $project_data['stats']['team_member_count']++;

                                        $team_data[] = array(
                                            'id' => $row->userid,
                                            'username' => $row->username,
                                            'firstname_lastname' => $row->firstname_lastname,
                                            'role' => $my_role,
                                            'company' => $row->company,
                                        );
                                    }

                                    $project_data['team_data'] = $team_data;
                                } else {
                                    $project_data['team_data'] = array();
                                }

                                $query_sketches = $this->db->query(
                                    'SELECT s.id AS id, s.name AS sketch_name, s.description AS description, u.username AS username, u.id AS userid ' .
                                    'FROM sketches AS s INNER JOIN users AS u ON s.owner = u.id ' .
                                    'WHERE s.projects_id = ? AND s.deleted = 0 ' .
                                    'ORDER BY s.name',
                                    array($project_id)
                                );

                                if ($query_sketches) {

                                    if ($query_sketches->num_rows() > 0) {
                                        foreach ($query_sketches->result() as $row) {
                                            $latest_sketch_id = $this->model_sketch->get_latest_version_id($row->id);
                                            $latest_sketch_version = $this->model_sketch->get_sketch_version($latest_sketch_id);
                                            $sketches_data[] = array(
                                                'id' => $row->id,
                                                'username' => $row->username,
                                                'userid' => $row->userid,
                                                'sketch_name' => $row->sketch_name,
                                                'description' => $row->description,
                                                'latest_sketch_ts' => $latest_sketch_version ? $latest_sketch_version->create_timestamp : '',
                                            );
                                        }

                                        $project_data['sketches_data'] = $sketches_data;

                                    } else {
                                        $project_data['sketches_data'] = array();
                                    }

                                    $query_artifacts = $this->db->query(
                                            'SELECT a.id AS artifact_id, a.name AS artifact_name, a.description AS description, u.username AS owner, u.id AS owner_id, concat(p.artifacts_directory, "/", a.path_on_fs) AS link ' .
                                            'FROM artifacts AS a INNER JOIN users AS u ON a.owner = u.id INNER JOIN projects AS p ON a.projects_id = p.id ' .
                                            'WHERE a.projects_id = ? ' .
                                            'ORDER BY artifact_name ASC',
                                            array($project_id)
                                    );

                                    if ($query_artifacts) {
                                        if ($query_artifacts->num_rows() > 0) {
                                            foreach ($query_artifacts->result() as $row) {
                                                $artifacts_data[] = array(
                                                    'id' => $row->artifact_id,
                                                    'name' => $row->artifact_name,
                                                    'description' => $row->description,
                                                    'owner' => $row->owner,
                                                    'owner_id' => $row->owner_id,
                                                    'link' => base_url() . $this->config->item('artifacts_location') . '/' . $row->link,
                                                );
                                            }
                                            $project_data['artifacts_data'] = $artifacts_data;

                                        } else {
                                            $project_data['artifacts_data'] = array();
                                        }
                                    }
                                    else {
                                        $body_data['error_messages'][] = 'Konnte das Projekt nicht aus der Datenbank laden.';
                                    }

                                } else {
                                    $body_data['error_messages'][] = 'Konnte das Projekt nicht aus der Datenbank laden.';
                                }

                            } else {
                                $body_data['error_messages'][] = 'Konnte das Projekt nicht aus der Datenbank laden.';
                            }

                            $history = $this->model_history->get_recent_actions_for_project($project_id, 10);
                            if ($history) {
                                $project_data['history'] = $history;
                            } else {
                                $project_data['history'] = array();
                            }

                        } else {
                            $body_data['error_messages'][] = 'Konnte das Projekt nicht aus der Datenbank laden.';
                        }
                    } else {
                        $body_data['error_messages'][] = 'Konnte das Projekt nicht aus der Datenbank laden.';
                    }                    
                    
                }
            } else {
                $body_data['error_messages'][] = 'Konnte die Berechtigung nicht ueberpruefen.';
            }            

        	
            $meta_data['page_title'] = (isset($project) ? htmlspecialchars($project->name) : 'Projekt');
            $this->load->view('meta', $meta_data);
            $this->load->view('header');
            
            if (isset($body_data['error_messages'])) {
                
                $body_data['page_title'] = $meta_data['page_title'];
                $body_data['breadcrumbs'] = array(
                  array('name'  => 'Meine Projekte',
                        'url'   => '/dashboard')
                );
                
                $this->load->view('show_message', $body_data);
            } else {
                $this->load->view('project_view', $project_data);
            }
            
        } else {
            redirect('home/restricted');
        }
    }
    
    /**
     * Edit project information (requirements, description) and add or remove users
     * @author Michel Pluess
     */
    public function edit_project_info($project_id, $redirect_tab = NULL) {
    	if ($this->session->userdata('is_logged_in')) {
    		$role_current_user = $this->model_roles->get_role_for_project_and_user($project_id, $this->session->userdata('userid'));
            if ($role_current_user) {
                if ($role_current_user == 'Nicht berechtigt') {
                    $body_data['error_messages'][] = 'Sie sind nicht berechtigt f&uuml;r dieses Projekt.';                    
                } else {
                    
                    if ($redirect_tab == NULL) {
                        $redirect_tab = 'lA';
                    }

                    $project = $this->model_projects->get_project($project_id);
                    if (!$project) {
                        $body_data['error_messages'][] = 'Konnte das Projekt nicht aus der Datenbank laden.';
                    } else {

                        $project_data['project'] = $project;
                        $project_data['user_role'] = $role_current_user;

                        //get users to display as current users
                        //all users authorized for the project except the owner and the currently logged in user.
                        $query_users = $this->db->query(
                            'SELECT pu.users_id AS id, u.username AS username ' .
                            'FROM projects_has_users AS pu INNER JOIN users AS u ON pu.users_id = u.id ' .
                            'WHERE pu.projects_id = ? AND u.deleted = 0 AND pu.users_id NOT IN ( ' .
                            'SELECT owner FROM projects WHERE id = ? ) AND pu.users_id <> ?',
                            array($project_id, $project_id, $this->session->userdata('userid'))
                        );

                        if ($query_users) {
                            if ($query_users->num_rows() > 0) {
                                //TODO MP 05.08.2013: take role names / ids from a central config file
                                foreach ($query_users->result() as $row) {
                                    $my_role = $this->model_roles->get_role_for_project_and_user($project_id, $row->id);

                                    $team_data[] = array(
                                        'id' => $row->id,
                                        'username' => $row->username,
                                        'role' => $my_role,
                                    );
                                }

                                $project_data['team_data'] = $team_data;
                            } else {
                                $project_data['team_data'] = array();
                            }
                        } else {
                            $body_data['error_messages'][] = 'Konnte die berechtigten User nicht aus der Datenbank laden.';
                        }

                        //get users to display as users possible to add
                        //all users except the ones already authorized for the project
                        $query_other_users = $this->db->query(
                            'SELECT id, username FROM users WHERE deleted = 0 AND id NOT IN ( ' .
                                'SELECT pu.users_id ' .
                                'FROM projects_has_users AS pu INNER JOIN users AS u ON pu.users_id = u.id ' .
                                'WHERE pu.projects_id = ? AND u.deleted = 0 ) ' .
                                    'ORDER BY username',
                            array($project_id)
                        );

                        if ($query_other_users) {
                            if ($query_other_users->num_rows() > 0) {
                                //TODO MP 05.08.2013: take role names / ids from a central config file
                                foreach ($query_other_users->result() as $row) {
                                    $other_users[] = array(
                                        'id'        => $row->id,
                                        'username'  => $row->username,
                                    );
                                }

                                $project_data['other_users'] = $other_users;
                            } else {
                                $project_data['other_users'] = array();
                            }
                        } else {
                            $body_data['error_messages'][] = 'Konnte die User nicht aus der Datenbank laden.';
                        }
                    }
                }
            } else {
                $body_data['error_messages'][] = 'Konnte die Berechtigung nicht ueberpruefen.';
            }

            $meta_data['page_title'] = (isset($project) ? htmlspecialchars($project->name) : 'Projekt') . ' | Projektinformationen bearbeiten';
            $meta_data['skip_custom_js'] = 1;
            
            $project_data['redirect_tab'] = $redirect_tab;
            
            $this->load->view('meta', $meta_data);
            $this->load->view('header');
            
            if (isset($body_data['error_messages'])) {
                $body_data['page_title'] = $meta_data['page_title'];
                $body_data['breadcrumbs'] = array(
                    array('name'  => 'Meine Projekte',
                          'url'   => '/dashboard')
                );
                $this->load->view('show_message', $body_data);
            } else {
                $this->load->view('edit_project_info', $project_data);
            }
        } else {
            redirect('home/restricted');
        }
    }
    
    public function edit_project_info_validation() {
    	if ($this->session->userdata('is_logged_in')) {
            $this->load->model('model_history');
            
            $project_id = $this->input->post('project_id');
            $user_role = $this->model_roles->get_role_for_project_and_user($project_id, $this->session->userdata('userid'));
            
            //only do the form validation if the user is project owner (for the required project name)
            if ($user_role == 'Besitzer') {
                $this->form_validation->set_rules('project_name', 'Projektname', 'required');
                $form_validation_result = $this->form_validation->run();
            } else {
            	$form_validation_result = 1;
            }

            if ($form_validation_result) {
                $data = array(
                    'general_requirements' => $this->input->post('project_general_requirements') ? $this->input->post('project_general_requirements') : NULL,
                );
                if ($user_role == 'Besitzer') {
                    $data['description'] = $this->input->post('project_description') ? $this->input->post('project_description') : NULL;
                    $data['name'] = $this->input->post('project_name');
                }

                $this->db->where('id', $project_id);
                $query = $this->db->update('projects', $data);

                if ($query) {
                    if ($user_role == 'Besitzer' or $user_role == 'Administrator') {
                        //delete all authorizations except the one of the owner and the currently logged in user
                        $result = $this->db->query(
                            'delete from projects_has_users where projects_id = ? and users_id not in (select owner from projects where id = ?) ' .
                            'and users_id <> ?',
                            array($project_id, $project_id, $this->session->userdata('userid'))
                        );
                        
                        if (!$result) {
                            $body_data['error_messages'][] = 'Fehler beim L&ouml;schen der alten Berechtigungen.';
                        } else {
                            // Authorize users for the project
                            $userdata = $this->input->post('userdata');
                            $user_array = preg_split('/;/', $userdata, NULL, PREG_SPLIT_NO_EMPTY);
                            $state = 1;
                            foreach ($user_array as $user_string) {
                                $user_data = preg_split('/,/', $user_string, NULL, PREG_SPLIT_NO_EMPTY);
                                $user_id = $user_data[0];
                                $is_admin = $user_data[1];
                                if ($user_id and ($is_admin == 'false' or $is_admin == 'true')) {
                                        $return_state = $this->model_roles->add_user_to_project($project_id, $user_id, ($is_admin == 'false' ? 0 : 1));

                                        if (!$return_state) {
                                            $state = 0;
                                        }
                                } else {
                                    $body_data['error_messages'][] = 'Fehler beim Berechtigen der User f&uuml;r das Projekt.';
                                }
                            }

                            if ($state != 1) {
                                $body_data['error_messages'][] = 'Fehler beim Berechtigen der User f&uuml;r das Projekt.';
                            }
                        }
                    }
                    
                    $body_data['success_messages'][] = 'Die &Auml;nderungen am Projekt wurden erfolgreich gespeichert.';
                    $this->model_history->add_history_entry('project_view.edit_project_info_validation', 'Projektinfos bearbeitet', $project_id);
                    
                } else {
                    $body_data['error_messages'][] = 'Fehler beim Bearbeiten des Projekts.';
                }
                
                $redirect_tab = $this->input->post('redirect_tab');
                if (!$redirect_tab) {
                	$redirect_tab = 'lA';
                }
                
                // Show success/error message
                header('refresh:3;url=/project_view/' . $project_id . '/#tab_' . $redirect_tab);

                $meta_data['page_title'] = (isset($project_id) ? htmlspecialchars($this->model_projects->get_project($project_id)->name) : 'Projekt') . ' | Bearbeiten';
                
                $body_data['page_title'] = $meta_data['page_title'];
                $body_data['breadcrumbs'] = array(
                    array('name'  => 'Meine Projekte',
                          'url'   => '/dashboard'),
                    array('name'  => $this->input->post('project_name'),
                          'url'   => '/project_view/' . $project_id)
                );

                $this->load->view("meta", $meta_data);
                $this->load->view("header");
                $this->load->view('show_message', $body_data);
                    
            } else {
                $this->edit_project_info($project_id);
            }
        } else {
            redirect('home/restricted');
        }
    }
    
    public function change_owner() {
    	if ($this->session->userdata('is_logged_in')) {
            $project_id = $this->input->post('project_id');
            
            if ($project_id) {
                $user_role = $this->model_roles->get_role_for_project_and_user($project_id, $this->session->userdata('userid'));
            
                //only allowed if the user is project owner
                if ($user_role == 'Besitzer') {
                    if ( $this->model_roles->change_project_owner($project_id, $this->input->post('owner')) ) {
                        header('refresh:3;url=/project_view/' . $project_id);
                        $body_data['success_messages'][] = 'Projektbesitzer erfolgreich ge&auml;ndert.';
                    } else {
                        $body_data['error_messages'][] = '&Auml;ndern des Projektbesitzers fehlgeschlagen.';
                    }
                } else {
                    $body_data['error_messages'][] = 'Sie sind nicht berechtigt, den Projektbesitzer zu &auml;ndern. Dies ist dem aktuellen Projektbesitzer vorbehalten.';
                }
            } else {
                $body_data['error_messages'][] = 'Es wurde keine Projekt-ID angegeben.';
            }
            
            $meta_data['page_title'] = (isset($project_id)? htmlspecialchars($this->model_projects->get_project($project_id)->name) : 'Projekt') . ' | Projektbesitzer &auml;ndern';

            $body_data['page_title'] = $meta_data['page_title'];
            $body_data['breadcrumbs'] = array(
                array('name'  => 'Meine Projekte',
                      'url'   => '/dashboard'),
                array('name'  => ($project_id) ? $this->model_projects->get_project($project_id)->name : 'Projekt',
                      'url'   => ($project_id) ? '/project_view/' . $project_id : '')
            );
            
            $this->load->view("meta", $meta_data);
            $this->load->view("header");
            $this->load->view('show_message', $body_data);
                
    	} else {
            redirect('home/restricted');
        }
    }
    
    public function delete_project($project_id) {
    	if ($this->session->userdata('is_logged_in')) {
            $user_role = $this->model_roles->get_role_for_project_and_user($project_id, $this->session->userdata('userid'));
            
            //only allowed if the user is project owner
            if ($user_role == 'Besitzer') {
                if ( $this->model_projects->delete_project($project_id) ) {
                    header('refresh:3;url=/dashboard');
                    $body_data['success_messages'][] = 'Das Projekt wurde erfolgreich gel&ouml;scht.';
                } else {
                    $body_data['error_messages'][] = 'L&ouml;schen des Projekts fehlgeschlagen.';
                }
            } else {
            	$body_data['error_messages'][] = 'Sie sind nicht berechtigt, das Projekt zu l&ouml;schen. Dies ist dem Projektbesitzer vorbehalten.';
            }
            
            $meta_data['page_title'] = htmlspecialchars($this->model_projects->get_project($project_id)->name) . ' | L&ouml;schen';

            $body_data['page_title'] = $meta_data['page_title'];
            $body_data['breadcrumbs'] = array(
                array('name'  => 'Meine Projekte',
                      'url'   => '/dashboard'),
                array('name'  => $this->model_projects->get_project($project_id)->name,
                      'url'   => '/project_view/' . $project_id)
            );
            
            $this->load->view("meta", $meta_data);
            $this->load->view("header");
            $this->load->view('show_message', $body_data);
                
    	} else {
            redirect('home/restricted');
        }
    }
    
    /**
     * Adds a new sketch to the project
     * @author SR
     */
    public function new_sketch($project_id) {
        $this->load->model('model_sketch');
        
        $this->form_validation->set_rules('sketch_name', 'Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('description', 'Beschreibung', 'required|trim|xss_clean');
            
        if ($this->form_validation->run()) {
            $sketch_name = $this->input->post('sketch_name');
            $sketch_desc = $this->input->post('description');            
            
            // Save sketch to DB
            $sketch_added_ID = $this->model_sketch->new_sketch(
                    $sketch_name,
                    $sketch_desc,
                    $this->session->userdata('userid'),
                    $project_id);
            
            if ($sketch_added_ID) {
                $body_data['project'] = $this->model_projects->get_project($project_id);
                $body_data['success_messages'][] =
                        'Die Skizze wurde erfolgreich angelegt. Sie werden automatisch zum Skizzeneditor weitergeleitet...<br />' .
                        '<br /><b>Name: </b>' .
                        '<small>' . htmlspecialchars($sketch_name) . '</small>' .
                        '<br /><b>Beschreibung: </b>' .
                        '<small>' . nl2br(htmlspecialchars($sketch_desc)) . '</small>';

                // Redirect to designer to edit the new sketch
                header('refresh:3;url=/designer?sketch_id=' . $sketch_added_ID);
            } else {
                $body_data['error_messages'][] = 'Fehler beim Erstellen der Skizze. Bitte versuchen Sie es nochmals.';
                header('refresh:3;url=/project_view/new_sketch/' . $project_id);
            }
            
            $meta_data['page_title'] = htmlspecialchars($this->model_projects->get_project($project_id)->name) . ' | Neue Skizze erstellen';

            $body_data['page_title'] = $meta_data['page_title'];
            $body_data['breadcrumbs'] = array(
                array('name'  => 'Meine Projekte',
                      'url'   => '/dashboard'),
                array('name'  => $this->model_projects->get_project($project_id)->name,
                      'url'   => '/project_view/' . $project_id)
            );
            
            $this->load->view('meta', $meta_data);
            $this->load->view('header');
            $this->load->view('show_message', $body_data);

            
        } else {
            // Form validation failed, show project page again
            // The sketches tab is selected & the modal dialog is displayed again from the view (javascript)
            $this->index($project_id);
        }
    }
    
    public function duplicate_sketch($sketch_id, $version_id = NULL) {
    	if ($this->session->userdata('is_logged_in')) {
            $this->load->model('model_sketch');
    		
            $sketch = $this->model_sketch->get_sketch_details($sketch_id);
            if (!$sketch) {
            	$body_data['error_messages'][] = 'Duplizieren fehlgeschlagen. Konnte die ben&ouml;tigten Informationen nicht aus der Datenbank lesen.';
            } else {
                $role_current_user = $this->model_roles->get_role_for_project_and_user($sketch->projects_id, $this->session->userdata('userid'));
                if ($role_current_user) {
                    if ($role_current_user == 'Nicht berechtigt') {
                        $body_data['error_messages'][] = 'Sie sind nicht berechtigt f&uuml;r dieses Projekt.';
                    }
                } else {
                    $body_data['error_messages'][] = 'Konnte die Berechtigung nicht &uuml;berpruefen.';
                }

                $project = $this->model_projects->get_project($sketch->projects_id);
                if (!$project) {
                    $body_data['error_messages'][] = 'Duplizieren fehlgeschlagen. Konnte die ben&ouml;tigten Informationen nicht aus der Datenbank lesen.';
                } else {            
                    $data['sketch'] = $sketch;
                    $data['project'] = $project;
                    $data['version_id'] = $version_id;
                }
            }
            
            $meta_data['page_title'] = (isset($project) ? $project->name : 'Projekt') . ' | ' . (isset($sketch) ? htmlspecialchars($sketch->name) : 'Skizze') . ' | Duplizieren';

            $this->load->view('meta', $meta_data);
            $this->load->view('header');
            
            if (isset($body_data['error_messages'])) {
                $body_data['page_title'] = $meta_data['page_title'];
                $body_data['breadcrumbs'] = array(
                    array('name'  => 'Meine Projekte',
                          'url'   => '/dashboard'),
                    array('name'  => (isset($project)) ? $project->name : 'Projekt',
                          'url'   => (isset($project)) ? '/project_view/' . $project->id : '')
                );
                $this->load->view('show_message', $body_data);
            } else {
                $this->load->view('duplicate_sketch', $data);
            }
            
    	} else {
            redirect('home/restricted');
        }
    }
    
    //TODO MP 11.08.2013: form repopulation: if field is empty, gets the value from the old sketch in the database. fix it or leave it this way?
    public function duplicate_sketch_validation($sketch_id) {
    	if ($this->session->userdata('is_logged_in')) {
    		$this->form_validation->set_rules('sketch_name', 'Name', 'required|trim|xss_clean');
        	$this->form_validation->set_rules('description', 'Beschreibung', 'required|trim|xss_clean');
    		
    		if ($this->form_validation->run()) {
                    $this->load->model('model_sketch');
	    		
	            $sketch = $this->model_sketch->get_sketch_details($sketch_id);
	            if (!$sketch) {
	            	$body_data['error_messages'][] = 'Duplizieren fehlgeschlagen. Konnte die ben&ouml;tigten Informationen nicht aus der Datenbank lesen (Skizze).';
	            } else {
                        $insert_id = $this->model_sketch->new_sketch(
                            $this->input->post('sketch_name'),
                            $this->input->post('description'),
                            $this->session->userdata('userid'),
                            $sketch->projects_id
                        );

                        if (!$insert_id) {
                            $body_data['error_messages'][] = 'Duplizieren fehlgeschlagen. Konnte nicht in die Datenbank schreiben (Skizze).';
                        }

                        //copy the latest sketch version if available
                        if ($this->input->post('version_id')) {
                            $latest_version_id = $this->input->post('version_id');
                        } else {
                            $latest_version_id = $this->model_sketch->get_latest_version_id($sketch_id);
                        }

                        if ($latest_version_id) {
                            $sketch_version = $this->model_sketch->get_sketch_version($latest_version_id);
                            if ($sketch_version) {
                                if ( ! $this->model_sketch->add_version($insert_id, $sketch_version->data, $sketch_version->description, $sketch_version->create_timestamp, $sketch_version->creator) ) {
                                    $body_data['error_messages'][] = 'Duplizieren fehlgeschlagen. Konnte nicht in die Datenbank schreiben (Version).';
                                }
                            } else {
                                $body_data['error_messages'][] = 'Duplizieren fehlgeschlagen. Konnte die ben&ouml;tigten Informationen nicht aus der Datenbank lesen (Version).';
                            }
                        }
                    }
	            
                    header('refresh:3;url=/project_view/' . $sketch->projects_id . '/#tab_lB');
                    
	            if (!isset($body_data['error_messages'])) {
                        $body_data['success_messages'][] = 'Skizze erfolgreich dupliziert.';
                    }
                    
                    $meta_data['page_title'] = (isset($sketch) ? htmlspecialchars($this->model_projects->get_project($sketch->projects_id)->name) : 'Projekt') . ' | ' . (isset($sketch) ? htmlspecialchars($sketch->name) : 'Skizze') . ' | Duplizieren';

                    $body_data['page_title'] = $meta_data['page_title'];
                    $body_data['breadcrumbs'] = array(
                        array('name'  => 'Meine Projekte',
                              'url'   => '/dashboard'),
                        array('name'  => (isset($sketch)) ? $this->model_projects->get_project($sketch->projects_id)->name : "Projekt",
                              'url'   => (isset($sketch)) ? '/project_view/' . $sketch->projects_id : "")
                    );

                    $this->load->view('meta', $meta_data);
                    $this->load->view('header');
                    $this->load->view('show_message', $body_data);
                    
    		} else {
                    $this->duplicate_sketch($sketch_id);
    		}
    	} else {
            redirect('home/restricted');
        }
    }
    
    public function delete_sketch($sketch_id) {
    	if ($this->session->userdata('is_logged_in')) {
    		$this->load->model('model_sketch');
    		
            $sketch = $this->model_sketch->get_sketch_details($sketch_id);
            if (!$sketch) {
            	$body_data['error_messages'][] = 'L&ouml;schen fehlgeschlagen. Konnte die ben&ouml;tigten Informationen nicht aus der Datenbank lesen.';
            } else {
            	$role_current_user = $this->model_roles->get_role_for_project_and_user($sketch->projects_id, $this->session->userdata('userid'));
                if ($this->session->userdata('userid') == $sketch->owner or $role_current_user == 'Besitzer' or $role_current_user == 'Administrator') {
                    if ($this->model_sketch->delete_sketch($sketch_id)) {
                        header('refresh:3;url=/project_view/' . $sketch->projects_id . '/#tab_lB');
                        $body_data['success_messages'][] = 'Skizze erfolgreich gel&ouml;scht.';
                    } else {
                        $body_data['error_messages'][] = 'Fehler beim L&ouml;schen der Skizze.';
                    }
                } else {
                    $body_data['error_messages'][] = 'Skizze darf nur vom Autor oder von einem Administrator gel&ouml;scht werden!';
                }
            }
            
            $meta_data['page_title'] = (isset($sketch) ? htmlspecialchars($this->model_projects->get_project($sketch->projects_id)->name) : 'Projekt') . ' | ' . (isset($sketch) ? htmlspecialchars($sketch->name) : 'Skizze') . ' | L&ouml;schen';

            $body_data['page_title'] = $meta_data['page_title'];
            $body_data['breadcrumbs'] = array(
                array('name'  => 'Meine Projekte',
                      'url'   => '/dashboard'),
                array('name'  => (isset($sketch)) ? $this->model_projects->get_project($sketch->projects_id)->name : "Projekt",
                      'url'   => (isset($sketch)) ? '/project_view/' . $sketch->projects_id : "")
            );

            $this->load->view('meta', $meta_data);
            $this->load->view('header');
            $this->load->view('show_message', $body_data);
                    
        } else {
            redirect('home/restricted');
        }
    }

    public function sketch_versions($sketch_id) {
        if ($this->session->userdata('is_logged_in')) {
            $this->load->model('model_users');
            $this->load->model('model_history');
            $this->load->model('model_sketch');

            $sketch = $this->model_sketch->get_sketch_details($sketch_id);
            if (!$sketch) {
            	echo 'Konnte die ben&ouml;tigten Informationen nicht aus der Datenbank lesen (Skizze).';
                return;
            } else {
                $project_id = $sketch->projects_id;
                $sketches_data['sketch'] = $sketch;

                $role_current_user = $this->model_roles->get_role_for_project_and_user($project_id, $this->session->userdata('userid'));
                if ($role_current_user) {
                    if ($role_current_user == 'Nicht berechtigt') {
                        echo 'Sie sind nicht berechtigt fuer dieses Projekt.';
                        return;
                    }
                } else {
                    echo 'Konnte die Berechtigung nicht ueberpruefen.';
                    return;
                }

                $sketches_data['role_current_user'] = $role_current_user;
                $sketches_data['id_current_user'] = $this->session->userdata('userid');

                $project = $this->model_projects->get_project($project_id);
                if ($project) {
                    $sketches_data['project'] = $project;

                    $owner = $this->model_users->get_user($project->owner);
                    if ($owner) {
                        $sketches_data['owner'] = $owner;
                        $sketches_data['stats']['last_activity'] = $this->model_history->get_last_project_activity_ts($project_id);
                        $sketches_data['stats']['sketches_count'] = $this->model_sketch->get_sketch_count_for_project($project_id);

                        $query_users = $this->db->query(
                            'SELECT pu.users_id AS userid ' .
                            'FROM projects_has_users AS pu INNER JOIN users AS u ON pu.users_id = u.id ' .
                            'where pu.projects_id = ? AND u.deleted = 0',
                            array($project_id)
                        );

                        if ($query_users) {
                            $sketches_data['stats']['team_member_count'] = 0;

                            if ($query_users->num_rows() > 0) {
                                //TODO MP 29.07.2013: sort order
                                foreach ($query_users->result() as $row) {
                                    $sketches_data['stats']['team_member_count']++;
                                }
                            }

                        } else {
                            echo 'Konnte die Daten nicht aus der Datenbank laden (Benutzer Statistiken).';
                            return;
                        }

                        $history = $this->model_history->get_recent_actions_for_project($project_id, 10);
                        if ($history) {
                            $sketches_data['history'] = $history;
                        } else {
                            $sketches_data['history'] = array();
                        }

                    } else {
                        echo 'Konnte die Daten nicht aus der Datenbank laden (Besitzer).';
                        return;
                    }
                } else {
                    echo 'Konnte die Daten nicht aus der Datenbank laden (Projekt).';
                    return;
                }

                $query_versions = $this->db->query(
                    'SELECT s.id AS id, s.create_timestamp AS create_timestamp, s.description AS description, u.id AS creator_id, u.username AS creator ' .
                    'FROM sketch_archives AS s INNER JOIN users AS u ON s.creator = u.id ' .
                    'WHERE s.sketches_id = ? ' .
                    'ORDER BY s.create_timestamp DESC',
                    array($sketch_id)
                );
                if (!$query_versions) {
                    echo 'Konnte die Daten nicht aus der Datenbank laden (Versionen).';
                    return;
                }

                $versions = array();
                if ($query_versions->num_rows() > 0) {
                    foreach ($query_versions->result() as $row) {
                        $versions[] = array(
                            'id' => $row->id,
                            'create_timestamp' => $row->create_timestamp,
                            'description' => $row->description,
                            'creator' => $row->creator,
                            'creator_id' => $row->creator_id
                        );
                    }
                }
                $sketches_data{'versions'} = $versions;
            }
        	
            $meta_data['page_title'] = (isset($project) ? htmlspecialchars($project->name) : 'Projekt') . ' | ' . (isset($sketch) ? htmlspecialchars($sketch->name) : 'Skizze') . ' | Versionen';

            $this->load->view("meta", $meta_data);
            $this->load->view("header");
            $this->load->view('sketch_versions', $sketches_data);
        } else {
            redirect('home/restricted');
        }
    }

    public function upload_artifact_view($project_id, $error = NULL) {
        if ($this->session->userdata('is_logged_in')) {
        	$role_current_user = $this->model_roles->get_role_for_project_and_user($project_id, $this->session->userdata('userid'));
        	if ($role_current_user) {
                    if ($role_current_user == 'Nicht berechtigt') {
                        echo 'Sie sind nicht berechtigt fuer dieses Projekt.';
                        return;
                    }
        	} else {
                    echo 'Konnte die Berechtigung nicht ueberpruefen.';
                    return;
        	}
			
                $data['page_title'] = (isset($project_id) ? htmlspecialchars($this->model_projects->get_project($project_id)->name) : 'Projekt') . ' | Rohdaten hochladen';
	        $this->load->view("meta", $data);
	        $this->load->view("header");
	        
	        $project = $this->model_projects->get_project($project_id);
	        if (!$project) {
                    echo "Konnte die notwendigen Daten nicht aus der Datenbank lesen.";
                    return;
	        }
	        
                $artifact_data = array(
                    'error' => $error ? $error : ' ',
                    'project' => $project,
                );
                $this->load->view('form_upload_artifact', $artifact_data);
            } else {
            redirect('home/restricted');
        }
    }

    public function do_upload_artifact($project_id) {
        if ($this->session->userdata('is_logged_in')) {
            $this->load->model('model_artifacts');

            $role_current_user = $this->model_roles->get_role_for_project_and_user($project_id, $this->session->userdata('userid'));
            if ($role_current_user) {
                if ($role_current_user == 'Nicht berechtigt') {
                        echo 'Sie sind nicht berechtigt fuer dieses Projekt.';
                        return;
                }
            } else {
                echo 'Konnte die Berechtigung nicht ueberpruefen.';
                return;
            }

            $project = $this->model_projects->get_project($project_id);
            if (!$project) {
                echo "Hochladen fehlgeschlagen. Konnte die notwendigen Daten nicht aus der Datenbank lesen.";
                return;
            }

            $config['upload_path'] = $this->config->item('artifacts_location') . '/' . $project->artifacts_directory;
            $config['allowed_types'] = 'doc|docx|xls|xlsx|pdf';

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload()) {
                $this->upload_artifact_view($project_id, $this->upload->display_errors());
            } else {
                $upload_data = $this->upload->data();

                $returncode = $this->model_artifacts->add_artifact(
                    $upload_data['client_name'],
                    $upload_data['file_name'],
                    $project_id,
                    $this->input->post('description')
                );

                if ($returncode) {
                    $this->upload_artifact_success($project_id, $upload_data['client_name']);
                } else {
                    $unlink_returncode = unlink($this->config->item('artifacts_location') . '/' . $project->artifacts_directory . '/' . $upload_data['file_name']);

                    if ($unlink_returncode) {
                        echo "Hochladen fehlgeschlagen. Konnte die notwendigen Daten nicht in die Datenbank schreiben.";
                    } else {
                        echo "Hochladen fehlgeschlagen. Konnte die notwendigen Daten nicht in die Datenbank schreiben. Konnte die Datei auf dem Server nicht wieder löschen!";
                    }
                    return;
                }
            }
        } else {
            redirect('home/restricted');
        }
    }

    public function upload_artifact_success($project_id, $filename) {
        if ($this->session->userdata('is_logged_in')) {
            $role_current_user = $this->model_roles->get_role_for_project_and_user($project_id, $this->session->userdata('userid'));
            if ($role_current_user) {
                if ($role_current_user == 'Nicht berechtigt') {
                    echo 'Sie sind nicht berechtigt fuer dieses Projekt.';
                    return;
                }
            } else {
                echo 'Konnte die Berechtigung nicht ueberpruefen.';
                return;
            }

            $project = $this->model_projects->get_project($project_id);
            if (!$project) {
                echo "Hochladen erfolgreich. Konnte die notwendigen Daten zur Darstellung der Seite allerdings nicht aus der Datenbank lesen.";
                return;
            }

            $meta_data['page_title'] = (isset($project) ? htmlspecialchars($project->name) : 'Projekt') .  ' | Rohdaten hochladen';
            
            $upload_data['project'] = $project;
            $upload_data['filename'] = $filename;

            $this->load->view("meta", $meta_data);
            $this->load->view("header");
            $this->load->view('form_upload_artifact_success', $upload_data);
            
        } else {
            redirect('home/restricted');
        }
    }
	
    public function delete_artifact($artifact_id) {
        if ($this->session->userdata('is_logged_in')) {
            $this->load->model('model_artifacts');

            $artifact = $this->model_artifacts->get_artifact($artifact_id);
            if (!$artifact) {
                echo 'L&ouml;schen fehlgeschlagen. Konnte die ben&ouml;tigten Informationen nicht aus der Datenbank lesen.';
            return;
            }

            $role_current_user = $this->model_roles->get_role_for_project_and_user($artifact->projects_id, $this->session->userdata('userid'));
	        if ($role_current_user) {
	            if ($role_current_user == 'Nicht berechtigt') {
	                echo 'Sie sind nicht berechtigt f&uuml;r dieses Projekt.';
	                return;
	            }
	        } else {
	            echo 'Konnte die Berechtigung nicht &uuml;berpr&uuml;fen.';
	            return;
	        }
	
	        $delete_returncode = $this->model_artifacts->delete_artifact($artifact_id);
	        if ($delete_returncode) {
	            header('refresh:2;url=/project_view/' . $artifact->projects_id . '/#tab_lC');
	            echo 'Datei erfolgreich gel&ouml;scht.';
	        } else {
	            echo 'L&ouml;schen fehlgeschlagen.';
	            return;
	        }

        } else {
            redirect('home/restricted');
        }
    }
}

?>