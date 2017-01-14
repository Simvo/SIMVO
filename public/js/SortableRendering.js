function renderSortable()
{
  $( '.sortable' ).sortable( {
    connectWith: ".validPosition",
    items: "div.custom_card:not(.pinned)",
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
      items: "div.custom_card:not(.pinned)",
      placeholder: 'object_ouline hvr-pulse',
      cancel: '.credit_counter, .error_course_message, .pinned',
      receive: function( event, ui ) {
      var new_semester = get_semester( event.target.attributes.id.nodeValue );
      var vsbActiveSemesters = get_VSB_active_semesters();
      var id = ui.item.context.id;
      var classes = ui.item.context.className;
      var courseType = classes.split(" ")[1].split("_")[0];

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
            id: id,
            courseType: courseType,

          },
          success: function( data ) {
            mixpanel.track("Course Added to Schedule");

            var response = JSON.parse(data);
            ui.item.context.id = response[0];
            $('#'+ui.item.context.id).removeClass("add-to-schedule");
            $('#'+ui.item.context.id).find(".remove-course").attr("id", "remove_"+ui.item.context.id);
            $('#'+ui.item.context.id).find(".show-prereqs").attr("id", "show_prereqs_"+ui.item.context.id);



            $( event.target ).children( '.credit_counter' ).children( '.credit_counter_num' ).text( 'CREDITS: ' + response[1]);
            checkVSB(new_semester, ui.item.context.id, event.target);
            removeErrors(response[5]);
            checkIgnoredErrors();
            getErrors();

            for (var group in response[2])
            {
                if (response[2].hasOwnProperty(group))
                {
                    var groupProgress = response[2][group];
                    var target = $( "td[id='" + group + "']" ).text("" + groupProgress[0] + "/" + groupProgress[1]);
                }
            }
            refreshDeleteSemester();

            //Check if add complementary tutorial should start
            if($('#'+ui.item.context.id).hasClass('Required_course'))
            {
              var startTutorial = false;
              var reqgroups = $("#required-group-div").find("div.sortable");
              for(var i = 0; i < reqgroups.length; i++)
              {
                if($(reqgroups[i]).children().length != 0)
                {
                  startTutorial = false;
                  break;
                }
                else
                {
                    startTutorial = true;
                }
              }
              if(startTutorial)
              {
                $("#required-group-div").animate({'height': '0px'},{duration: 500, queue: false } );
                $("#required-group-div").animate({'padding-bottom': '0px'}, {duration: 500, queue: false });
                $("#required-group-div").animate({'opacity': 0}, {duration: 500, queue: false, complete: function (){$("#required-group-div").remove();}});
                startAddCourseTutorial();
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
            id: id,
          },
          success: function( data ) {
            mixpanel.track("Course Moved");

            var response = JSON.parse( data );
            $( event.target ).children( '.credit_counter' ).children( '.credit_counter_num' ).text( 'CREDITS: ' + response[0]);
            $( ui.sender[ 0 ] ).children( '.credit_counter' ).children( '.credit_counter_num' ).text( 'CREDITS: ' + response[1]);
            checkVSB(new_semester, ui.item.context.id, event.target);
            getErrors();
            checkIgnoredErrors();
            removeErrors(response[2]);
            refreshDeleteSemester();
          }
        })
      }
    }
  });
}
