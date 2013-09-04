        <div id="bc">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <a href="/home"><i class="icon-home"></i></a> / <a href="/dashboard">Meine Projekte</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row center">
                <div class="span12">
                    <h1 class="text-center">Meine Projekte</h1>
                </div>
            </div>

            <div class="row-fluid">
                <div class="span3 well">
                    <h2>
                        <?php echo htmlspecialchars($user_data->username); ?>
                    </h2>
                    <p>
                        <span class="counter"><?php echo $project_metadata['my_projects_count'] ?></span><br />
                        <small>Eigene Projekte</small>
                    </p>
                    <p>
                        <span class="counter"><?php echo $project_metadata['other_projects_count'] ?></span><br />
                        <small>Projekte, an denen ich mitarbeite</small>
                    </p>
                    <p>
                        <span class="counter"><?php echo $project_metadata['my_sketches_count'] ?></span><br />
                        <small>Eigene Skizzen</small>
                    </p>
                    <p>
                        <span class="counter"><?php echo $project_metadata['other_sketches_count'] ?></span><br />
                        <small>Skizzen, an denen ich mitarbeite</small>
                    </p>
                    <p>
                    	<a href="/dashboard/new_project" role="button" data-toggle="modal" class="btn btn-warning btn-primary btn-block">Neues Projekt erstellen</a>
                    </p>
                </div>

                <div class="span9">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Projekt</th>
                                <th>Besitzer</th>
                                <th>Letzte Aktivit&auml;t</th>
                                <th>Meine Rolle</th>
                                <th>Skizzen</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php
                        		foreach ($project_data as $project) {
                        			echo "                            <tr>\n";
                        			echo '                                <td><a href="/project_view/' . $project['id'] . '">' . htmlspecialchars($project['project_name']) . "</a></td>\n";
                        			echo "                                <td>" . htmlspecialchars($project['owner_name']) . "</td>\n";
                        			echo "                                <td>" . $project['last_activity'] . "</td>\n";
                        			echo "                                <td>" . htmlspecialchars($project['role']) . "</td>\n";
                        			echo "                                <td>" . $project['number_of_sketches'] . "</td>\n";
                        			echo "                            </tr>\n";
                        		}
                        	?>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
        </div>
    </body>
</html>
