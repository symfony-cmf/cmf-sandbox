jQuery(document).ready(function($) {
    $('.hiddenfieldsToggle').click(function(event) {
        var context = $(this).closest('.hiddenfieldsContainer');
        $('.hiddenfields', context).show();
        $('.hiddenfieldsToggle', context).hide();
        $('.hiddenfieldsCloseCorner', context).show();
        $('.hiddenfieldsCloseButton', context).show();
    });

    $('.hiddenfieldsCloseCorner, .hiddenfieldsCloseButton').click(function(event) {
        var context = $(this).closest('.hiddenfieldsContainer');
        $('.hiddenfields', context).hide();
        $('.hiddenfieldsToggle', context).show();
        $('.hiddenfieldsCloseCorner', context).hide();
        $('.hiddenfieldsCloseButton', context).hide();
    });
});