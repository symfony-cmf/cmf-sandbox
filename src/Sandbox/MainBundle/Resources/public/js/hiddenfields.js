jQuery(document).ready(function($) {
    $('.hiddenfieldsToggle').click(function(event) {
        var context = $(this).closest('.hiddenfieldsContainer');
        $('.hiddenfields', context).show();
        $('.hiddenfieldsToggle', context).hide();
        $('.hiddenfieldsClose', context).show();
    });

    $('.hiddenfieldsClose').click(function(event) {
        var context = $(this).closest('.hiddenfieldsContainer');
        $('.hiddenfields', context).hide();
        $('.hiddenfieldsToggle', context).show();
        $('.hiddenfieldsClose', context).hide();
    });
});