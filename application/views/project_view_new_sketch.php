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
    'value' => 'Erstellen',
    'id' => 'create',
    'content' => 'Erstellen',
    'class' => 'btn btn-warning btn-primary',
    'type' => 'submit',
);
?>

<!-- Project overview: "New Sketch" Modal Window -->
<div id="new_sketch" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="new_sketch" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="myModalLabel">Skizze erstellen</h3>
    </div>
    <div class="modal-body">
        <p>Bitte beschreiben Sie hier die neu zu erstellende Skizze m&ouml;glichst genau.</p>
   
<?php 

// Display the modal window again if this form's validation failed
if (validation_errors() && $this->input->post('form_name') === 'project_view_new_sketch') {
    ?>
        <script type="text/javascript">$('#new_sketch').modal('show'); $('.nav-tabs a[href=#lB]').tab('show');</script>
        <div class="alert alert-error"><?php echo validation_errors(); ?></div>
    <?php
}
            
echo form_open('project_view/new_sketch/' . $project_id);
echo form_hidden('form_name', 'project_view_new_sketch');
?>
        <table class="table table-condensed">
            <tbody>
                <tr>
                    <th>Name</th>
                    <td><?php echo form_input($sketch_name, $this->input->post('sketch_name')); ?></td>
                </tr>
                <tr>
                    <th>Beschreibung</th>
                    <td><?php echo form_textarea($sketch_description, $this->input->post('description')) ?></td>
                </tr>
            </tbody>
        </table>

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Schliessen</button>
        <?php
        echo form_button($sketch_submit, 'class="button button-primary"');
        echo form_close();
        ?>
    </div>
</div>