$(document).ready(function(){

  $( '.sortable' ).sortable( {
    start: function( event, ui ) {
      is_dragging = true;
    },
    connectWith: ".sortable",
    stop: function( event, ui ) {
      is_dragging = false;
    }
  }).on( 'mousemove', function( e ){});

$( ".sortable" ).sortable( {
    placeholder: 'object_ouline hvr-pulse',
    cancel: '.credit_counter, .error_course_message',
  } );

  $( "#add-semester" ).click( function( e ) {

    e.preventDefault();
    var last_sem = $( "#course_schedule .semester:last .sortable" ).attr( "id" );
    last_sem = last_sem.split( " " );
    last_sem = last_sem[ 0 ] + " " + last_sem[ 1 ];

    var new_sem = get_semester_letter( get_next_semester( get_semester( last_sem ) ) );

    var new_semester = '<div class="semester">';
    new_semester += '<h5 style="text-align:center">' + new_sem + '</h5>';
    new_semester += '<div class="draggable">';
    new_semester += '<div class="sortable ' + new_sem.replace( " ", "" ) + '" id="' + new_sem + " " + new_sem.replace( " ", "" ) + '">';
    new_semester += '<div class="custom_card credit_counter" style="text-align:center;">';
    new_semester += '<div class="credit_counter_num" style="display: table-cell; vertical-align: middle; font-size:15px">';
    new_semester += 'CREDITS: 0';
    new_semester += '</div>';
    new_semester += '</div>';
    new_semester += '</div>';
    new_semester += '</div>';
    new_semester += '</div>';

    //$("#course_schedule").append(new_semester);
    $( ".semester + #add-semester-wrap" ).before( new_semester );

    $( "#course_schedule" ).sortable( { refresh: course_schedule });

  } );
});
