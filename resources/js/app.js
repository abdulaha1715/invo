require('./bootstrap');
window.$ = window.jQuery = require( "jquery" );
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


jQuery(document).ready(function($){
    setTimeout(() => {
        $('#status_message').slideUp('slow');
    }, 2000);

    // Task Filter Box
    $('#task_filter_btn').on('click', function(){
        var text = $(this).text();

        if(text == 'Filter'){
            $(this).text('Close Filter');
        }
        if(text == 'Close Filter'){
            $(this).text('Filter');
        }


        $('#task_filter').slideToggle('slow');
    });

});

CKEDITOR.replace('description');
