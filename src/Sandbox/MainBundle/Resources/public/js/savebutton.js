jQuery(document).ready(function($) {
    var saveTimeoutSet = false;

    function updateButton(action) {
        switch (action) {
            case 'savenow':
                $('.saveButton').html("Save now");
                $('.saveButton').addClass('saveButtonActive');
            break;

            case 'saved':
                $('.saveButton').html("Saved");
                $('.saveButton').removeClass('saveButtonActive');
            break;

            case 'saving':
                $('.saveButton').html("Saving...");
                $('.saveButton').removeClass('saveButtonActive');
            break;
        }
    }

    function doUpdateButton(event) {
        updateButton(event.data.action);
    }

    function contentChanged() {
        updateButton('savenow');

        if (!saveTimeoutSet) {
            setTimeout(function() {
                vieSaveContent();
                saveTimeoutSet = false;
            }, 5000);
            saveTimeoutSet = true;
        }
    }

    function removeHandlers() {
        $(this).unbind("hallodeactivated", removeHandlers);
        $(this).unbind("hallomodified", contentChanged);
    }

    function addHandlers() {
        $(this).bind("hallodeactivated", removeHandlers);
        $(this).bind("hallomodified", contentChanged);
        $(document).bind("vieSaveStart", {"action": "saving"}, doUpdateButton);
        $(document).bind("vieSaveSuccess", {"action": "saved"}, doUpdateButton);

    }

    // This doesn't catch NEW fields
    $('[contenteditable]').bind("halloactivated", addHandlers);
    $('.saveButton').live('click', vieSaveContent);
});
