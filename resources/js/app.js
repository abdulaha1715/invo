require('./bootstrap');
window.$ = window.jQuery = require( "jquery" );
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


jQuery(document).ready(function($){
    setTimeout(() => {
        $('#status_message').slideUp('slow');
    }, 2000);
});

CKEDITOR.replace('description');
