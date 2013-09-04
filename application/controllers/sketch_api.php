<?php

/**
 * Provides methods for loading and saving sketches to/from the database.
 * These methods are to be called via an XmlHttpRequest from the Designer.
 * 
 * @author SR
 */
class sketch_api extends CI_Controller {
    
    /**
     * Loads specified version of a sketch from the database.
     * Intended for use via XmlHttpRequest.
     * GET: sketch_id, 
     * @author SR
     * @return String Data of latest version of sketch with given ID
     */
    public function load_data() {
        $this->load->model('model_sketch');
        
        $sketch_id = $this->input->get('sketch_id');
        $sketch_version = $this->input->get('sketch_version');
        
        // Default to the latest sketch version if none specified
        // Store the number of sketch versions, so we can create a new one if necessary.
        if ($sketch_id && !$sketch_version) {
            
            // Does a version of the sketch exist yet?
            if ($this->model_sketch->get_sketch_version_count($sketch_id) == 0) {
                // No sketch version exists, create new blank sketch
                echo "CREATE_NEW";
            } else {
                // Sketch exists already, get latest version and run next if block
                $sketch_version = $this->model_sketch->get_latest_version_id($sketch_id);
            }
            
        }
        
        if ($sketch_id && $sketch_version) {
            $sketch_version_data = $this->model_sketch->get_sketch_version($sketch_version);
            
            // Check if sketch version ID is valid and belongs to the specified sketch ID
            if ($sketch_version_data != false) {
                if ($sketch_version_data->sketches_id == $sketch_id) {
                    echo $sketch_version_data->data;
                } else {
                    echo "Sketch version ID doesn't belong to specified sketch ID.";
                }
            } else {
                echo "Invalid sketch version ID.";
            }
            
        } else if (!$sketch_id) {
            echo "No sketch ID specified.";
        }
        
    }

    /**
     * Saves new version of a sketch into the database.
     * Intended for use via XmlHttpRequest.
     * POST: sketch_id, data, description
     * @author SR
     * @return String Success/failure message
     */
    public function save_to_db() {
        $this->load->model('model_sketch');

        if ($this->model_sketch->add_version()) {
            echo 'Die Skizze wurde erfolgreich gespeichert.';
        } else {
            echo 'Es trat ein Problem auf beim Speichern der Skizze.';
        }
    }
    
}

?>
