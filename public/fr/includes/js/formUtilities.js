function popularForm(frm, data) {
    $.each(data, function(key, value) {
        var ctrl = $('[name='+key+']', frm);
        switch(ctrl.prop("type")) {
            case "radio": case "checkbox":
                ctrl.each(function() {
                    if($(this).attr('value') == value) $(this).attr("checked",value);
                });
            break;
            case "file":
               /// não faz nada pois é input file
                break;
            default:
                    ctrl.val(value);
        }
    });
}

function limpaForm(frm) {
    $(frm+" :input").each(function(){
        var input = $(this);
        if(input.attr('name')  != '_token')
        {
            input.val('');
            input.removeClass('is-invalid');
        }

    });

    $('.invalid-feedback').html('');
    $(frm+" :input[type=radio]").prop("checked", false);
}
