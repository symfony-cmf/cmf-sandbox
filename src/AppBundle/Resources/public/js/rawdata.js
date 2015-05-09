$(document).ready(function () {
    $("#raw_xml").on("click", function () {
        $.ajax({
           "dataType": "xml",
            "success": function(xml) {
                $("#overlay_content").text((new XMLSerializer()).serializeToString(xml));
                $("#raw_data_overlay").show();
            }
        });
    });
    $("#raw_json").on("click", function () {
        $.ajax({
            "dataType": "json",
            "success": function(json) {
                $("#overlay_content").text(JSON.stringify(json, undefined, 4));
                $("#raw_data_overlay").show();
            }
        });
    });
});

$(document).keyup(function(e) {
    if (e.keyCode == 27) { $("#raw_data_overlay").hide(); }   // esc
});
