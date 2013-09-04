/* NOTES
 * SR - 2013-06-29
 * Link HTML Elements to JS code here
 * Refer to any element within the tag specified in 'el'
 * Specify link from element to function in 'events'
 * 
 * MP - 2013-07-06
 * Added separate function to save to database (saveData).
 * Renamed function to show JSON String from Local Storage to showJSON.
 */

usemockups.views.Page = Backbone.View.extend({
    el: "#designer-canvas",
    mockup_count: 0,

    initialize: function () {
        this.model.mockups.on("add", this.add_mockup, this);
        this.model.mockups.on("reset", this.render_mockups, this);
        this.model.on("change:width change:height", this.resize_document, this);
        this.render_mockups();
        this.footer = $("#designer-element-properties");
    },

    add_mockup: function (mockup, options) {

        var mockup_view = new (this.get_mockup_view(mockup.get("tool")))({
            model: mockup
        });
        
        this.$el.append(mockup_view.render(options).el);

        if (mockup.is_resizable())
            mockup_view.make_resizable();

        mockup_view.$el.attr("tabindex", this.mockup_count++);
        mockup_view.focus();

        return this;
    },
    
    render_mockups: function () {
        this.$el.empty();
        _.forEach(this.model.mockups.models, function (model) {
            this.add_mockup(model, {
                focus: false,
                show_property_dialog: false
            })
        }, this);
        this.model.mockups.off("reset");
    },

    resize_document: function () {
        this.$el
            .width(this.model.get("width"))
            .height(this.model.get("height"));
    },

    render: function () {

        this.resize_document();

        this.$el.droppable({
            accept: ".toolbox li",
            drop: function (event, ui) {
                
                var left =  ui.offset.left - this.$el.offset().left,
                    top = ui.offset.top - this.$el.offset().top,
                    tool_name = ui.draggable.data("tool");

                var mockup = new usemockups.models.Mockup({
                    top: top,
                    left: left,
                    tool: tool_name
                });

                this.model.mockups.add(mockup);

            }.bind(this)
        });

        this.$el.click(function (event) {
            if ($(event.target).is($("#designer-canvas"))) {
                this.footer.hide();
            }
        }.bind(this));

    },

    get_mockup_view: function (tool_name) {
        return usemockups.custom_mockup_views[tool_name] ||
               usemockups.views.Mockup;
    }
});

usemockups.views.Document = Backbone.View.extend({
    el: "body",

    events: {
        "click .export":    "export",
        "click .saveData":  "saveData",
    },

    initialize: function () {
        // Set window closing confirmation
        window.onbeforeunload = function (e) {
          var message = "Sind Sie sicher, dass Sie die Seite verlassen wollen?\n\n\Wenn Sie diese Seite verlassen, gehen ihre Änderungen verloren. Speichern Sie zuerst die Skizze, um die Änderungen zu behalten.",
          e = e || window.event;
          // For IE and Firefox
          if (e) {
            e.returnValue = message;
          }

          // For Safari
          return message;
        };        
    },

    render: function () {
        (new usemockups.views.Toolbox({
            model: usemockups.toolbox
        })).render();

        this.article = (new usemockups.views.Page({
            model: this.model
        }));
        this.article.render();
        
        // Force toolbox to expand to canvas height
        $("#designer-toolbox").css("height", $("#designer-canvas").height() );
    },

    export: function () {
        html2canvas([this.article.el], {
            onrendered: function(canvas) {
                window.open(canvas.toDataURL("image/png"));
            }
        });
    },
    
    // CHANGES MP
    saveData: function () {
       
        // Ask user to enter comment for versioning
        var comment = "";
        do {
            comment = prompt("Bitte geben Sie einen Kommentar ein zu dieser Version der Skizze.", "");
        } while (comment === "");
        
        if (comment.length > 0) {
            // SR - 2013-07-09: Where should this be placed to access from multiple js files?
            // GET parameter parsing
            function getParameterByName(name) {
                name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                    results = regex.exec(location.search);
                return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
            }

            var sketch_id = getParameterByName('sketch_id');
            
            
            // Store ID of the data in LocalStorage in order to clean up afterwards
            var LocalStorageID = this.model.localStorage.name + "-" + this.model.id;
            
            // SR - 2013-08-04:
            // Remove the internal model ID, it's not needed in the DB
            // Having a blank ID on load causes a new one to be generated,
            // so several version of the same sketch can be opened concurrently
            delete this.model.attributes.id;

            // SR - Save to DB, JQuery version
            $.post(
                '/sketch_api/save_to_db',
                {
                    'sketch_id': sketch_id,
                    'data': JSON.stringify(this.model),
                    'comment': comment
                },
                function(data) {
                    alert(data);
                    
                    // Clean up LocalStorage
                    localStorage.removeItem(LocalStorageID);
                    
                    // Redirect to sketch versions overview & prevent confirmation from appearing   
                    window.onbeforeunload = null;
                    window.location.href = '/project_view/sketch_versions/' + sketch_id;
                }
            );
        }
    }
});

/*
usemockups.views.DocumentEditForm = Backbone.View.extend({
    el: "nav #document-properties form",
    events: {
        "submit": "submit_form"
    },
    render: function () {
        this.$el.find("#id_title").val(this.model.get("title"));
        this.$el.find("#id_width").val(this.model.get("width"));
        this.$el.find("#id_height").val(this.model.get("height"));
    },
    submit_form: function () {
        this.model.set({
            "title": this.$el.find("#id_title").val(),
            "width": this.$el.find("#id_width").val(),
            "height": this.$el.find("#id_height").val()
        });
        this.model.save();
        this.hide();
        return false;
    },
    hide: function () {
        this.$el.parent().hide();
    }
});
*/