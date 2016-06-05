$(document).ready(function(){

  $( '.sortable' ).sortable( {
    start: function( event, ui ) {
      is_dragging = true;
    },
    connectWith: ".sortable",
    stop: function( event, ui ) {
      is_dragging = false;
    }
  } ).on( 'mousemove', function( e ) {
    if ( is_dragging ) {
      jsPlumb.repaintEverything();
    }
  } );

});
