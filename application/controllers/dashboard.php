<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class dashboard extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
	
	public function index() {
            if ($this->session->userdata('is_logged_in')) {
        	$this->load->model('model_roles');
        	$this->load->model('model_users');
        	$this->load->model('model_history');
        	$this->load->model('model_sketch');
        	
        	$userid = $this->session->userdata('userid');
        	$user = $this->model_users->get_user($userid);
        	
        	$query_projects = $this->db->query(
        		'SELECT projects_id, is_admin, owner, p.name AS project_name, u.username AS owner_name ' .
        		'FROM projects_has_users AS pu INNER JOIN projects AS p ON pu.projects_id = p.id INNER JOIN users AS u ON p.owner = u.id ' .
        		'WHERE pu.users_id = ? AND p.deleted = 0',
        		array($userid)
        	);
        	
        	$project_metadata = array(
	            'my_projects_count'     => 0,
	            'other_projects_count'  => 0,
	            'my_sketches_count'     => 0,
	            'other_sketches_count'  => 0,
	        );
        	
        	if ($query_projects) {
                    if ($query_projects->num_rows() > 0) {
                        foreach ($query_projects->result() as $row) {
                            $projects_id = $row->projects_id;
                            $timestamp = $this->model_history->get_last_project_activity_ts($projects_id);

                            $sketches_count = $this->model_sketch->get_sketch_count_for_project($projects_id);

                            //TODO MP 29.07.2013: Hold role names on a central place instead of hardcoding them.
                            $my_role = $this->model_roles->get_role_for_project_and_user($projects_id, $userid);
                            if ($my_role == 'Besitzer') {
                                $project_metadata['my_projects_count']++;
                            }
                            else {
                                $project_metadata['other_projects_count']++;
                            }
							
                            $role_sort_weight = 0;
                            if ($my_role == 'Besitzer') {
                                    $role_sort_weight = 100;
                            }
                            else if ($my_role == 'Administrator') {
                                    $role_sort_weight = 90;
                            }
                            else if ($my_role == 'Benutzer') {
                                    $role_sort_weight = 80;
                            }
							
                            $project_data[] = array(
                                'project_name'          => $row->project_name,
                                'owner_name'            => $row->owner_name,
                                'last_activity'         => $timestamp,
                                'role'                  => $my_role,
                                'role_sort_weight'		=> $role_sort_weight,
                                'number_of_sketches'    => $sketches_count,
                                'id'                    => $projects_id,
                            );
                        }
						
                        foreach ($project_data as $key => $row) {
                            $roles[$key] = $row['role_sort_weight'];
                            $names[$key] = strtolower($row['project_name']);
                        }
						
                        array_multisort($roles, SORT_DESC, $names, SORT_ASC, $project_data);
                        $data_dash['project_data'] = $project_data;
                    } else {
                        $data_dash['project_data'] = array();
                    }

                    //get count of sketches (not sketch_archives) owned by the current user
                    $this->db->select('count(*) as count');
                    $this->db->where('owner', $userid);
                    $this->db->where('deleted', 0);
                    $query_my_sketches_count = $this->db->get('sketches');
                    if ($query_my_sketches_count) {
                        $my_sketches_count_result = $query_my_sketches_count->row();
                        $my_sketches_count = $my_sketches_count_result->count;
                        $project_metadata['my_sketches_count'] = $my_sketches_count;
                    } else {
                        $project_metadata['my_sketches_count'] = '';
                    }

                    //get count of sketches (not sketch_archives) NOT owned by the current user, where the current user has created at least one version.
                    //select distinct(sa.sketches_id) from sketch_archives as sa inner join sketches as s on sa.sketches_id = s.id where sa.creator = 1 and s.owner <> 1
                    $query_other_sketches_count = $this->db->query(
                        'SELECT count(DISTINCT(sa.sketches_id)) AS count ' .
                        'FROM sketch_archives AS sa INNER JOIN sketches AS s ON sa.sketches_id = s.id ' .
                        'WHERE sa.creator = ? AND s.owner <> ? AND s.deleted = 0',
                        array($userid,$userid)
                    );

                    if ($query_other_sketches_count) {
                        $other_sketches_count_result = $query_other_sketches_count->row();
                        $other_sketches_count = $other_sketches_count_result->count;
                        $project_metadata['other_sketches_count'] = $other_sketches_count;
                    } else {
                        $project_metadata['other_sketches_count'] = '';
                    }

                    $data_dash['user_data'] = $user;
                    $data_dash['project_metadata'] = $project_metadata;
                } else {
                    $body_data['error_messages'][] = 'Projekte konnten nicht aus der Datenbank gelesen werden.';
                }
                
                $meta_data['page_title'] = 'Meine Projekte';

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
                    $this->load->view('dashboard', $data_dash);
                }        
        } else {
            redirect('home/restricted');
        }
    }
    
    // LK 2013-07-16: Create new Project (Setup Page)
    public function new_project() {
        if ($this->session->userdata('is_logged_in')) {
            $query_users = $this->db->query(
                'SELECT id, username FROM users WHERE id <> ? ' .
                'ORDER BY username',
                array($this->session->userdata('userid'))
            );

            if ($query_users) {
                if ($query_users->num_rows() > 0) {
                    foreach ($query_users->result() as $row) {
                        $user_data['users'][] = array(
                            'id' => $row->id,
                            'username' => $row->username,
                        );
                    }
                } else {
                    $user_data['users'] = array();
                } 
            } else {
                // Show error message
                $meta_data['page_title'] = "Projekt erstellen";
                $body_data['page_title'] = "Projekt erstellen";
                $body_data['error_messages'][] = "Benutzer konnten nicht aus der Datenbank gelesen werden.";
                $this->load->view('header');
                $this->load->view('meta', $meta_data);
                $this->load->view('show_message', $body_data);
            }
        	
            $meta_data['page_title'] = "Projekt erstellen";
            $this->load->view("meta", $meta_data);
            $this->load->view("header");
            $this->load->view('new_project', $user_data);
        } else {
            redirect('home/restricted');
        }
    }

    public function new_project_validation() {
        if ($this->session->userdata('is_logged_in')) {
            $this->form_validation->set_rules('project_name', 'Projektname', 'required');

            if ($this->form_validation->run()) {
                $this->load->model('model_projects');
                $this->load->model('model_roles');

                $insert_id = $this->model_projects->add_project(
                    $this->config->item('artifacts_location'),
                    $this->input->post('project_name'),

                    //insert NULL to database if the description is void
                    $this->input->post('project_description') ? $this->input->post('project_description') : NULL,

                    //insert NULL to database if the requirements are void
                    $this->input->post('project_general_requirements') ? $this->input->post('project_general_requirements') : NULL
                );

                if ($insert_id) {
                    header('refresh:3;url=/project_view/' . $insert_id);
                    
                    //authorize users for the project
                    $userdata = $this->input->post('userdata');
                    $user_array = preg_split('/;/', $userdata, NULL, PREG_SPLIT_NO_EMPTY);
                    $state = 1;
                    foreach ($user_array as $user_string) {
                        $user_data = preg_split('/,/', $user_string, NULL, PREG_SPLIT_NO_EMPTY);
                        $user_id = $user_data[0];
                        $is_admin = $user_data[1];
                        if ($user_id and ($is_admin == 'false' or $is_admin == 'true')) {
                            $return_state = $this->model_roles->add_user_to_project($insert_id, $user_id, ($is_admin == 'false' ? 0 : 1));
                            if (!$return_state) {
                                $state = 0;
                            }
                        } else {                                
                            $body_data['success_messages'][] = 'Das Projekt "' . htmlspecialchars($this->input->post('project_name')) . '" wurde erfolgreich erstellt.';
                            $body_data['error_messages'][] = 'Fehler beim Berechtigen der Benutzer f&uuml;r das Projekt.';
                        }
                    }

                    if ($state == 1) {
                        $body_data['success_messages'][] = 'Das Projekt "' . htmlspecialchars($this->input->post('project_name')) . '" wurde erfolgreich erstellt.';
                    }
                    else {
                        $body_data['success_messages'][] = 'Das Projekt "' . htmlspecialchars($this->input->post('project_name')) . '" wurde erfolgreich erstellt.';
                        $body_data['error_messages'][] = 'Fehler beim Berechtigen der Benutzer f&uuml;r das Projekt.';
                    }

                } else {
                    header('refresh:3;url=/dashboard');
                    $body_data['error_messages'][] = 'Fehler beim Erstellen des Projekts.';
                }
                
                $body_data['page_title'] = 'Projekt erstellen';
                $body_data['breadcrumbs'] = array(
                  array('name'  => 'Projekt erstellen',
                        'url'   => '/dashboard/new_project')
                );
                
                $meta_data['page_title'] = 'Projekt erstellen';

                // Show success/error message(s)
                $this->load->view('header');
                $this->load->view('meta', $meta_data);
                $this->load->view('show_message', $body_data);

            } else {
                //TODO MP 29.07.2013: display error message, repopulate form (redesign form to CI style like form_register, form_login)
                $this->new_project();
            }
        } else {
            redirect('home/restricted');
        }
    }
}

?>