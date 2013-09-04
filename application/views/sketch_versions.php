<div id="bc">
    <div class="container">
        <div class="row">
            <div class="span12">
                <a href="/home"><i class="icon-home"></i></a> / <a href="/dashboard">Meine Projekte</a> / <a href="/project_view/<?php echo $project->id ?>"><?php echo htmlspecialchars($project->name) ?></a> / <a href="/project_view/<?php echo $project->id ?>/#tab_lB">Skizzen</a> / <a href="/designer?sketch_id=<?php echo $sketch->id; ?>"><?php echo htmlspecialchars($sketch->name); ?></a> / <a href="/project_view/sketch_versions/<?php echo $sketch->id ?>">Verlauf</a>
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
                                        foreach ($history as $entry) {
                                                echo "						<tr>\n";
                                                echo '							<td><a href="/profile/' . $entry->userid . '">' . htmlspecialchars($entry->username) . "</a></td>\n";
                                                echo "							<td>" . $entry->action_timestamp . "</td>\n";
                                                echo "                          <td>" . htmlspecialchars($entry->action_description) . "</td>\n";
                                                echo "						</tr>\n";
                                        }
                                        ?>

                                    </tbody>
				</table>
			</div>
		</div>
		<div class="span8">
			<h3 class="text-center">Skizzenverlauf: <?php echo htmlspecialchars($sketch->name); ?></h3>
			<hr />
                        
                        <div class="padding-bottom">
                            <p>Hier sehen Sie den Verlauf der Skizze &quot;<?php echo htmlspecialchars($sketch->name); ?>&quot;.<br />
                            Sie k&ouml;nnen jede Version der Skizze bearbeiten oder als neue Skizze duplizieren.</p>
                            <small>Hinweis: Wenn Sie eine alte Version einer Skizze bearbeiten wird beim Speichern eine neue Version erstellt.</small>                            
                        </div>
                        
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Beschreibung</th>
						<th>Autor</th>
						<th>Erstelldatum</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
                    foreach ($versions as $version) {?>
				
					<tr>
                                            <td><!--<a href="/designer?sketch_id=<?php echo $sketch->id; ?>&sketch_version=<?php echo $version['id']; ?>">--><?php echo htmlspecialchars($version['description']); ?><!--</a>--></td>
                                            <td><a href="/profile/<?php echo $version['creator_id'] ?>"><?php echo htmlspecialchars($version['creator']); ?></a></td>
                                            <td><?php echo $version['create_timestamp']; ?></td>
                                            <td>
                                                <div class="btn-group pull-right">
                                                    <a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#">
                                                        Optionen
                                                        <span class="caret"></span>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li><a tabindex="-1" href="/designer?sketch_id=<?php echo $sketch->id; ?>&sketch_version=<?php echo $version['id']; ?>">Bearbeiten</a></li>
                                                        <li><a tabindex="-1" href="/project_view/duplicate_sketch/<?php echo $sketch->id; ?>/<?php echo $version['id']; ?>">Duplizieren</a></li>
                                                    </ul>
                                                </div>
                                            </td>
					</tr>
					
					<?php
                    }
                    ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
