		<div id="bc">
		    <div class="container">
		        <div class="row">
		            <div class="span12">
		                <a href="/home"><i class="icon-home"></i></a> / <a href="/dashboard">Meine Projekte</a> / <a href="/project_view/<?php echo $project->id ?>"><?php echo htmlspecialchars($project->name) ?></a> / <a href="/project_view/upload_artifact_view/<?php echo $project->id ?>">Rohdaten hochladen</a>
		            </div>
		        </div>
		    </div>
		</div>
		
		<div class="container">
			<h3>Rohdaten hochladen</h3>
		
			<?php if(strlen(trim($error)) > 0) { ?>
                        <div class="alert alert-error"><?php echo $error;?></div>
                        <?php } ?>
	
			<p>
				<?php echo form_open_multipart('project_view/do_upload_artifact/' . $project->id);?>
				
				<input type="file" name="userfile" size="20" />
				
				<br /><br />
				
				<textarea name="description" class="span6 bottom-space" rows="5" placeholder="Beschreibung des Dokuments"></textarea>
				
				<br /><br />
				
				<input type="submit" value="Hochladen" />
				
				</form>
			</p>
		</div>

	</body>
</html>