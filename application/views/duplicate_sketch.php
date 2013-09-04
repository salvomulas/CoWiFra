<?php
$sketch_name = array(
    'name' => 'sketch_name',
    'id' => 'sketch_name',
    'placeholder' => 'Name',
    'rules' => 'required',
);

$sketch_description = array(
    'name' => 'description',
    'id' => 'description',
    'placeholder' => 'Beschreibung',
    'type' => 'textarea',
    'rules' => 'required',
);

$sketch_submit = array(
    'value' => 'Duplizieren',
    'id' => 'duplicate',
    'content' => 'Duplizieren',
    'class' => 'btn btn-warning btn-primary',
    'type' => 'submit',
);
?>
        
        <div id="bc">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <a href="/home"><i class="icon-home"></i></a> / <a href="/dashboard">Meine Projekte</a> / <a href="/project_view/<?php echo $project->id ?>"><?php echo htmlspecialchars($project->name); ?></a> / <a href="/project_view/<?php echo $project->id ?>/#tab_lB">Skizzen</a> / <a href="/project_view/duplicate_sketch/<?php echo $sketch->id ?>">Skizze duplizieren</a>
                        <a href="/help" class="pull-right" onclick="window.open(this.href, 'Hilfebereich',
'left=20,top=20,width=450,height=600,toolbar=0,resizable=0'); return false;" >Hilfebereich</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <h1>Skizze duplizieren</h1>
			
			<?php if (validation_errors() && $this->input->post('form_name') === 'duplicate_sketch') { ?>
			<div class="alert alert-error"><?php echo validation_errors(); ?></div>
			<?php } ?>

            <div class="row">
                <div class="span8">
                	<?php
                		echo form_open('project_view/duplicate_sketch_validation/' . $sketch->id);
                		echo form_hidden('form_name', 'duplicate_sketch');
                		echo form_hidden('version_id', $version_id);
                	 ?>
                    	<table class="table table-striped">
                                <tr>
                                	<th>Name</th>
                                    <td><?php echo form_input($sketch_name, ($this->input->post('sketch_name') ? htmlspecialchars($this->input->post('sketch_name')) : htmlspecialchars($sketch->name))); ?></td>
                                </tr>
                                <tr>
                                	<th>Beschreibung</th>
                                    <td><?php echo form_textarea($sketch_description, ($this->input->post('description') ? htmlspecialchars($this->input->post('description')) : htmlspecialchars($sketch->description))); ?></td>
                                </tr>
                        </table>
                        
                        <?php
					        echo form_button($sketch_submit, 'class="button button-primary"');
					        echo form_close();
					    ?>
                </div>
            </div>
            <hr>
        </div>
    </body>
</html>
