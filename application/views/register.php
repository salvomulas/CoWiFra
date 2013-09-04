<!-- Registration Modal Window -->
<div id="signup" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="signup" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="myModalLabel">Registrieren</h3>
    </div>
    <div class="modal-body">
        <p>Bitte alle Felder ausfuellen, um die Registration durchzufuehren. Nach dem Abschliessen der Registration bekommen Sie eine E-Mail. Klicken Sie auf den darin enthaltenen Link, um Ihren Account zu aktivieren.</p>
        <?php
        include('form_register.php');
        ?>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Abbrechen</button>
        <?php
        echo form_button($reg_submit);
        echo form_close();
        ?>
    </div>
</div>