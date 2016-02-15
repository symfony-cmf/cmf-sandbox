jQuery(function ($) {

    var $rawData = $('#raw-data');
    var $modal = $('#raw-data-modal');

    $('.raw-data-link').click(function (e) {
        var format = $(this).data('format');

        $modal.modal('show');
        var i = 0;
        var loaderIcon =setInterval(function () {
            $rawData.append('.');
            if (i++ == 10) $rawData.text('.');
        }, 250);

        $.ajax({
            dataType: format,
            success: function (data) {
                var str = '';
                if ('xml' === format) {
                    str = (new XMLSerializer()).serializeToString(data);
                } else if ('json' === format) {
                    str = JSON.stringify(data, undefined, 4);
                }

                $rawData.text(str);
                clearInterval(loaderIcon);
            }
        });
    });

});
