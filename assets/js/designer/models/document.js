usemockups.models.Document = Backbone.Model.extend({
    localStorage: new Backbone.LocalStorage('documents'),
    defaults: {
        "mockups": [],
        "width": 960,
        "height": 600
    },
    initialize: function () {
        this.mockups = new usemockups.collections.Mockups;
        
        // MP - 15.07.2013:
        // "add remove change persist" instead of "add remove persist"
        // Fixed a bug where the properties of a newly created element did not get updated
        // in JSON / local storage.
        this.mockups.on("add remove change persist", this.persist, this);
    },
    persist: function () {
        this.set("mockups", this.mockups.toJSON());
        this.save();
    },
    parse: function (result) {
        if (this.mockups && !this.mockups.length)
            this.mockups.reset(result.mockups);
        return result;
    }

});

usemockups.collections.Documents = Backbone.Collection.extend({
    model: usemockups.models.Document,
    localStorage: new Backbone.LocalStorage('documents')

});