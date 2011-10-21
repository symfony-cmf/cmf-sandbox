jQuery(document).ready(function($) {
    var userBar = $('#userBar');
    if (!userBar) {
        return;
    }

    function show() {
        userBar.show();
    }

    function hide() {
        userBar.hide();
    }

    $('[contenteditable]').bind("halloactivated", show);
    $('[contenteditable]').bind("hallodeactivated", hide);
});