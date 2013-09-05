<?php

/*
 * Controller for development
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Development extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
	
	function index() {
		$this->load->view('install');
	}

}