        <script type="text/javascript">
        	function populate_hidden_field() {
        		var data = "";
        		var users = document.getElementById("ul_users").getElementsByTagName("li");
        		var admin_role = document.getElementById("ul_users").getElementsByTagName("input");
        		for (var i=0; i < users.length; i++) {
				    data = data.concat( users[i].id, ",", admin_role[i].checked, ";" );
				}
				
				document.getElementById("userdata_hidden").value = data;
        	}
        </script>
        
        <div id="bc">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <a href="/home"><i class="icon-home"></i></a> / <a href="/dashboard">Meine Projekte</a> / <a href="/project_view/<?php echo $project->id ?>"><?php echo htmlspecialchars($project->name) ?></a> / <a href="/project_view/edit_project_info/<?php echo $project->id ?>">Projektinfos bearbeiten</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row center">
                <div class="span12">
                    <h1 class="text-center">Projektinformationen bearbeiten</h1>
                </div>
            </div>
            
            <?php
            if (validation_errors()) {
                echo '<div class="alert alert-error">' . validation_errors() . '</div>';
            }
            ?>

            <div class="row">
                <div class="span8">
                <h3>Projektdetails</h3>
                    <table class="table table-striped">
                        <form id="edit_project_info_form" action="/project_view/edit_project_info_validation" method="post">
                            <fieldset>
                                <tr>
                                    <td class="span3">Projektname (*):</td>
                                    
                                    <!-- Disable input field if not owner. -->
                                    <td><input name="project_name" value="<?php echo htmlspecialchars($project->name) ?>" placeholder="Projekt 1" class="span6" type="text" <?php if ($user_role != 'Besitzer') { echo "disabled"; } ?>></td>
                                </tr>
                                <tr>
                                	<!-- Disable input field if not owner. -->
                                    <td>Beschreibung:</td>
                                    <td><textarea name="project_description" class="span6 bottom-space" rows="5" placeholder="Beschreiben Sie das Projekt" <?php if ($user_role != 'Besitzer') { echo "disabled"; } ?>><?php echo htmlspecialchars($project->description) ?></textarea></td>
                                </tr>
                                <tr>
                                    <td>Anforderungen:</td>
                                    <td>
                                    	<textarea name="project_general_requirements" class="span6 bottom-space" rows="5" placeholder="Erfassen Sie Anforderungen f&uuml;r das Projekt"><?php echo htmlspecialchars($project->general_requirements) ?></textarea>
                                    	
                                    	<?php if ($user_role == 'Besitzer' or $user_role == 'Administrator') {
                                			echo <<<HTML0
                                    	<input id="userdata_hidden" name="userdata" value="" type="hidden">
                                    	<input name="project_id" value="$project->id" type="hidden">
                                    	<input name="redirect_tab" value="$redirect_tab" type="hidden">
HTML0;
                                    	}
                                    	else {
                                    		echo <<<HTML05
                                    	<input name="project_id" value="$project->id" type="hidden">
                                    	<input name="redirect_tab" value="$redirect_tab" type="hidden">
HTML05;
                                    	}
                                    	?>
                                    </td>
                                </tr>
                                
                            </fieldset>
                        </form>
                                
                                <?php if ($user_role == 'Besitzer' or $user_role == 'Administrator') {
                                	echo <<<HTML1
                                <tr>
                                    <td>Benutzer:<br /><small>Checkbox = Administrator</small></td>
                                    <td>
                                        <ul id="ul_users" class="userlist_project unstyled list-padding">
HTML1;

									foreach ($team_data as $user) {
										//<li class="6" id="6"><input id="" value="checkbox" class="adminbox" type="checkbox"> cleanup <button class="btn btn-mini pull-right remove">entfernen</button></li>
		                    			echo "                                    <li class= \"" . $user['id'] . "\" id=\"" . $user['id'] . "\"><input id=\"\" value=\"checkbox\" class=\"adminbox\" type=\"checkbox\"" . ($user['role'] == 'Administrator' ? "checked" : "") . "> " . htmlspecialchars($user['username']) . " <button class=\"btn btn-mini pull-right remove\">entfernen</button></li>\n";
		                    		}

									echo <<<HTML2
                                        </ul>
                                    </td>
                                </tr>
HTML2;
                                } ?>
                                
                                <tr>
                                	<?php if ($user_role == 'Besitzer' or $user_role == 'Administrator') {
                                		echo <<<HTML3
                                    <td>
                                    	
                                    </td>
                                    <td><button onclick="populate_hidden_field(); document.getElementById('edit_project_info_form').submit();" class="btn btn-warning pull-right">Speichern</button></td>
HTML3;
									}
									else {
										echo <<<HTML4
                                    <td>
                                    	
                                    </td>
                                    <td><button onclick="document.getElementById('edit_project_info_form').submit();" class="btn btn-warning pull-right">Speichern</button></td>
HTML4;
									} ?>
                                </tr>
                    </table>
                </div>
				
				<?php if ($user_role == 'Besitzer' or $user_role == 'Administrator') {
					echo <<<HTML5
                <div class="span4">
                    <h3>Benutzer hinzuf&uuml;gen</h3>
                    <table class="table table-striped">
                        <tr>
                            <td colspan="2"><input id="filter" class="span4" type="text" placeholder="Benutzer suchen..." /></td>
                        </tr>
                        <tr>
                            <td>
                                <ul class="userlist unstyled list-padding">
HTML5;
	                                	foreach ($other_users as $user) {
			                    			echo "                                    <li class=\"" . $user['id'] . "\" id=\"" . $user['id'] . "\">" . htmlspecialchars($user['username']) . " <button class=\"add btn btn-mini pull-right\">hinzuf&uuml;gen</button></li>\n";
			                    		}
                                	
                                		echo <<<HTML6
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
HTML6;
                } ?>
            </div>
            <hr>
        </div>
        
        <!-- Load custom.js at the end of the script when all elements are created. -->
        <?php echo '		<script src="' . base_url() . 'assets/js/custom.js"></script>'; ?>
        
    </body>
</html>
