<!-- Project overview: "Delete Project" Modal Window -->
<div id="delete_project" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="new_sketch" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="myModalLabel">Projekt l&ouml;schen</h3>
    </div>
    <div class="modal-body">
        <p>Wollen Sie das Projekt wirklich l&ouml;schen?</p>
    </div>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Schliessen</button>
        <a href="/project_view/delete_project/<?php echo $project_id ?>" role="button" data-toggle="modal" class="btn btn-warning btn-primary">Projekt l&ouml;schen</a>
    </div>
</div>