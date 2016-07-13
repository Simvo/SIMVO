$(document).ready(function()
{
  renderSortable();
  initDeleteSemesterListener(".delete-semester");
  initAddSemesterListener(".add-semester");
  initRemoveCourseListener(".remove-course")
});

function initAddSemesterListener(target)
{
  event.stopImmediatePropagation();
  $(target).click(function(e){
    e.preventDefault();
    var last_sem = $(this).attr("id" ).substring(0 , $(this).attr("id" ).length - 4 );
    last_sem = last_sem.split( " " );
    last_sem = last_sem[ 0 ] + " " + last_sem[ 1 ];

    var new_sem = get_semester_letter( get_next_semester( get_semester( last_sem ) ) );
    var new_sem2 = get_semester_letter( get_next_semester( get_semester( new_sem ) ) );
    var new_sem2_class = new_sem2.split( " " );
    new_sem2_class = new_sem2_class[ 0 ] + new_sem2_class[ 1 ];

    if(new_sem.substring(0,6) == "SUMMER" && !$(".semester").find("div."+new_sem2_class).length)
    {
      //add add-button
      var new_semester = '<div class="fill-semester-gap-wrap">';
      new_semester += '<a href="#" id="' + last_sem + '-gap" class="add-semester mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab" style="background-color: #2980b9;"><i class="material-icons" style="color: white">add</i></a>';
      new_semester += '</div>';
      new_semester += '<div class="semester">';
      new_semester += '<h5 style="text-align:center">' + new_sem2 + '</h5>';
      new_semester += '<div class="draggable">';
      new_semester += '<div class="sortable validPosition ' + new_sem2.replace( " ", "" ) + '" id="' + new_sem2 + " " + new_sem2.replace( " ", "" ) + '">';
      new_semester += '<div class="custom_card credit_counter" style="text-align:center;">';
      new_semester += '<div class="credit_counter_num" style="display: table-cell; vertical-align: middle; font-size:15px">';
      new_semester += 'CREDITS: 0';
      new_semester += '</div>';
      new_semester += '</div>';
      new_semester += '</div>';
      new_semester += '</div>';
      new_semester += '<div class="delete-semester-wrap">';
      new_semester += '<a href="#" style="opacity:0;" id="' + new_sem2 + '-delete" class="delete-semester mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab"><i class="material-icons">clear</i></a>';
      new_semester += '</div>';
      new_semester += '</div>';

      $(this).parent().before( new_semester );
      renderSortable();

      //add delete listener to new semester
      initDeleteSemesterListener("[id='"+new_sem2+"-delete']");
      initAddSemesterListener("[id='"+last_sem+"-gap']");




      //check if the next semester exists
      test_sem = new_sem2
      test_sem = test_sem.split( " " );
      test_sem = test_sem[ 0 ] + " " + test_sem[ 1 ];
      var check_sem = formatSemesterID( get_semester_letter( get_next_semester( get_semester( new_sem2 ) ) ) );

      //if the next semester exists then we dont need the button!
      if($("[id='"+check_sem+"']").length)
      {
        $(this).parent().remove();
      }
      else
      {
        //update the gap
        var newAddButton = '<div class="fill-semester-gap-wrap">';
        newAddButton += '<a href="#" id="' + new_sem2 + '-gap" class="add-semester mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab" style="background-color: #2980b9;"><i class="material-icons" style="color: white">add</i></a>';
        newAddButton += '</div>';
        $(this).parent().before( newAddButton );
        initAddSemesterListener("[id='"+new_sem2+"-gap']")
        $(this).parent().remove();
      }

    }
    else
    {
      var new_semester = '<div class="semester">';
      new_semester += '<h5 style="text-align:center">' + new_sem + '</h5>';
      new_semester += '<div class="draggable">';
      new_semester += '<div class="sortable validPosition ' + new_sem.replace( " ", "" ) + '" id="' + new_sem + " " + new_sem.replace( " ", "" ) + '">';
      new_semester += '<div class="custom_card credit_counter" style="text-align:center;">';
      new_semester += '<div class="credit_counter_num" style="display: table-cell; vertical-align: middle; font-size:15px">';
      new_semester += 'CREDITS: 0';
      new_semester += '</div>';
      new_semester += '</div>';
      new_semester += '</div>';
      new_semester += '</div>';
      new_semester += '<div class="delete-semester-wrap">';
      new_semester += '<a href="#" style="opacity:0;" id="' + new_sem + '-delete" class="delete-semester mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab"><i class="material-icons">clear</i></a>';
      new_semester += '</div>';
      new_semester += '</div>';

      //$("#course_schedule").append(new_semester);
      $(this).parent().before( new_semester );
      renderSortable();

      //add delete listener to new semester
      initDeleteSemesterListener("[id='"+new_sem+"-delete']");

      //check if the next semester exists
      var test_sem = new_sem;
      test_sem = test_sem.split( " " );
      test_sem = test_sem[ 0 ] + " " + test_sem[ 1 ];
      var check_sem = formatSemesterID( get_semester_letter( get_next_semester( get_semester( new_sem ) ) ) );

      //if the next semester exists then we dont need the button!
      if($("[id='"+check_sem+"']").length)
      {
        $(this).parent().remove();
      }
      else
      {
        var newAddButton = '<div class="fill-semester-gap-wrap">';
        newAddButton += '<a href="#" id="' + new_sem + '-gap" class="add-semester mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab" style="background-color: #2980b9;"><i class="material-icons" style="color: white">add</i></a>';
        newAddButton += '</div>';
        $(this).parent().before( newAddButton );
        initAddSemesterListener("[id='"+new_sem+"-gap']")
        $(this).parent().remove();
      }
    }
  });

}



