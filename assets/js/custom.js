$(document).ready(function(){
    // livesearch
    $("#filter").keyup(function(){
 
        // Retrieve the input field text and reset the count to zero
        var filter = $(this).val(), count = 0;
 
        // Loop through the comment list
        $(".userlist li").each(function(){
 
            // If the list item does not contain the text phrase fade it out
            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                $(this).fadeOut();
 
            // Show the list item if the phrase matches and increase the count by 1
            } else {
                $(this).show();
                count++;
            }
        });
 
        // Update the count
        var numberItems = count;
        $("#filter-count").text("Number of Comments = "+count);
    });

    // add and remove users from project
    $(".add, .remove").click(function() {
        if($(this).hasClass('remove')){
            $(this).text('hinzufügen');
            $(this).removeClass("remove").addClass("add");
            $(this).parent('li').children('input').remove();
            $(this).parent().appendTo(".userlist");
        } else {
            $(this).text('entfernen');
            $(this).removeClass("add").addClass("remove");
            $(this).parent().prepend('<input type="checkbox" id="'.concat(this.id, '" value="checkbox" class="adminbox" /> '));
            $(this).parent().appendTo(".userlist_project");
        }
    });

    // add and remove owner
    $(".ownerbutton").click(function() {
        if( $(this).parent().parent().hasClass("ownerlist")) {
            
            // button pressed in ownerlist

            if( $(".project-userlist").children().length == 0 ) {
                alert("Dem Projekt ist nur ein Benutzer zugewiesen. Der Besitzer kann daher nicht gelöscht werden.");
            } else {
                if($(this).hasClass('owneradd')){
                    $(this).text('entfernen');
                    $(this).removeClass("owneradd").addClass("ownerremove");
                    $(this).parent().appendTo(".ownerlist");
                } else {
                    $(this).text('hinzufügen');
                    $(this).removeClass("ownerremove").addClass("owneradd");
                    $(this).parent().appendTo(".project-userlist");
                }
            }

        } else {

            // button pressed in project-userlist

            if( $(".ownerlist").children().length >= 1 ) {
                alert("Es kann nur einen Projektbesitzer geben. Bitte löschen Sie zuerst den bestehenden Besitzer.");
            } else {
                if($(this).hasClass('ownerremove')){
                    $(this).text('hinzufügen');
                    $(this).removeClass("ownerremove").addClass("owneradd");
                    $(this).parent().appendTo(".project-userlist");
                } else {
                    $(this).text('entfernen');
                    $(this).removeClass("owneradd").addClass("ownerremove");
                    $(this).parent().appendTo(".ownerlist");
                }
            }
        }
    });

    // open and close sketch description
    $(".description").hide();

    $(".open-description").click(function() {
        if($(this).hasClass("close-description")){
            // close description
            $(this).removeClass("close-description").addClass("open-description");
            $(this).removeClass("icon-chevron-down").addClass("icon-chevron-right");
            $(this).parent().parent().next().hide();
        } else {
            // open description
            $(this).removeClass("open-description").addClass("close-description");
            $(this).removeClass("icon-chevron-right").addClass("icon-chevron-down");
            $(this).parent().parent().next().show();
        }
    });
});