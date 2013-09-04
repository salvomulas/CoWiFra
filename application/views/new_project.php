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
                        <a href="/home"><i class="icon-home"></i></a> / <a href="/dashboard">Meine Projekte</a> / <a href="/dashboard/new_project">Neues Projekt erstellen</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row center">
                <div class="span12">
                    <h1 class="text-center">Neues Projekt erstellen</h1>
                </div>
            </div>

            <div class="row">
                <div class="span8">
                <h3>Projektdetails</h3>
                    <table class="table table-striped">
                        <form id="new_project_form" action="new_project_validation" method="post">
                            <fieldset>
                                <tr>
                                    <td class="span3">Projektname:</td>
                                    <td><input name="project_name" class="span6" type="text" placeholder="Projekt 1"></td>
                                </tr>
                                <tr>
                                    <td>Beschreibung:</td>
                                    <td><textarea name="project_description" class="span6 bottom-space" rows="5" placeholder="Beschreiben Sie das Projekt"></textarea></td>
                                </tr>
                                <tr>
                                    <td>Anforderungen:</td>
                                    <td><textarea name="project_general_requirements" class="span6 bottom-space" rows="5" placeholder="Erfassen Sie Anforderungen f&uuml;r das Projekt"></textarea></td>
                                </tr>
                                <tr>
                                    <td>Benutzer:<br /><small>Checkbox = Administrator</small></td>
                                    <td>
                                        <ul id="ul_users" class="userlist_project unstyled list-padding">
                                            
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input id="userdata_hidden" name="userdata" value="" type="hidden"></td>
                                    <td><button onclick="populate_hidden_field();" type="submit" class="btn btn-warning pull-right">Projekt erstellen</button></td>
                                </tr>
                            </fieldset>
                        </form>
                    </table>
                </div>

                <div class="span4">
                    <h3>Benutzer hinzuf&uuml;gen</h3>
                    <table class="table table-striped">
                        <tr>
                            <td colspan="2"><input id="filter" class="span4" type="text" placeholder="Benutzer suchen..." /></td>
                        </tr>
                        <tr>
                            <td>
                                <ul class="userlist unstyled list-padding">
                                	<?php
                                	foreach ($users as $user) {
		                    			echo "                                    <li class= \"" . $user['id'] . "\" id=\"" . $user['id'] . "\">" . htmlspecialchars($user['username']) . " <button class=\"add btn btn-mini pull-right\">hinzuf&uuml;gen</button></li>\n";
		                    		}
		                    		?>
		                    		<!--
                                    <li class="1">Joerg <button class="add btn btn-mini pull-right">hinzuf&uuml;gen</button></li>
                                    <li class="2">Salvo <button class="add btn btn-mini pull-right">hinzuf&uuml;gen</button></li>
                                    <li class="3">Michel <button class="add btn btn-mini pull-right">hinzuf&uuml;gen</button></li>
                                    <li class="4">Stephen <button class="add btn btn-mini pull-right">hinzuf&uuml;gen</button></li>
                                    -->
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <hr>
        </div>
    </body>
</html>
