jQuery(document).ready( function($) {
    $('.comp_man_add_row').click( function (event) {
        event.preventDefault();
        $('.comp_man_form_none').hide();
        $('#comp_man_form_inner thead').show();
        var fields = $('.comp_man_field_count').val();
        var newfields = parseInt( fields ) + 1;
        $('.comp_man_field_count').val(newfields);
        
        var rowHtml = '<tr class="row"><th>'+newfields+'</th><td><input type="text" placeholder="Field Name" name="comp_man_field_name['+newfields+']"></td><td><select name="comp_man_field_type['+newfields+']"><option value="-1" disabled selected>Select field type</option><option value="0">Single line text</option><option value="1">Multi line text</option><option value="2">Checkbox</option></select></td><th><input type="checkbox" value="1" name="comp_man_field_req['+newfields+']"></th><td><input type="number" placeholder="Order" name="comp_man_field_order['+newfields+']" value="0"></td><th><input class="del_field'+newfields+'" type="hidden" value="0" name="comp_man_field_del['+newfields+']"><a href="#" class="comp_man_field_remove" rel="'+newfields+'"><i class="fa fa-trash"></i></a></th></tr>';
        
        $('#comp_man_form_inner').append(rowHtml);
    });
    $(document).on('click', '.admin_export_entries', function(event) {
        event.preventDefault();
        var data = {
            action: 'wp_comp_man_export',
            comp: $(this).attr('data-comp')
        };
        var link = $(this);
        if( confirm( 'Do you want to export the entries for this competition?' ) ) {
            $(this).html('<i class="fa fa-circle-o-notch fa-spin"></i>').attr('disabled', 'disabled');
            $.post( ajax_object.ajax_url, data, function( response ) {
                console.log(response);
                link.attr('href', response).removeClass('admin_export_entries').addClass('button-primary').removeClass('button-secondary').html('<i class="fa fa-floppy-o"></i>').css('background-color', '#2ECCA2').css('border-color', '#17962C').removeAttr('disabled');
                var linka = document.createElement("a");
                linka.href = response;
                linka.click();
            } );
        }
    });
    $('.admin_pick_winner').click(function(event) {
        event.preventDefault();
        var data = {
            action: 'wp_comp_man_pick_winner',
            comp: $(this).attr('data-comp')
        };
        var loc = window.location.href;
        var winners = $(this).attr('data-winners');
        if( winners == 1 ) {
            var text = 'Are you sure you want to pick 1 winner for this competition?';
        } else {
            var text = 'Are you sure you want to pick '+winners+' winners for this competition?';
        }
        if( confirm( text ) ) {
            $.post( ajax_object.ajax_url, data, function( data ) {
                window.location.assign(loc);
            } );
        }
    });
});
jQuery(document).on('click', '.comp_man_field_remove', function(event) {
    event.preventDefault();
    var id = jQuery(this).attr('rel');
    var delfield = '.del_field'+id;
    var fields = jQuery('.comp_man_field_count').val();
    var newfields = parseInt( fields ) - 1;
    jQuery('.comp_man_field_count').val(newfields);
    if( newfields === 0 ) {
        jQuery('.comp_man_form_none').show();
        jQuery('#comp_man_form_inner thead').hide();
    }
    jQuery(this).closest('tr').css('visibility', 'hidden');
    jQuery(delfield).val(1);
});