usemockups.routers.Document = Backbone.Router.extend({
    routes: {
        "document/:id": "get_document",
        "": "index"
    },
    initialize: function (options) {
        this.documents = options.documents;
    },

    /**
     * @author SR
     * @description Loads the sketch data in JSON notation from the database
     */        
    index: function () {
        var doc = new usemockups.models.Document();
       
        // SR - 2013-07-09: Where should this be placed to access from multiple js files?
        // GET parameter parsing
        function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        }
        
        var sketch_id = getParameterByName('sketch_id');
        var sketch_version = getParameterByName('sketch_version');
        
        var url_params = "sketch_id=" + sketch_id;
        if (sketch_version) {
            url_params += "&sketch_version=" + sketch_version;
        }
        
        // Load sketch data from DB with a synchronous XmlHttpRequest call
        var sketch_data = $.ajax({
            type: "GET",
            url: "/sketch_api/load_data?" + url_params,
            cache: false,
            async: false
        }).responseText;
        
        // Check if we're dealing with a new sketch without any data yet
        if (sketch_data === "CREATE_NEW") {
            this.create_new_document();
        } else {
            // Try loading the retrieved data
            try {
             doc.save(JSON.parse(sketch_data), {
                 success: function (model) {
                     this.documents.add(model, { silent: true });
                     this.get_document(model.get("id"));
                 }.bind(this)
             });
            } catch (err) {
                 alert("Skizze konnte nicht geladen werden.\nFehlermeldung:\n\n " + err);
            }
        }
    },
    
    create_new_document: function () {
        var demo_document = new usemockups.models.Document();
        demo_document.save(usemockups.fixtures.demo_document, {
            success: function (model) {
                this.documents.add(model, { silent: true });
                this.get_document(model.get("id"));
            }.bind(this)
        });
    },
    
    get_document: function (document_id) {
        var document = new usemockups.models.Document({
            "id": document_id
        });

        document.fetch();

        if (usemockups.active_document_view) {
            usemockups.active_document_view.undelegateEvents();
            usemockups.active_document_view.article.undelegateEvents();
            usemockups.active_document_view.edit_form.undelegateEvents();
        }

        if (usemockups.active_property_dialog) {
            usemockups.active_property_dialog.hide();
        }

        usemockups.active_document_view = new usemockups.views.Document({
            model: document
        });

        usemockups.active_document_view.render();
    }
});