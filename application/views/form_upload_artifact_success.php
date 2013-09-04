		<div id="bc">
		    <div class="container">
		        <div class="row">
		            <div class="span12">
		                <a href="/home"><i class="icon-home"></i></a> / <a href="/dashboard">Meine Projekte</a> / <a href="/project_view/<?php echo $project->id ?>"><?php echo htmlspecialchars($project->name) ?></a> / <a href="/project_view/upload_artifact_view/<?php echo $project->id ?>">Rohdaten hochladen</a> / <a>Erfolgreich</a>
		            </div>
		        </div>
		    </div>
		</div>
		
		<div class="container">
			<h3>Rohdaten hochladen</h3>
		
                        <div class="alert alert-success"><p>Datei <?php echo htmlspecialchars($filename); ?> wurde erfolgreich auf den Server hochgeladen.</p></div>
			
			<p><?php echo anchor('project_view/upload_artifact_view/' . $project->id, 'Weitere Datei hochladen'); ?></p>
			<p><?php echo anchor('project_view/' . $project->id . '/#tab_lC', 'Zurück zum Projekt "' . htmlspecialchars($project->name) . '"'); ?></p>
		</div>

	</body>
</html>