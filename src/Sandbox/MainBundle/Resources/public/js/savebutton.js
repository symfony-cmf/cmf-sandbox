jQuery(document).ready(function($) {
    // catch the event saying the editable was opened
    // This doesn't catch NEW fields
    var saveTimestamp = new Date().getTime();
    var contentModified = false;
    var timeUpdateInterval = null;

    var that = this;

    function updateTimeText(text) {
        $('#saveStatus').text(text);
    }

    function contentModified() {
        console.log('content modified');
        contentModified = true;

        var secondsAgo = Math.round((new Date().getTime() - saveTimestamp) / 1000);
        updateTimeText("Last Save was <span time=\"" + saveTimestamp + "\">" + secondsAgo + "</span> seconds ago"); // Text should not be in JS!
    }

    function vieSavedSuccess() {
        updateTimeText("Saved!"); // Text should not be in JS!

        setTimeout(function() {
            if (!contentModified) {
                updateTimeText("All changes saved");
            }
        }, 5000);

        saveTimestamp = new Date().getTime();
    }

    function vieSavedStart() {
        timeUpdateInterval = null;
        updateTimeText("Saving..."); // Text should not be in JS!
        contentModified = false;
    }

    function removeHandlers() {
        $(this).unbind("hallodeactivated", that.removeHandlers);
        $(this).unbind("hallomodified", that.contentModified);
    }

    function addHandlers() {
        $(this).bind("hallodeactivated", that.removeHandlers);
        $(this).bind("hallomodified", that.contentModified);
        $(document).bind("vieSavedStart", that.vieSavedStart);
        $(document).bind("vieSavedSuccess", that.vieSavedSuccess);
    }

    $('[contenteditable]').bind("halloactivated", addHandlers);
});