function initDeleteSemesterListener(target)
{
  $(target).animate({opacity: 1}, 300);
  $(target).click(function(e){
    e.preventDefault();

    var target_sem = $(this).attr("id" ).substring(0 , $(this).attr("id" ).length - 7 );
    target_sem = target_sem.split( " " );
    target_sem = target_sem[ 0 ] + " " + target_sem[ 1 ];
    var CourseCount = $(this).parent().parent().find("div.custom_card").length - 1;
    var prev_sem = get_semester_letter( get_previous_semester( get_semester(target_sem)));
    var next_sem = get_semester_letter( get_next_semester( get_semester(target_sem)));

    deleteSemester(prev_sem, target_sem, next_sem);

  });
}

function deleteSemester(prev_sem, target_sem, next_sem)
{
  //Four cases:
  //1. both prev and next exist (YES)
  //2. prev DNE and next exists (NO)
  //3. prev exists and next DNE (NO)
  //4. both prev and next DNE (NO)
  var prevID = formatSemesterID(prev_sem);
  var nextID = formatSemesterID(next_sem);

  if($("[id='"+prevID+"']").length && $("[id='"+nextID+"']").length)
  {
    //add add-button
    var add_button = '<div class="fill-semester-gap-wrap">';
    add_button += '<a href="#" id="' + prev_sem + '-gap" class="add-semester mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab" style="background-color: #2980b9;"><i class="material-icons" style="color: white">add</i></a>';
    add_button += '</div>';
    $("[id='"+target_sem+"-delete']").parent().parent().before(add_button);
    initAddSemesterListener("[id='"+prev_sem+"-gap']");
  }
  else if ($("[id='"+prevID+"']").length && !$("[id='"+nextID+"']").length)
  {
    //update the gap starting point
    $("[id='"+target_sem+"-gap']").attr("id",prev_sem+"-gap");
  }
  else if(!$("[id='"+prevID+"']").length && !$("[id='"+nextID+"']").length)
  {
    //remove the later gap button entirely
    $("[id='"+target_sem+"-gap']").parent().remove();
  }

  $("[id='"+target_sem+"-delete']").parent().parent().remove();
}

function refreshDeleteSemester()
{
  for( i = 2; i < $(".semester").length; i++ )
  {
    if(isSemesterEmpty($(".semester")[i]))
    {
      //not empty
      if($($(".semester")[i]).find("div.delete-semester-wrap").length)
      {
        var target_sem = $($(".semester")[i]).find("h5").html();
        $("[id='"+target_sem+"-delete']").animate({opacity:0},300,"linear", function (){$(this).parent().remove();});
        //$($(".semester")[i]).find("div.delete-semester-wrap").remove();
      }
    }
    else
    {
      //empty -- append delete
      if(!$($(".semester")[i]).find("div.delete-semester-wrap").length && !$($(".semester")[i]).find("div.complementary_area").length)
      {
        var target_sem = $($(".semester")[i]).find("h5").html();
        var deleteButton = '<div class="delete-semester-wrap" >';
        deleteButton += '<a href="#" style="opacity:0;" id="' + target_sem + '-delete" class="delete-semester mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab"><i class="material-icons">clear</i></a>';
        deleteButton += '</div>';

        $($(".semester")[i]).append(deleteButton);

        initDeleteSemesterListener("[id='"+target_sem+"-delete']");
      }
    }
  }
}

