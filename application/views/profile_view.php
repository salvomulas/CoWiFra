<div id="bc">
    <div class="container">
        <div class="row">
            <div class="span12">
                <a href="/home"><i class="icon-home"></i></a> / <a href="/profile/<?php echo $user_id ?>">Benutzerprofil von <?php echo htmlspecialchars($user_name); ?></a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    
    <div class="row">
        <div class="span12">
            <h1>Benutzerprofil von <?php echo htmlspecialchars($user_name); ?></h1>
        </div>
    </div>
    
    <?php
    if ($this->session->userdata('userid') === $user_id) {
    ?>
    <div class="pull-right">                
        <a class="btn btn-warning btn-primary" href="/profile/edit">Profil bearbeiten</a>
    </div>
    <?php
    }
    ?>
        
    <div class="row">
        <div class="span3 well">
            <div class="row-fluid">
                <div class="span12"><h4>Aktivit&auml;t</h4></div>
            </div>
            <div class="row-fluid">
                <div class="span5">Mitglied seit:</div>
                <div class="span7"><?php echo $user_created; ?></div>
            </div>
            <div class="row-fluid">Eigene Projekte:</div>
            <div class="row-fluid">
                <ul>
                    <?php
                    if ($message_no_projects_owned) {
                        echo '<small>' . $message_no_projects_owned . '</small>';
                    }
                    foreach ($user_projects_owned as $project) {
                        echo '<li><a href=/project_view/'. $project->id . '>'. htmlspecialchars($project->name) . '</a></li>' . "\n";
                    }
                    ?>
                </ul>
            </div>
            <div class="row-fluid">&Uuml;brige Projekte:</div>
            <div class="row-fluid">
                    <ul>
                        <?php
                        if ($message_no_projects_member) {
                            echo '<small>' . $message_no_projects_member . '</small>';
                        }
                        foreach ($user_projects_member as $project) {
                            echo '<li><a href=/project_view/'. $project->id . '>'. htmlspecialchars($project->name) . '</a></li>' . "\n";
                        }
                        ?>
                    </ul>
            </div>
        </div>

        <div class="span6 padding-bottom">
            <div class="row">
                <div class="span6"><h3>Pers&ouml;nlich</h3></div>
            </div>
            <div class="row">
                <div class="span1 text-right">Vorname:</div>
                <div class="span5"><?php echo htmlspecialchars($user_firstname); ?></div>
            </div>
            <div class="row">
                <div class="span1 text-right">Nachname:</div>
                <div class="span5"><?php echo htmlspecialchars($user_lastname); ?></div>
            </div>
            <div class="row">
                <div class="span1 text-right">Email:</div>
                <div class="span5"><a href="mailto:<?php echo htmlspecialchars($user_email) ?>"><?php echo htmlspecialchars($user_email) ?></a></div>
            </div>        
        </div>
        <div class="span6">
            <div class="row">
                <div class="span6"><h3>Unternehmen</h3></div>
            </div>
            <div class="row">
                <div class="span1 text-right">Funktion:</div>
                <div class="span5"><?php echo htmlspecialchars($user_job_description); ?></div>
            </div>
            <div class="row">
                <div class="span1 text-right">Firma:</div>
                <div class="span5"><?php echo htmlspecialchars($user_company); ?></div>
            </div>
        </div>
    </div>
</div>