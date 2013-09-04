<?php
if (isset($error_message)) {
    ?>
<div id="bc">
    <div class="container">
        <div class="row">
            <div class="span12">
                <a href="/home"><i class="icon-home"></i></a>
            </div>
        </div>
    </div>
</div>
<br />
<div class="container">
    <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
</div>
    <?php
} else {
    ?>
<div id="bc">
    <div class="container">
        <div class="row">
            <div class="span12">
                <a href="/home"><i class="icon-home"></i></a> / <a href="/dashboard">Meine Projekte</a> / <a href="/project_view/<?php echo $project['id']; ?>"><?php echo htmlspecialchars($project['name']); ?></a> / <a href="/project_view/<?php echo $project['id']; ?>#tab_lB">Skizzen</a> / <a href="/project_view/sketch_versions/<?php echo $sketch['id']; ?>">Skizzen Versionen</a> / <a href="/designer?sketch_id=<?php echo $sketch['id']; ?><?php if ($sketch['version']) { echo '&sketch_version=' . $sketch['version']; } ?>"><?php echo htmlspecialchars($sketch['name']); ?></a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row-fluid">
        <h1><?php echo htmlspecialchars($sketch['name']); ?> <div class="pull-right"><a class="export btn margin-right">Exportieren</a><a class="saveData btn btn-primary">Speichern</a></div></h1>
    </div>
    <div class="row-fluid">
        <div id="designer-toolbox" class="span2"></div>
        
        <div class="span10">
            <div class="row">
                <div id="designer-canvas" class="pull-right"></div>
            </div>
        </div>
        
    </div>
    <div class="row-fluid">
        <div id="designer-element-properties" class="span12 clearfix">
            <h2>Eigenschaften</h2>
            <form id="designer-element-properties-form"></form>
        </div>
    </div>
    
</div>

<!-- templates -->

<!-- Properties box for each element -->
<script type="text/html" id="property-form-template">
<% for (var attribute in attributes) { %>
    <% if (!attributes[attribute].hidden) { %>
    <div class="line <%= attributes[attribute].type %>">
    <label for="id_<%= attributes[attribute].name %>"><%= attributes[attribute].displayname %></label>

    <% if (attributes[attribute].type == "boolean") { %>
        <input type="checkbox" id="id_<%= attributes[attribute].name %>"
           name="<%= attributes[attribute].name %>"
           <% if (attributes[attribute].value) { %> checked="checked" <% } %> />
    <% } else if (attributes[attribute].type == "string") { %>
        <textarea id="id_<%= attributes[attribute].name %>"
           name="<%= attributes[attribute].name %>"><%= attributes[attribute].value %></textarea>
    <% } else { %>
        <input type="text" id="id_<%= attributes[attribute].name %>"
           name="<%= attributes[attribute].name %>"
           value="<%= attributes[attribute].value %>" />
    <% } %>
    </div>
    <% } %>     
<% } %>
    <div class="pull-right tools">
        <a class="btn send-to-back" href="#">Nach hinten</a>
        <a class="btn bring-to-front" href="#">Nach vorne</a>
        <a class="btn delete" href="#" title="Tastaturkürzel: DELETE">L&ouml;schen</a>
    </div>
</script>

<!-- Rendering of GUI Elements is defined here -->
<script type="text/html" id="button-template">
     <div class="button" data-attribute="text"><%= text %></div>
</script>

<script type="text/html" id="checkbox-template">
    <div class="checkbox <%= checked ? 'checked' : '' %>"></div>
    <span data-attribute="text"><%= text %></span>
</script>

<script type="text/html" id="radio-template">
    <div class="radio <%= checked ? 'checked' : '' %>"></div>
    <span data-attribute="text"><%= text %></span>
</script>

<script type="text/html" id="text-template">
    <div class="text-input">
    <% if (label) { %>
    <label data-attribute="text"><%= text %></label>
    <% } %>
    <div class="input"></div>
    </div>
</script>

<script type="text/html" id="heading-template">
    <h1 class="heading" data-attribute="text" style="font-size: <%= size %>px; color: <%= color %>"><%= text %></h1>
</script>

<script type="text/html" id="paragraph-template">
    <div class="paragraph" style="background-color: <%= background %>">
        <p data-attribute="text"><%= new String(text).replace(/\n/g, '<br />') %></p>
    </div>
</script>

<script type="text/html" id="label-template">
    <strong data-attribute="text" style="color: <%= color %>; font-size: <%= size %>px;"><%= text %></strong>
</script>

<script type="text/html" id="combobox-template">
    <div class="combobox">
        <span class="text" data-attribute="text"><%= text %></span>
        <span class="opener"></span>
    </div>
</script>

<script type="text/html" id="table-template">
    <table class="designer-table">
        <% for (var i=0; i < rows; i++ ) { %>
            <tr>
                <% for (var j=0; j < columns; j++ ) { %>
                    <td class="designer-td">
                        <input type="text" data-row="<%= i %>" data-column="<%= j %>" value="<%=  values[i] ? values[i][j] : ''  %>" />
                    </td>
                <% } %>
            </tr>
        <% } %>
    </table>
</script>

<script type="text/html" id="image-template">
    <svg class="image" xmlns="http://www.w3.org/2000/svg" version='1.1'
         style=" width:100%; height:100%;" preserveAspectRatio="none" viewBox="0 0 100 100">
        <line vector-effect="non-scaling-stroke" fill="none" stroke="#dedede" stroke-width="2" x1="0" y1="100" x2="100" y2="0"></line>
        <line vector-effect="non-scaling-stroke" fill="none" stroke="#dedede" stroke-width="2" x1="0" y1="0" x2="100" y2="100"></line>
    </svg>
</script>

<script type="text/html" id="shape-template">
    <div class="shape" style="background-color: <%= color %>; <%= border ? '' : 'border:none' %>"></div>
</script>

<!-- END of GUI elements -->

<script src="assets/js/designer/libs/jquery.min.js"></script>
<script src="assets/js/designer/libs/jquery-ui.min.js"></script>
<script src="assets/js/designer/libs/underscore-min.js"></script>
<script src="assets/js/designer/libs/backbone-min.js"></script>
<script src="assets/js/designer/libs/html2canvas.min.js"></script>
<script src="assets/js/designer/persistance/local-storage.js"></script>
<script src="assets/js/colourpicker_spectrum.js"></script>

<script type="text/javascript">
    // namespace definition
    var usemockups = {
        models: {},
        collections: {},
        routers: {},
        views: {},
        fixtures: {},
        toolbox: []
    }
</script>

<script src="assets/js/designer/fixtures/demo-document.js"></script>
<script src="assets/js/designer/fixtures/toolbox.js"></script>
<script src="assets/js/designer/routers/document.js"></script>
<script src="assets/js/designer/models/document.js"></script>
<script src="assets/js/designer/models/mockup.js"></script>
<script src="assets/js/designer/models/toolbox.js"></script>
<script src="assets/js/designer/views/document.js"></script>
<script src="assets/js/designer/views/toolbox.js"></script>
<script src="assets/js/designer/views/mockup.js"></script>
<script src="assets/js/designer/views/properties.js"></script>

<script type="text/javascript">
    usemockups.toolbox = new usemockups.collections.Toolbox(usemockups.fixtures.toolbox);

    usemockups.custom_mockup_views = {
        "table": usemockups.views.TableMockup
    };

    $(function () {
        var documents = new usemockups.collections.Documents();
        
        var router = new usemockups.routers.Document({
            documents: documents
        });

        Backbone.history.start({
            pushState: false // !!(window.history && window.history.pushState)
        });

    });
</script>

<?php
}
?>