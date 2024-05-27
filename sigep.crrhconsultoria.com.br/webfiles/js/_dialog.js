$("#add").click(function () {
    $("#add_form").dialog("open");
});
$("#add_form").dialog({
    autoOpen: false,
    position: {my: "top", at: "top", of: "#page-wrapper"},
    title: $('#add_form').attr('title') + name,
    draggable: true,
    resizable: false,
    modal: true,
    width: 1000,
    height: 500
});