// Add Complentary Course

  $(".add_comp_course_button").click(function()
  {
    var selected = [];


    $(".complentary_table_body tr").each(function()
    {
      if ($(this).hasClass('is-selected'))
      {
        var new_class = [];

        selected.push([$(this).find('td.course_number').text(), $(this).find('td.class_name').text()]);
        $(this).remove();
      }
    });

    for (var i = 0; i < selected.length; i++)
    {

      $.ajax({
        type: "post",
        url: "/flowchart/add_complementary_course_to_Flowchart",
        data: {
          semester: "complementary_course",
          id: 'new schedule',
          courseName: selected[i][0],
        },
        success: function(data) {
          var response = JSON.parse(data);
          console.log(response);

          if (response === 'Error')
          {
            //error handler
          }
          else
          {
            var comp_course = "<div class='custom_card Complementary_course add-to-schedule' id='" + response['SUBJECT_CODE'] + " " + response['COURSE_NUMBER'] + "'>";
            comp_course += "<div class='card_content'>";
            comp_course += response['SUBJECT_CODE'] + " " + response['COURSE_NUMBER'];
            comp_course += "<button id='menu_for_" + response['SUBJECT_CODE'] + " " + response['COURSE_NUMBER']  + "' class='mdl-button mdl-js-button mdl-button--icon'>";
            comp_course += "<i class='material-icons'>arrow_drop_down</i>";
            comp_course += "</button>" + response['COURSE_CREDITS'];
            comp_course += "<ul class='mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect' for='menu_for_" + response['SUBJECT_CODE'] + " " + response['COURSE_NUMBER'] + "''>";
            comp_course += "<li class='mdl-menu__item show-prereqs' id='show_prereqs_" + response['SUBJECT_CODE'] + "_" + response['COURSE_NUMBER'] + "'>Show Pre-Requisites</li>";
            comp_course += "<li class='mdl-menu__item remove-course' id='remove_" + response['SUBJECT_CODE'] + "_" + response['COURSE_NUMBER'] + "'>Remove</li>";
            comp_course += "</ul>";
            comp_course += "</div>";
            comp_course += "</div>";

            $(".complementary_area .sortable").append(comp_course);

            //Dynamically render MDL
            componentHandler.upgradeDom();
            initRemoveCourseListener("#remove_"+ response['SUBJECT_CODE'] + "_" + response['COURSE_NUMBER']);
          }
        }
      })
    }

    $('#comp_courses').foundation('reveal', 'close');

  });

  //add elective course!

    $(".add_elec_course_button").click(function() {
      var selected = [];


      $(".elective_table_body tr").each(function() {

        if ($(this).hasClass('is-selected')) {
          var new_class = [];

          selected.push([$(this).find('td.course_number').text(), $(this).find('td.class_name').text()]);
          $(this).remove();
        }
      });


      for (var i = 0; i < selected.length; i++)
      {
        $.ajax({
          type: "post",
          url: "/flowchart/add_complementary_course_to_Flowchart",
          data: {
            semester: "Elective_course",
            id: 'new schedule',
            courseName: selected[i][0],
          },
          success: function(data) {
            var response = JSON.parse(data);

            if (response === 'Error')
            {
              //error handler
            }
            else
            {
              var comp_course = "<div class='custom_card Elective_course add-to-schedule' id='" + response['SUBJECT_CODE'] + " " + response['COURSE_NUMBER'] + "'>";
              comp_course += "<div class='card_content'>";
              comp_course += response['SUBJECT_CODE'] + " " + response['COURSE_NUMBER'];
              comp_course += "<button id='menu_for_" + response['SUBJECT_CODE'] + " " + response['COURSE_NUMBER']  + "' class='mdl-button mdl-js-button mdl-button--icon'>";
              comp_course += "<i class='material-icons'>arrow_drop_down</i>";
              comp_course += "</button>" + response['COURSE_CREDITS'];
              comp_course += "<ul class='mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect' for='menu_for_" + response['SUBJECT_CODE'] + " " + response['COURSE_NUMBER'] + "''>";
              comp_course += "<li class='mdl-menu__item show-prereqs' id='show_prereqs_" + response['SUBJECT_CODE'] + "_" + response['COURSE_NUMBER'] + "'>Show Pre-Requisites</li>";
              comp_course += "<li class='mdl-menu__item remove-course' id='remove_" + response['SUBJECT_CODE'] + "_" + response['COURSE_NUMBER'] + "'>Remove</li>";
              comp_course += "</ul>";
              comp_course += "</div>";
              comp_course += "</div>";

              $(".elective_area .sortable").append(comp_course);

              //Dynamically render MDL
              componentHandler.upgradeDom();
              initRemoveCourseListener("#remove_"+ response['SUBJECT_CODE'] + "_" + response['COURSE_NUMBER']);
            }
          }
        })
      }


      $('#comp_courses').foundation('reveal', 'close');
    });


    function initRemoveCourseListener(target)
    {
      $(target).click(function(e){
        e.preventDefault();

        if($(this).parent().parent().parent().parent().hasClass("add-to-schedule"))
        {
          //courses that have NOT been added to the schedule have no need for a database call
          $(this).parent().parent().parent().parent().remove();
        }
        else
        {
          var courseID = $(this).attr("id").substring(7, $(this).attr("id").length);
          //delete from database
          $.ajax({
            type: "delete",
            url: "/flowchart/delete_course_from_schedule",
            data: {
              id: courseID,
            },
            success: function(data) {
              var response = JSON.parse(data);
              if (response === 'Error')
              {
                //error handler
              }
              else
              {
                var semester = get_semester_letter(response[3]);
                semester = semester.split(" ");
                semester = semester[0] + semester[1];
                $("#" + response[0]).remove();
                $("."+semester).children( '.credit_counter' ).children( '.credit_counter_num' ).text( 'CREDITS: ' + response[1]);


                for (var group in response[2])
                {
                    if (response[2].hasOwnProperty(group))
                    {
                        var groupProgress = response[2][group];
                        var target = $( "td[id='" + group + "']" ).text("" + groupProgress[0] + "/" + groupProgress[1]);
                    }
                }
                refreshDeleteSemester();
              }
            }
          });




        }

      });
    }
