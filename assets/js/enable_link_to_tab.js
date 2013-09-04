/*
 * thanks to "flynfish" at stackoverflow
 * http://stackoverflow.com/questions/7862233/twitter-bootstrap-tabs-go-to-specific-tab-on-page-reload
 */

// Javascript to enable link to tab
var hash = document.location.hash;
var prefix = "tab_";
if (hash) {
    $('.nav-tabs a[href='+hash.replace(prefix,"")+']').tab('show');
}

//Doesn't work properly, because we'd need the hashtag after a slash.
//
//Change hash for page-reload
//$('.nav-tabs a').on('shown', function (e) {
//    window.location.hash = e.target.hash.replace("#", "#" + prefix);
//});