<table class="table table-condensed">
    <tbody>
        <tr>
            <?php
            echo form_open('profile/edit');

            $profile_firstname = array(
                'name' => 'firstname',
                'id' => 'firstname',
                'label' => 'Vorname',
            );
            
            $profile_lastname = array(
                'name' => 'lastname',
                'id' => 'lastname',
                'label' => 'Nachname',
            );
            
            $profile_email = array(
                'name' => 'email',
                'id' => 'email',
                'label' => 'E-Mail',
            );            

            $profile_jobdescription = array(
                'name' => 'jobdescription',
                'id' => 'jobdescription',
                'label' => 'Jobbeschreibung',
            );
            
            $profile_company = array(
                'name' => 'company',
                'id' => 'company',
                'label' => 'Firma',
            );
            
            $profile_old_password = array(
                'name' => 'oldpassword',
                'id' => 'oldpassword',
                'label' => 'Altes Passwort',
            );
            
            $profile_password = array(
                'name' => 'password',
                'id' => 'password',
                'label' => 'Neues Passwort',
            );

            $profile_password_chk = array(
                'name' => 'password_chk',
                'id' => 'password_chk',
                'label' => 'Neues Passwort best&auml;tigen',
            );

            $profile_submit = array(
                'value' => 'Speichern',
                'id' => 'save_profile',
                'content' => 'Speichern',
                'class' => 'btn btn-warning btn-primary',
                'type' => 'submit',
            );

            if (validation_errors()) {
                echo '<div class="alert alert-error">' . validation_errors() . '</div>';
            }

            echo '<th>Vorname</th>';
            echo '<td>'.form_input($profile_firstname, $this->input->post('firstname') ? $this->input->post('firstname') : $user_firstname ).'</td>';
            echo '</tr><tr>';
            echo '<th>Nachname</th>';
            echo '<td>'.form_input($profile_lastname, $this->input->post('lastname') ? $this->input->post('lastname') : $user_lastname ).'</td>';
            echo '</tr><tr>';
            echo '<th>E-Mail</th>';
            echo '<td>'.form_input($profile_email, $this->input->post('email') ? $this->input->post('email') : $user_email ).'</td>';
            echo '</tr><tr>';            
            echo '<th>Jobbeschreibung</th>';
            echo '<td>'.form_input($profile_jobdescription, $this->input->post('jobdescription') ? $this->input->post('jobdescription') : $user_job_description ).'</td>';
            echo '</tr><tr>';
            echo '<th>Firma</th>';
            echo '<td>'.form_input($profile_company, $this->input->post('company') ? $this->input->post('company') : $user_company ).'</td>';
            echo '</tr><tr>';
            echo '<th>Altes Passwort</th>';
            echo '<td>'.form_password($profile_old_password).'</td>';
            echo '</tr><tr>';
            echo '<th rowspan="2">Passwort</th>';
            echo '<td>'.form_password($profile_password, $this->input->post('password') ).'</td>';
            echo '</tr><tr>';
            echo '<td>'.form_password($profile_password_chk).'</td>';
            echo '</tr><tr>';
            echo '<th></th>';
            echo '<td>' . form_button($profile_submit) . '</td>';
            echo '</tr>';
            
            echo form_close();
            ?>
        </tr>
    </tbody>
</table>
<hr>