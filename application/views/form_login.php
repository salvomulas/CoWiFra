<?php

if ($this->session->userdata('is_logged_in')) {
    ?>

    <li><a href="/dashboard">Meine Projekte</a></li>
    <li><a href="/profile"><i class="icon-user"></i><?php echo htmlspecialchars($this->session->userdata('username')); ?></a></li>
    <a href="/home/logout" class="btn btn-warning btn-primary pull-right">Logout</a>
    <?php
} else {
    // Form field definitions
    $username = array(
        'name' => 'username',
        'id' => 'username',
        'placeholder' => 'Username',
        'style' => 'margin-bottom: 15px;',
    );

    $password = array(
        'name' => 'password',
        'id' => 'password',
        'placeholder' => 'Passwort',
        'style' => 'margin-bottom: 15px;',
    );

    $submit = array(
        'value' => 'Login',
        'id' => 'sign_in',
        'class' => 'btn btn-inverse btn-block',
    );
    
    ?>
    
    <li class="dropdown" id="login_dropdown">
        <a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon-lock"></i> Login <strong class="caret"></strong></a>
        <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
    <?php
    echo form_open('home/login_validation');
    echo form_hidden('form_name', 'form_login');

    // Display the dropdown login form again if this form's validation failed
    if (validation_errors() && $this->input->post('form_name') === 'form_login') { ?>
        <script type="text/javascript">$("#login_dropdown").addClass("open");</script>
        <div class="alert alert-error"><?php echo validation_errors() ?></div>
        <?php
    }

    echo form_input($username);
    echo form_password($password);
    echo form_submit($submit);

    echo form_close();
    
    echo '</div></li>';
    echo '<a href="#signup" role="button" data-toggle="modal" class="btn btn-warning btn-primary pull-right">Registrieren</a>';
    
    // Load the registration form
    $this->load->view("register");}
?>
