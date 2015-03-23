jQuery(document).ready( function($) {
    $('.show_comp').click( function(event) {
        event.preventDefault();
        $(this).hide();
        $('#wp_comp_form').show(); 
    });
    
    $('#comp_form').submit( function( event ) {
        event.preventDefault();
        var data = $( this ).serialize();
        $.post( ajax_object.ajax_url, data, function( data ) {
            
        } );
    });
});