jQuery(document).ready(function($) {
    var saveTimestamp = new Date().getTime();
    var contentModified = false;
    var timeUpdateInterval = null;
    var saveTimeoutSet = false;

    function updateTimeText(html) {
        $('#saveStatus').html(html);
    }

    function convertTimestampToHTML5(timestamp) {
        function addLeadingZero(nr) {
            if (nr.toString().length == 1) {
                nr = "0" + nr;
            }

            return nr;
        }

        var dateObject = new Date(timestamp);

        var returnValue = dateObject.getFullYear();
        returnValue += "-";
        returnValue += addLeadingZero(dateObject.getMonth() + 1);
        returnValue += "-";
        returnValue += addLeadingZero(dateObject.getDate());
        returnValue += "T";
        returnValue += addLeadingZero(dateObject.getHours());
        returnValue += ":";
        returnValue += addLeadingZero(dateObject.getMinutes());
        returnValue += ":";
        returnValue += addLeadingZero(dateObject.getSeconds());

        var timezoneOffset = dateObject.getTimezoneOffset()
        if (timezoneOffset === 0) {
            returnValue += "Z";
        } else {
            if (timezoneOffset >= 1) {
                returnValue += "+";
            } else {
                returnValue += "-";
                timezoneOffset = timezoneOffset * -1;
            }

            returnValue += addLeadingZero(timezoneOffset / 60);
            returnValue += ":";
            returnValue += addLeadingZero(timezoneOffset % 60);
        }
        console.log(returnValue);
        return returnValue;
    }

    function contentChanged() {
        contentModified = true;

        if (saveTimestamp == 0) {
            saveTimestamp = new Date().getTime();
        }

        var secondsAgo = Math.round((new Date().getTime() - saveTimestamp) / 1000);

        var dateObject = new Date(saveTimestamp);
        updateTimeText("Last saved <time datetime=\"" + convertTimestampToHTML5(saveTimestamp) + "\">a few seconds ago</time> <button class=\"saveButton\">Save Now</button>"); // Text should not be in JS!

        if (!saveTimeoutSet) {
            setTimeout(function() {
                vieSaveContent();
                saveTimeoutSet = false;
            }, 5000);
            saveTimeoutSet = true;
        }
    }

    function vieSaveSuccess() {
        updateTimeText("Saved!"); // Text should not be in JS!

        setTimeout(function() {
            if (!contentModified) {
                updateTimeText("All changes saved");
            }
        }, 5000);

        saveTimestamp = 0;
    }

    function vieSaveStart() {
        timeUpdateInterval = null;
        updateTimeText("Saving..."); // Text should not be in JS!
        contentModified = false;
    }

    function removeHandlers() {
        $(this).unbind("hallodeactivated", removeHandlers);
        $(this).unbind("hallomodified", contentModified);
    }

    function addHandlers() {
        $(this).bind("hallodeactivated", removeHandlers);
        $(this).bind("hallomodified", contentChanged);
        $(document).bind("vieSaveStart", vieSaveStart);
        $(document).bind("vieSaveSuccess", vieSaveSuccess);

    }

    // This doesn't catch NEW fields
    $('[contenteditable]').bind("halloactivated", addHandlers);
    $('.saveButton').live('click', vieSaveContent);
    $('#saveStatus').click(function() {
        if ($(this).find('.saveButton').length < 1) {
            $('#saveStatus').append(' <button class=\"saveButton\">Save Now</button>');
        }
    });
});
