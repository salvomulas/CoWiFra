<?php
// Form field definitions

$reg_username = array(
    'name' => 'username',
    'id' => 'username',
    'placeholder' => 'Username',

);

$reg_firstname = array(
    'name' => 'firstname',
    'id' => 'firstname',
    'placeholder' => 'Vorname',

);

$reg_lastname = array(
    'name' => 'lastname',
    'id' => 'lastname',
    'placeholder' => 'Nachname',

);

$reg_email = array(
    'name' => 'email',
    'id' => 'email',
    'placeholder' => 'E-Mail Adresse',

);

$reg_password = array(
    'name' => 'password',
    'id' => 'password',
    'placeholder' => 'Passwort',
);

$reg_password_chk = array(
    'name' => 'password_chk',
    'id' => 'password_chk',
    'placeholder' => 'Passwort best&auml;tigen',
);

$reg_submit = array(
    'value' => 'Registrieren',
    'id' => 'sign_up',
    'content' => 'Registrieren',
    'class' => 'btn btn-warning btn-primary',
    'type' => 'submit',
);
?>

<table class="table table-condensed">
    <tbody>
        <tr>
            <?php
            echo form_open('home/signup_validation');
            echo form_hidden('form_name', 'form_register');

            // Display the modal window again if this form's validation failed
            if (validation_errors() && $this->input->post('form_name') === 'form_register') { ?>
                <script type="text/javascript">$('#signup').modal('show');</script>
                <div class="alert alert-error"><?php echo validation_errors() ?></div>
                <?php
            }

            echo '<th>Username</th>';
            echo '<td>'.form_input($reg_username, $this->input->post('username')).'</td>';
            echo '</tr><tr>';
            echo '<th>Vorname</th>';
            echo '<td>'.form_input($reg_firstname, $this->input->post('firstname')).'</td>';
            echo '</tr><tr>';
            echo '<th>Nachname</th>';
            echo '<td>'.form_input($reg_lastname, $this->input->post('lastname')).'</td>';
            echo '</tr><tr>';
            echo '<th>E-Mail</th>';
            echo '<td>'.form_input($reg_email, $this->input->post('email')).'</td>';
            echo '</tr><tr>';
            echo '<th rowspan="2">Passwort</th>';
            echo '<td>'.form_password($reg_password).'</td>';
            echo '</tr><tr>';
            echo '<td>'.form_password($reg_password_chk).'</td>';
            echo '</tr><tr>';
            
            ?>
            
        </tr>
    </tbody>
</table>
<hr>