$(document).ready(function()
{
  renderSortable();
  initAddCompCourseButton();
  initDeleteSemesterListener(".delete-semester");
  initAddSemesterListener(".add-semester");
  initRemoveCourseListener(".remove-course");
  initComplementaryModalRevealListener(".semester-add-comp-course-button");
});

function startAddCourseTutorial()
{
   $('#add_course_tutorial').foundation('reveal','open');
}

function initComplementaryModalRevealListener(target)
{
  $(target).click(function(e){
    $($("#course_schedule").find($("a.Complementary_Add_Target"))).removeClass("Complementary_Add_Target");
    $(this).addClass("Complementary_Add_Target");
  });
}

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
      new_semester += '<h5 style="text-align:center" class="semester-header" id="' + new_sem2.replace( " ", "" ) + '_header">' + new_sem2 + '</h5>';
      if($("#required-group-div").length == 0)
      {
        new_semester += '<a href="#" id="reveal_complementary_courses_' + new_sem.replace( " ", "" ) + '" data-reveal-id="comp_courses" class="mdl-button mdl-js-button mdl-js-ripple-effect semester-add-comp-course-button reveal_complementary_courses" style="background-color: #aaedff">';
        new_semester += 'Add Course';
        new_semester += '</a>';
      }
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
      initComplementaryModalRevealListener("#reveal_complementary_courses_" + new_sem.replace( " ", "" ));



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
      new_semester += '<h5 style="text-align:center" class="semester-header" id="' + new_sem.replace( " ", "" ) + '_header">' + new_sem + '</h5>';
      if($("#required-group-div").length == 0)
      {
        new_semester += '<a href="#" id="reveal_complementary_courses_' + new_sem.replace( " ", "" ) + '" data-reveal-id="comp_courses" class="mdl-button mdl-js-button mdl-js-ripple-effect semester-add-comp-course-button reveal_complementary_courses" style="background-color: #aaedff">';
        new_semester += 'Add Course';
        new_semester += '</a>';
      }
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
      initComplementaryModalRevealListener("#reveal_complementary_courses_" + new_sem.replace( " ", "" ));

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
      if(!$($(".semester")[i]).find("div.delete-semester-wrap").length && !$($(".semester")[i]).find("div.complementary_area").length && !$($(".semester")[i]).find("div.elective_area").length)
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
function initAddCompCourseButton()
{
  $(".add_comp_course_button").click(function()
  {
    var target_sem = $($($("#course_schedule").find($("a.Complementary_Add_Target"))).parent());
    var semester = $(target_sem.find("div.sortable")).attr("id");
    semester = semester.split(" ");
    semester = semester[0] + " " + semester[1];
    semester = get_semester(semester);

    var selected = [];

    $(".Required_table_body tr").each(function()
    {
      if ($(this).hasClass('is-selected'))
      {
        selected.push([$(this).find('td.course_number').text(), $(this).find('td.class_name').text(), 'Required']);
        $(this).remove();
      }
    });

    $(".Complementary_table_body tr").each(function()
    {
      if ($(this).hasClass('is-selected'))
      {
        selected.push([$(this).find('td.course_number').text(), $(this).find('td.class_name').text(), 'Complementary']);
        $(this).remove();
      }
    });

    $(".Elective_table_body tr").each(function() {

      if ($(this).hasClass('is-selected')) {
        selected.push([$(this).find('td.course_number').text(), $(this).find('td.class_name').text(), 'Elective']);
        $(this).remove();
      }
    });

    for (var i = 0; i < selected.length; i++)
    {

      $.ajax({
        type: "post",
        url: "/flowchart/add-course-to-Schedule",
        data: {
          semester: semester,
          id: 'new schedule',
          courseName: selected[i][0],
          courseType: selected[i][[2]],
        },
        success: function(data) {
          var response = JSON.parse(data);
          if (response === 'Error')
          {
            //error handler
          }
          else
          {
            var comp_course = "<div class='custom_card " + response[4] + "_course' id='" + response[0] + "'>";
            comp_course += "<div class='card_content'>";
            comp_course += response[3]['SUBJECT_CODE'] + " &nbsp " + response[3]['COURSE_NUMBER'] + "&nbsp";
            comp_course += "<button id='menu_for_" + response[0] + "' class='mdl-button mdl-js-button mdl-button--icon'>";
            comp_course += "<i class='material-icons'>arrow_drop_down</i>";
            comp_course += "</button>" + " " + response[3]['COURSE_CREDITS'];
            comp_course += "<ul class='mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect' for='menu_for_" + response[0] + "''>";
            comp_course += "<li class='mdl-menu__item show-prereqs' id='show_prereqs_" + response[0] + "'>Show Pre-Requisites</li>";
            if(response[4] != 'Required')
            {
              comp_course += "<li class='mdl-menu__item remove-course' id='remove_" + response[0] + "'>Remove</li>";
            }
            comp_course += "</ul>";
            comp_course += "</div>";
            comp_course += "</div>";


            $(target_sem.find("div.credit_counter")).before(comp_course);
            $(target_sem.find('div.credit_counter_num' )).text( 'CREDITS: ' + response[1]);


            for (var group in response[2])
            {
                if (response[2].hasOwnProperty(group))
                {
                    var groupProgress = response[2][group];
                    var target = $( "td[id='" + group + "']" ).text("" + groupProgress[0] + "/" + groupProgress[1]);
                }
            }

            initRemoveCourseListener("#remove_"+ response[0]);
            //Dynamically render MDL
            componentHandler.upgradeDom();
            refreshDeleteSemester();
          }
        }
      })
    }

    $('#comp_courses').foundation('reveal', 'close');


  });
}




    function initRemoveCourseListener(target)
    {
      $(target).click(function(e){
        e.preventDefault();


        if($(this).parent().parent().parent().parent().hasClass("add-to-schedule"))
        {
          //courses that have NOT been added to the schedule have no need for a database call
          $(this).parent().parent().parent().parent().remove();
          refreshComplementaryCourses();
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
                if(response[3]!='Exemption')
                {
                  var semester = get_semester_letter(response[3]);
                  semester = semester.split(" ");
                  semester = semester[0] + semester[1];
                }
                else
                {
                  var semester = 'Exemption';
                }


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
                refreshComplementaryCourses();
              }
            }
          });




        }

      });
    }

    function refreshComplementaryCourses()
    {
      $.ajax({
        type: "get",
        url: "/flowchart/refresh_complementary_courses",

        success: function(data) {
          var response = JSON.parse(data);
          var refreshedCourses = response[0];
          if (response === 'Error')
          {
            //error handler
          }
          else
          {

            var html = "";
            for(var tabtitle in refreshedCourses){
            for(var key in refreshedCourses[tabtitle])
            {
              /*  <div class="mdl-tabs__panel is-active" id="{{$tabtitle}}_tab">
            @else
              <div class="mdl-tabs__panel" id="{{$tabtitle}}_tab">
            @endif

              @if(!is_null($Courses))
                <button type="button" class="mdl-button mdl-js-button mdl-button--raised add_comp_course_button">Add</button>
                @foreach ($Courses as $key=>$value)
                  @if( count($value) != 0)
                    <h4 id="{{$tabtitle}}_table_header_{{$key}}" style="text-align:center">{{$key}}  ({{$progress[$key][1]}} credits)</h4>
                      */
              if(refreshedCourses[tabtitle][key].length != 0){
                html = '<h4 id="' + tabtitle +'_table_header_' + key + '" style="text-align:center">' + key + ' (' + response[1][key] + ' credits)</h4>'
                html += '<table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp ' + tabtitle + '_table" id="' + tabtitle + '_table_'+key+'">';
                html += '<thead>';
                html += '<tr>';
                html += '<th class="mdl-data-table__cell--non-numeric">Course Number</th>';
                html += '<th class="mdl-data-table__cell--non-numeric">Course Name</th>';
                html += '<th>Credits</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody class="' + tabtitle + '_table_body tech_comp_table">';
                for( var i = 0; i < refreshedCourses[tabtitle][key].length; i++)
                {
                  if(  !$("[id='"+ refreshedCourses[tabtitle][key][i][0] + " " + refreshedCourses[tabtitle][key][i][1] +"']").length)
                  {
                    html += '<tr id="' + refreshedCourses[tabtitle][key][i][0] + refreshedCourses[tabtitle][key][i][1] + '">';
                    html += '<td class="mdl-data-table__cell--non-numeric course_number">' + refreshedCourses[tabtitle][key][i][0] + " " + refreshedCourses[tabtitle][key][i][1] + '</td>';
                    html += '<td class="mdl-data-table__cell--non-numeric class_name">' + refreshedCourses[tabtitle][key][i][4] +'</td>';
                    html += '<td>' + refreshedCourses[tabtitle][key][i][2] +'</td>';
                    html += '</tr>';
                  }
                }
                html += '</tbody>';
                html += '</table>';
              }


              $("[id='" + tabtitle +"_table_"+key+"']").remove();
              $("[id='" + tabtitle +"_table_header_"+key+"']").remove();
              $("[id='" + tabtitle + "_tab']").append(html);
            }
          }

            //Dynamically render MDL
            componentHandler.upgradeDom();
          }
        }
      });
    }
