function renderSortable()
{
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
      receive: function( event, ui ) {

      var new_semester = get_semester( event.target.attributes.id.nodeValue );
      var id = ui.item.context.id;
      var classes = ui.item.context.className;
      if(classes.includes("add-to-schedule"))
      {
        id = "new schedule";

        courseName = ui.item.context.id;

        $.ajax( {
          type: 'post',
          url: '/flowchart/add-course-to-Schedule',
          data: {
            courseName: courseName,
            semester: new_semester,
            id: id
          },
          success: function( data ) {
            var response = JSON.parse(data);
            console.log(response);
            ui.item.context.id = response[0];
            $( event.target ).children( '.credit_counter' ).children( '.credit_counter_num' ).text( 'CREDITS: ' + response[1]);
            ui.item.context.removeClass("add-to-schedule");
          }
        })
      }

      else
      {
        $.ajax( {
          type: 'post',
          url: '/flowchart/move-course',
          data: {
            semester: new_semester,
            id: id
          },
          success: function( data ) {
            var response = JSON.parse( data );
            console.log( response );
            $( event.target ).children( '.credit_counter' ).children( '.credit_counter_num' ).text( 'CREDITS: ' + response[0]);
            $( ui.sender[ 0 ] ).children( '.credit_counter' ).children( '.credit_counter_num' ).text( 'CREDITS: ' + response[1]);
          }
        })
      }
    }
  });
}
