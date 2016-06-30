function renderSortable()
{
  $( '.sortable' ).sortable( {
    connectWith: ".validPosition",
    start: function( event, ui ) {
      is_dragging = true;
    },
    connectWith: ".sortable",
    stop: function( event, ui ) {
      is_dragging = false;
    }
  }).on( 'mousemove', function( e ){});

  $( ".sortable" ).sortable( {
      connectWith: ".validPosition",
      placeholder: 'object_ouline hvr-pulse',
      cancel: '.credit_counter, .error_course_message',
      receive: function( event, ui ) {

      var new_semester = get_semester( event.target.attributes.id.nodeValue );
      var vsbActiveSemesters = get_VSB_active_semesters();
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
            ui.item.context.id = response[0];
            $('#'+ui.item.context.id).removeClass("add-to-schedule");
            $( event.target ).children( '.credit_counter' ).children( '.credit_counter_num' ).text( 'CREDITS: ' + response[1]);

            for (var group in response[2])
            {
                if (response[2].hasOwnProperty(group))
                {
                    var groupProgress = response[2][group];
                    var target = $( "td[id='" + group + "']" ).text("" + groupProgress[0] + "/" + groupProgress[1]);
                }
            }

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

      console.log(new_semester);
      console.log(vsbActiveSemesters);
      // Check for VSB warnings
      if(new_semester === vsbActiveSemesters[0] || new_semester === vsbActiveSemesters[1])
      {
        $.ajax({
          type: 'post',
          url: '/flowchart/check-course-availability',
          data: {
            semester: new_semester,
            courseName: courseName
          },
          success: function(data){
            var response = JSON.parse(data);
          }
        });
      }
    }
  });
}
