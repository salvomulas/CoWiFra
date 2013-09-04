<div id="bc">
    <div class="container">
        <div class="row">
            <div class="span12">
                <a href="/home"><i class="icon-home"></i></a> / <a href="/dashboard">Meine Projekte</a> / <a href="/project_view/<?php echo $project->id ?>"><?php echo htmlspecialchars($project->name) ?></a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row-fluid">
        <div class="span4">
            <h3 class="text-center"><?php echo htmlspecialchars($project->name) ?></h3>
            <hr>
            <div class="well">
                <h4>Informationen</h4>
                <table class="table table-condensed">
                    <tr>
                        <th>Besitzer:</th>
                        <td><a href="/profile/<?php echo $owner->id; ?>"><?php echo htmlspecialchars($owner->username) ?></a></td>
                    </tr>
                    <tr>
                        <th>Teammitglieder:</th>
                        <td><?php echo $stats['team_member_count'] ?></td>
                    </tr>
                    <tr>
                        <th>Skizzen:</th>
                        <td><?php echo $stats['sketches_count'] ?></td>
                    </tr>
                    <tr>
                        <th>Erstellt am:</th>
                        <td><?php echo $project->create_timestamp ?></td>
                    </tr>
                    <tr>
                        <th>Letzte Aktivit&auml;t:</th>
                        <td><?php echo $stats['last_activity'] ?></td>
                    </tr>
                </table>
            </div>
            <div class="well">
                <h4>Logbuch</h4>
                <table class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>Benutzer</th>
                            <th>Zeitpunkt</th>
                            <th>Aktion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (sizeof($history) === 0) {
                            echo '<tr><td colspan="3"><div class="alert alert-info">Es wurde noch nichts aufgezeichnet.</div></td></tr>' . "\n";
                        } else {                                                
                            foreach ($history as $entry) {
                                ?>
                        <tr>
                            <td><a href="/profile/<?php echo $entry->userid; ?>"><?php echo htmlspecialchars($entry->username); ?></a></td>
                            <td><?php echo $entry->action_timestamp; ?></td>
                            <td><?php echo htmlspecialchars($entry->action_description); ?></td>
                        </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="span8">
            <div class="tabbable tabbable-space">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#lA" data-toggle="tab">&Uuml;bersicht</a>
                    </li>
                    <li>
                        <a href="#lB" data-toggle="tab">Skizzen</a>
                    </li>
                    <li>
                        <a href="#lC" data-toggle="tab">Rohdaten</a>
                    </li>
                    <li>
                        <a href="#lD" data-toggle="tab">Team</a>
                    </li>
                    
                    <?php if ($role_current_user == 'Besitzer') { ?>
                    <li>
                        <a href="#lE" data-toggle="tab">Administration</a>
                    </li>
                    <?php } ?>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="lA">
                        <h3>&Uuml;bersicht <a href="/project_view/edit_project_info/<?php echo $project->id ?>/lA" role="button" data-toggle="modal" class="btn btn-warning btn-primary pull-right">Projektinfos bearbeiten</a></h3>

                        <h4>Projektbeschreibung</h4>
                        <p><?php echo nl2br(htmlspecialchars($project->description)); ?></p>
                        <h4>Anforderungen</h4>
                        <p><?php echo nl2br(htmlspecialchars($project->general_requirements)); ?></p>

                        <!-- Projektübersicht kommt hierher! -->
                    </div>
                    <div class="tab-pane" id="lD">
                        <h3>Team<?php if ($role_current_user == 'Besitzer' or $role_current_user == 'Administrator') { ?> <a href="/project_view/edit_project_info/<?php echo $project->id ?>/lD" role="button" data-toggle="modal" class="btn btn-warning btn-primary pull-right">Berechtigungen bearbeiten</a><?php } ?></h3>
                        <!-- Teamübersicht kommt hierher -->
                        <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Benutzername</th>
                                        <th>Vollst&auml;ndiger Name</th>
                                        <th>Rolle</th>
                                        <th>Firma</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($team_data as $user) {?>
                                    <tr>
                                        <td><a href="/profile/<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></a></td>
                                        <td><?php echo htmlspecialchars($user['firstname_lastname']); ?></td>
                                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                                        <td><?php echo htmlspecialchars($user['company']); ?></td>
                                    </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                        </table>
                    </div>

					<?php if ($role_current_user == 'Besitzer') { ?>
                    <div class="tab-pane" id="lE">
                        <h3>Administration</h3>
                        
                        <!-- Projekteinstellungen -->

                        <h4>Projektbesitzer &auml;ndern</h4>
                        <form action="/project_view/change_owner" method="post">
                            <input type="hidden" name="project_id" value="<?php echo $project->id; ?>">
                            <select name="owner">
                                    <?php foreach ($team_data as $user) {?>
                                    <option<?php if ($user['role'] == 'Besitzer') { ?> selected="selected"<?php } ?> value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                                    <?php } ?>
                            </select>
                            <br />
                            <button type="submit" class="btn btn-warning btn-primary">Speichern</button>
                        </form>

                        <h4 class="padding-top">Projekt l&ouml;schen</h4>
                        <p>
                            <a href="#delete_project" role="button" data-toggle="modal" class="btn btn-warning btn-primary">L&ouml;schen</a>
                        </p>
                    </div>
					<?php } ?>

                    <div class="tab-pane" id="lC">
                        <h3>Rohdaten <a href="/project_view/upload_artifact_view/<?php echo $project->id ?>" role="button" data-toggle="modal" class="btn btn-warning btn-primary pull-right">Rohdaten hochladen</a></h3>

                        <!-- Rohdatenübersicht kommt hierher -->
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="20%">Dateiname</th>
                                    <th width="55%">Beschreibung</th>
                                    <th width="25%" colspan="2">Hochgeladen von</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php
                                    if (sizeof($artifacts_data) === 0) {?>
                                        <tr><td colspan="4"><div class="alert alert-info">Es sind noch keine Rohdaten vorhanden.</div></td></tr>
                                        <?php
                                    } else {
                                        foreach ($artifacts_data as $artifact) {?>
                                <tr>
                                    <td><a href="<?php echo $artifact['link']; ?>"><?php echo htmlspecialchars($artifact['name']); ?></a></td>
                                    <td><?php echo htmlspecialchars($artifact['description']); ?></td>
                                    <td><a href="/profile/<?php echo $artifact['owner_id']; ?>"><?php echo htmlspecialchars($artifact['owner']); ?></a></td>
                                    <td><a class="btn btn-mini pull-right" href="/project_view/delete_artifact/<?php echo $artifact['id']; ?>">L&ouml;schen</a></td>
                                </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="lB">
                        <h3>Skizzen <a href="#new_sketch" role="button" data-toggle="modal" class="btn btn-warning btn-primary pull-right">Neue Skizze</a></h3>
                        <!-- Skizzenübersicht kommt hierher -->

                        <div class="padding-bottom">
                            <table class="table table-hover">
                                    <thead>
                                            <tr>
                                                    <th></th>
                                                    <th>Name</th>
                                                    <th>Autor</th>
                                                    <th>Letzte &Auml;nderung</th>
                                                    <th></th>
                                            </tr>
                                    </thead>
                                    <tbody>
                                            <?php
                                            if (sizeof($sketches_data) === 0) {?>
                                                <tr><td colspan="5"><div class="alert alert-info">Es sind noch keine Skizzen vorhanden.</div></td></tr>
                                                <?php
                                            } else {
                                                foreach ($sketches_data as $sketch) {?>
                                        <tr id="<?php echo $sketch['id'] ?>">
                                            <td><i class="icon-chevron-right open-description"></i></td>
                                            <td><a href="/designer?sketch_id=<?php echo $sketch['id']; ?>"><?php echo htmlspecialchars($sketch['sketch_name']); ?></a></td>
                                            <td><a href="/profile/<?php echo $sketch['userid']; ?>"><?php echo htmlspecialchars($sketch['username']); ?></a></td>
                                        <td><?php echo $sketch['latest_sketch_ts']; ?></td>
                                        <td>
                                            <div class="btn-group pull-right">
                                              <a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#">
                                                Optionen
                                                <span class="caret"></span>
                                              </a>
                                              <ul class="dropdown-menu">
                                                    <li><a tabindex="-1" href="/project_view/sketch_versions/<?php echo $sketch['id']; ?>">Versionen</a></li>
                                                    <li><a tabindex="-1" href="/project_view/duplicate_sketch/<?php echo $sketch['id']; ?>">Duplizieren</a></li>
                                                    <?php if ($id_current_user == $sketch['userid'] or $role_current_user == 'Besitzer' or $role_current_user == 'Administrator') { ?>
                                                    <li class="divider"></li>
                                                    <li><a tabindex="-1" href="/project_view/delete_sketch/<?php echo $sketch['id']; ?>">L&ouml;schen</a></li>
                                                    <?php } ?>
                                              </ul>
                                            </div>
                                        </td>
                                        </tr>

                                        <tr id="<?php echo $sketch['id']; ?>-description" class="description">
                                            <td></td>
                                            <td colspan="4"><?php echo ($sketch['description'] != NULL ? nl2br(htmlspecialchars($sketch['description'])) : 'Keine Beschreibung vorhanden.'); ?></td>
                                        </tr>
                                                        <?php
                                                }
                                            }
                    ?>
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/enable_link_to_tab.js"></script>

<?php $this->load->view("project_view_new_sketch", Array('project_id' => $project->id)); ?>
<?php $this->load->view("project_view_delete_project", Array('project_id' => $project->id)); ?>