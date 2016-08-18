$(document).ready(function()
{
  renderSortable();
  initAddCompCourseButton();
  initAddInternshipButton();
  initAddCustomCourseButton();
  initDeleteSemesterListener(".delete-semester");
  initAddSemesterListener(".add-semester");
  initRemoveCourseListener(".remove-course");
  initComplementaryModalRevealListener(".semester-add-comp-course-button");
  initEditInternship(".edit-internship");
  initEditCustomCourse(".edit_custom");
  refreshDeleteSemester();
  refreshComplementaryCourses();
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

      if($($(".semester")[i]).find("div.pinned").length)
      {
        $($(".semester")[i]).find("div.sortable").removeClass("validPosition");
      }
    }
    else
    {
      //empty -- append delete
      if(!$($(".semester")[i]).find("div.delete-semester-wrap").length && !$($(".semester")[i]).find("div.complementary_area").length && !$($(".semester")[i]).find("div.elective_area").length)
      {
        if(!$($(".semester")[i]).find("div.sortable").hasClass("validPosition"))
        {
          $($(".semester")[i]).find("div.sortable").addClass("validPosition");
        }
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
    var courseType = ['Required', 'Complementary', 'Elective'];
    var selected = [];

    for(var i = 0; i < 3; i++)
    {
      $("." + courseType[i] +"_table_body tr").each(function()
      {
        if ($(this).hasClass('is-selected'))
        {
          selected.push([$(this).find('td.course_number').text(), $(this).find('td.class_name').text(), courseType[i] ]);
          $(this).remove();
        }
      });
      for(var k = 0; k < $("." + courseType[i] +"_table_body").length; k++)
      {
        var target = $($("." + courseType[i] +"_table_body")[k]);
        if(target.length != 0)
        {
          var parent = target.parent().attr("id");
          var id = parent.substring(courseType[i].length + 7, parent.length);
          $("[id='" + courseType[i] +"_table_header_"+id+"']").remove();
          $("[id='" + courseType[i] +"_table_"+id+"']").remove();
        }
      }
    }


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
            comp_course += "<li class='mdl-menu__item remove-course' id='remove_" + response[0] + "'>Remove</li>";
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
            refreshComplementaryCourses();
            refreshDeleteSemester();
            componentHandler.upgradeDom();
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

          if($(".Internship_holder_" + courseID).length)
          {
            var internshipDelete = $(".Internship_holder_" + courseID);
            //Case where internship has holder semesters
            for(var i = 0; i < internshipDelete.length; i++ )
            {
              $("#remove_" + $(internshipDelete[i]).attr("id")).trigger("click");
            }
          }
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

                $("."+semester).find( '.credit_counter_num' ).text( 'CREDITS: ' + response[1]);


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
        url: "/flowchart/refresh-complementary-courses",

        success: function(data) {
          var response = JSON.parse(data);
          var refreshedCourses = response[0];
          if (response === 'Error')
          {
            //error handler
          }
          else
          {
            var firstTab = true;
            var firstTabName = "";
            var tabs = '<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">';
            tabs += "<div class='mdl-tabs__tab-bar'>";
            var html = "";
            for(var tabtitle in refreshedCourses)
            {
              html += '<div class="mdl-tabs__panel" id="' + tabtitle + '_tab_panel">';
              var existCoursesInTab = false;
              for(var key in refreshedCourses[tabtitle])
              {
                if(refreshedCourses[tabtitle][key].length != 0){
                  if(firstTab)
                  {
                    firstTab = false;
                    firstTabName = tabtitle;
                    html = '<div class="mdl-tabs__panel is-active" id="' + tabtitle + '_tab_panel">';
                    tabs += '<a href="#' + tabtitle + '_tab_panel" id="' + tabtitle + '_tab" class="mdl-tabs__tab is-active">' + tabtitle + '</a>';
                  }
                  if(!existCoursesInTab)
                  {
                    html += '<button type="button" class="mdl-button mdl-js-button mdl-button--raised add_button add_comp_course_button">Add</button>';
                  }
                  existCoursesInTab = true;
                  html += '<h4 id="' + tabtitle +'_table_header_' + key + '" style="text-align:center">' + key + ' (' + response[1][key] + ' credits)</h4>';
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
              }
              html += '</div>';
              if(existCoursesInTab && !firstTab && tabtitle != firstTabName)
              {
                tabs += '<a href="#' + tabtitle + '_tab_panel" id="' + tabtitle + '_tab" class="mdl-tabs__tab">' + tabtitle + '</a>';
              }
            }
            tabs += '<a href="#Custom_tab" class="mdl-tabs__tab">Custom</a>';
            tabs += '<a href="#Internship_tab" class="mdl-tabs__tab">Internship</a>';
            tabs += '</div>';
            html += '<div class="mdl-tabs__panel" id="Custom_tab">';
            html += $("#Custom_tab").html();
            html += '</div>';
            html += '<div class="mdl-tabs__panel" id="Internship_tab">';
            html += $("#Internship_tab").html();
            html += '</div>'

            tabs += html + '</div>';
            $(".mdl-tabs").remove();
            $("#comp_courses").append(tabs);
            initAddCompCourseButton();
            initAddInternshipButton();
            initAddCustomCourseButton();

            var upgrade = $("#Internship_tab").find("div.is-upgraded");
            for(var l = 0; l < upgrade.length; l++)
            {
              $(upgrade[l]).removeClass("is-upgraded");
              $(upgrade[l]).removeAttr("data-upgraded");
            }

            upgrade = $("#Custom_tab").find("div.is-upgraded");
            for(var l = 0; l < upgrade.length; l++)
            {
              $(upgrade[l]).removeClass("is-upgraded");
              $(upgrade[l]).removeAttr("data-upgraded");
            }



            //Dynamically render MDL
            componentHandler.upgradeDom();
          }
        }
      });
    }




    function initAddInternshipButton()
    {
      $(".add_internship_button").click(function(){
        var target_sem = $($($("#course_schedule").find($("a.Complementary_Add_Target"))).parent());
        var semester_letter = $(target_sem.find("div.sortable")).attr("id");
        if(semester_letter != "Exemption")
        {

          semester_letter = semester_letter.split(" ");
          semester_letter = semester_letter[0] + " " + semester_letter[1];
          var semester = get_semester(semester_letter);
        }


        var company = $("#internship_company_name").val();
        var length = parseInt($("#internship_length_select").val());
        var width = 160;
        var position = $("#internship_position_held").val();


        //Check if there is valid input -- all the fields have something in them
        if(company.length == 0 || position.length == 0 )
        {
          $("#Internship_error").remove();
          var invalid = '<div id="Internship_error"> Please fill out all of the internship information </div>'
          $(".add_internship_button").after(invalid);
          return;
        }
        else if(semester_letter == "Exemption")
        {
          $("#Internship_error").remove();
          var invalid = '<div id="Internship_error">Internships cannot be added to the Exemption list!';
          $(".add_internship_button").after(invalid);
          return;
        }
        else
        {
          $("#Internship_error").remove();
        }



        //allocate semesters for internship occupation
        var k = 0;

        while (k < length)
        {
          var firstSemesterCheck =  $($($(".semester")[1]).find("div.sortable")).attr("id").split(" ");
          firstSemesterCheck = firstSemesterCheck[0] + " " + firstSemesterCheck[1];

          if($("[id='" + formatSemesterID(semester_letter) + "']").find("div.custom_card").length > 1 )
          {
            $("#Internship_error").remove();
            var invalid = '<div id="Internship_error"> There were courses found in semesters you wish to place your internship! Make sure you clear these out before adding your internship</div>'
            $(".add_internship_button").after(invalid);
            return;
          }

          if($("[id='" + semester_letter +"-gap']").length)
          {
            $("[id='" + semester_letter +"-gap']").trigger("click");
          }


          var prev_sem = get_semester_letter(get_previous_semester(get_semester(semester_letter)));

          if($("[id='" + prev_sem + "-gap']").length)
          {
            $("[id='" + prev_sem +"-gap']").trigger("click");
          }
          width += 220;
          k++;
          semester_letter = get_semester_letter(get_next_semester(get_semester(semester_letter)));
        }

        width -= 220;



          $.ajax({
            type: "post",
            url: "/flowchart/user-create-course",
            data: {
              semester: semester,
              details: 'Internship ' + width + ' ' + length,
              company: company,
              position: position,
            },

            success: function(data) {
              var response = JSON.parse(data);
              for( var i = 0; i < response[4].length; i++)
              {
                var sem = get_semester_letter(response[4][i]);
                var sem2 = sem.split(" ");
                sem2 = sem2[0] + sem2[1];
                if(i == 0)
                {
                  var comp_course = "<div class='custom_card pinned " + response[1] + "_course' id='" + response[0][i] + "' style='width:"+width+"px;'>";
                  comp_course += "<div class='card_content'>";
                  comp_course += '<div class="internship_company_name" id="internship_company_name_' + response[0][i] + '">' + response[2] + '</div>';
                  comp_course += '<div class="internship_position_held" id="internship_position_held_' + response[0][i] + '">' + response[3] + '</div>';
                  comp_course += "<button id='menu_for_" + response[0][i] + "' class='mdl-button mdl-js-button mdl-button--icon'>";
                  comp_course += "<i class='material-icons'>arrow_drop_down</i>";
                  comp_course += "</button>"
                  comp_course += "<ul class='mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect' for='menu_for_" + response[0][i] + "''>";
                  comp_course += "<li class='mdl-menu__item edit-internship' id='edit_internship_" + response[0][i] + "'>Edit</li>";
                  comp_course += "<li class='mdl-menu__item remove-course' id='remove_" + response[0][i] + "'>Remove</li>";
                  comp_course += "</ul>";
                  comp_course += "</div>";
                  comp_course += "</div>";
                  $("." + sem2).append(comp_course);
                }
                else
                {
                  var comp_course = "<div class='custom_card pinned Internship_holder_" + response[0][0] + "' id='" + response[0][i] + "' >";
                  comp_course += "<div class='card_content'>";
                  comp_course += '<div>' + response[2] + '</div>';
                  comp_course += '<div>' + response[3] + '</div>';
                  comp_course += "<button id='menu_for_" + response[0][i] + "' class='mdl-button mdl-js-button mdl-button--icon'>";
                  comp_course += "<i class='material-icons'>arrow_drop_down</i>";
                  comp_course += "</button>"
                  comp_course += "<ul class='mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect' for='menu_for_" + response[0][i] + "''>";
                  comp_course += "<li class='mdl-menu__item edit-internship' id='edit_internship_" + response[0][i] + "'>edit</li>";
                  comp_course += "<li class='mdl-menu__item remove-course' id='remove_" + response[0][i] + "'>Remove</li>";
                  comp_course += "</ul>";
                  comp_course += "</div>";
                  comp_course += "</div>";
                  $("." + sem2).append(comp_course);
                }
                initRemoveCourseListener("#remove_" + response[0][i]);
                initEditInternship("#edit_internship_" + response[0][i]);
              }

              refreshDeleteSemester();
              refreshComplementaryCourses();

              //Dynamically render MDL
              componentHandler.upgradeDom();



            }

          });

        $('#comp_courses').foundation('reveal', 'close');
      });
    }

    function initEditInternship(target)
    {
      $(target).click(function(e){
        var id = $(this).attr("id").substring(16, $(this).attr("id").length);
        var companyName = $("#internship_company_name_" + id).html();
        var positionHeld = $("#internship_position_held_" + id).html();

        var textfield1 = '<div class="mdl-textfield mdl-js-textfield">';
        textfield1 += '<textarea class="mdl-textfield__input edit_internship_textfield" type="text" rows= "3" id="edit_company_name_textfield_' + id + '" >' + companyName + '</textarea>';
        textfield1 += '<label class="mdl-textfield__label" for="edit_position_held_textfield_' + id + '">Company name</label>';
        textfield1 += '</div>';

        var textfield2 = '<div class="mdl-textfield mdl-js-textfield">';
        textfield2 += '<textarea class="mdl-textfield__input edit_internship_textfield" type="text" rows= "2" id="edit_position_held_textfield_' + id + '" >' + positionHeld + '</textarea>';
        textfield2 += '<label class="mdl-textfield__label" for="edit_position_held_textfield_' + id + '">Position held</label>';
        textfield2 += '</div>';

        $("#menu_for_" + id).remove();
        $("#internship_company_name_" + id).html(textfield1);
        $("#internship_position_held_" + id).html(textfield2);

        var options = '<div> ';
        options += '<button id="internship_edit_cancel_' + id + '" class="mdl-button mdl-js-button mdl-js-ripple-effect">';
        options += '<i class="material-icons">cancel</i>';
        options += '</button>';
        options += '<button id="internship_edit_confirm_' + id + '" class="mdl-button mdl-js-button mdl-js-ripple-effect">';
        options += '<i class="material-icons">check</i>';
        options += '</button>';
        options += "</div>";
        $("#internship_position_held_" + id).after(options);

        initConfirmEditInternshipButton(id,companyName, positionHeld);


        //Dynamically render MDL
        componentHandler.upgradeDom();

      });
    }

    function initConfirmEditInternshipButton(id, originalCN, originalPH)
    {

      //on confirmTarget click
      $("#internship_edit_confirm_" + id).click(function(){
        var newCN = $("#edit_company_name_textfield_" + id).val();
        var newPH = $("#edit_position_held_textfield_" + id).val();
        var html = '<div class="card_content">';

        if(originalCN == newCN && originalPH == newPH)
        {
          html += '<div class="internship_company_name" id="internship_company_name_' + id + '"> ' + originalCN + ' </div>';
          html += '<div class="internship_position_held" id="internship_position_held_' + id + '"> ' + originalPH + ' </div>';
          html += '<button id="menu_for_' + id + '" class="mdl-button mdl-js-button mdl-button--icon">';
          html += '<i class="material-icons">arrow_drop_down</i>';
          html += '</button>';
          html += '<ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="menu_for_' + id + '">';
          html += '<li class="mdl-menu__item edit-internship" id="edit_internship_' + id + '">Edit</li>';
          html += '<li class="mdl-menu__item remove-course" id="remove_' + id+ '">Remove</li>';
          html += '</ul>';
        }
        else
        {
          html += '<div class="internship_company_name" id="internship_company_name_' + id + '"> ' + newCN + ' </div>';
          html += '<div class="internship_position_held" id="internship_position_held_' + id + '"> ' + newPH + ' </div>';
          html += '<button id="menu_for_' + id + '" class="mdl-button mdl-js-button mdl-button--icon">';
          html += '<i class="material-icons">arrow_drop_down</i>';
          html += '</button>';
          html += '<ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="menu_for_' + id + '">';
          html += '<li class="mdl-menu__item edit-internship" id="edit_internship_' + id + '">Edit</li>';
          html += '<li class="mdl-menu__item remove-course" id="remove_' + id+ '">Remove</li>';
          html += '</ul>';
          //DB CALL!
          $.ajax({
            type: "post",
            url: "/flowchart/edit-internship",
            data: {
              id: id,
              companyName: newCN,
              positionHeld: newPH,
            }
          });

          //--------------
        }
        html += '</div>';

        $("#" + id).html( html );

        initEditInternship("#edit_internship_" + id);
        initRemoveCourseListener("#remove_" + id);

        //Dynamically render MDL
        componentHandler.upgradeDom();

      });


      //on cancelTarget click
      $("#internship_edit_cancel_" + id).click(function(){

        var newCN = $("#edit_company_name_textfield_" + id).val();
        var newPH = $("#edit_position_held_textfield_" + id).val();

        var html = '<div class="card_content">';
        html += '<div class="internship_company_name" id="internship_company_name_' + id + '"> ' + originalCN + ' </div>';
        html += '<div class="internship_position_held" id="internship_position_held_' + id + '"> ' + originalPH + ' </div>';
        html += '<button id="menu_for_' + id + '" class="mdl-button mdl-js-button mdl-button--icon">';
        html += '<i class="material-icons">arrow_drop_down</i>';
        html += '</button>';
        html += '<ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="menu_for_' + id + '">';
        html += '<li class="mdl-menu__item edit-internship" id="edit_internship_' + id + '">Edit</li>';
        html += '<li class="mdl-menu__item remove-course" id="remove_' + id+ '">Remove</li>';
        html += '</ul>';
        html += '</div>';
        $("#" + id).html( html );

        initEditInternship("#edit_internship_" + id);
        initRemoveCourseListener("#remove_" + id);

        //Dynamically render MDL
        componentHandler.upgradeDom();

      });
    }


function initAddCustomCourseButton()
{
  $(".add_custom_course_button").click( function(){
    var target_sem = $($($("#course_schedule").find($("a.Complementary_Add_Target"))).parent());
    var semester_letter = $(target_sem.find("div.sortable")).attr("id");
    if(semester_letter != "Exemption")
    {

      semester_letter = semester_letter.split(" ");
      semester_letter = semester_letter[0] + " " + semester_letter[1];
      var semester = get_semester(semester_letter);
    }
    else
    {
      //throw error message!
      console.log("You may not add custom courses to the exemption semester!");
      return;
    }



    var title = $("#custom_title").val();
    var credits = parseInt($("#custom_credit_select").val());
    var focus = $("#custom_focus").val();
    var description = $("#custom_description").val();



    if(title == "")
    {
      //throw error message!
      console.log("Please enter in a title for your custom course");
      return;
    }

    $.ajax({
      type: "post",
      url: "/flowchart/user-create-course",
      data: {
        details: "Custom " + credits,
        title: title + "|" + description,
        focus: focus,
        semester: semester,

      },
      success: function(data){
        var response = JSON.parse(data);

        var html = '';
        var sem = get_semester_letter(response[4]);
        var sem2 = sem.split(" ");
        var cutTitle = response[2].split("|")[0];
        if(cutTitle.length > 11)
        {
          cutTitle = cutTitle.substring(0,8) + "...";
        }
        sem2 = sem2[0] + sem2[1];

        html += '<div class="custom_card ' + response[1] + '_course" id="' + response[0] + '" >';
        html += '<div class="card_content">';
        html += '<div class="custom_course_title" id="custom_course_title_' + response[0] + '">' + cutTitle +  ' </div> &nbsp  &nbsp';
        html += '<button id="menu_for_' + response[0] + '" class="mdl-button mdl-js-button mdl-button--icon">';
        html += '<i class="material-icons">arrow_drop_down</i>';
        html += '</button> &nbsp &nbsp';
        html += '<div class="custom_course_credits" id="custom_course_credits_' + response[0] + '">'+ response[3] + '</div>';
        html += '<div class="custom_course_focus" id="custom_course_focus_' + response[0] + '">' + response[7] + '</div>';

        html += '<ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="menu_for_' + response[0] + '">';
        html += '<li disabled class=" mdl-menu__item mdl-menu__item--full-bleed-divider custom_course_description" id="custom_course_description_' + response[0] + '">' + response[2].split("|")[1] + '</li>';
        html += '<li class="mdl-menu__item edit_custom" id="edit_custom_' + response[0] + '">Edit</li>';
        html += '<li class="mdl-menu__item remove-course" id="remove_' + response[0] + '">Remove</li>';
        html += '</ul>';
        html += '</div>';
        html += '</div>';

        $(target_sem.find("div.credit_counter")).before(html);
        $(target_sem.find('div.credit_counter_num' )).text( 'CREDITS: ' + response[5]);


        for (var group in response[6])
        {
            if (response[6].hasOwnProperty(group))
            {
                var groupProgress = response[6][group];
                var target = $( "td[id='" + group + "']" ).text("" + groupProgress[0] + "/" + groupProgress[1]);
            }
        }

        initEditCustomCourse("#edit_custom_" + response[0]);
        initRemoveCourseListener("#remove_" + response[0]);
        refreshDeleteSemester();
        refreshComplementaryCourses();



        //Dynamically render MDL
        componentHandler.upgradeDom();

      }
    });


    $('#comp_courses').foundation('reveal', 'close');




  });

}

function initEditCustomCourse(target){
  $(target).click(function() {
    var id = $(this).attr("id").substring(12, $(this).attr("id").length );

    $("#" + id).animate({"width":"300px"},{queue: false, duration: 100},"linear");
    $("#" + id).animate({"height":"400px"},{queue: false, duration: 100},"linear");


    $.ajax({
      type: 'get',
      url: '/flowchart/get-elective-groups',
      success: function(data) {

        var response = JSON.parse(data);


        $.ajax({
          type: 'get',
          url: '/flowchart/get-schedule-course-info',
          data: { id: id},
          success: function(data2){

            var response2 = JSON.parse(data2);

            var title = response2[0];
            var description = response2[1];
            var focus = response2[2];
            var credits = response2[3];



            var html = '<div class="card_content">';
            html += '<div class="mdl-textfield mdl-js-textfield">';
            html += '<textarea class="mdl-textfield__input edit_internship_textfield" type="text" rows= "3" id="edit_custom_course_title_' + id + '" >' + $.trim(title) + '</textarea>';
            html += '<label class="mdl-textfield__label" for="edit_custom_course_title_' + id + '">Custom Title</label>';
            html += '</div>';

            html += '<div class="mdl-textfield mdl-js-textfield">';
            html += '<textarea class="mdl-textfield__input edit_internship_textfield" type="text" rows= "3" id="edit_custom_course_description_' + id + '" >' + $.trim(description) + '</textarea>';
            html += '<label class="mdl-textfield__label" for="edit_custom_course_description_' + id + '">Custom Description</label>';
            html += '</div>';


            html += '<div id="custom_course_focus_' + id + '">';
            html += 'Focus: ';
            html += '<select name="Focus" id="edit_custom_focus_select_' + id + '" class="reg_dropdown form-control">';
            for(var group in response )
            {
              html += '<option value="'+ group +'">' + group + '</option>';
            }
            html += '<option value="Miscellaneous">Miscellaneous</option>';
            html += '</select>';
            html += '</div>';
            html += '<br>';

            html += '<div id="custom_course_credits_' + id + '">';
            html += 'Credits: ';
            html += '<select name="Credits" id="edit_custom_credits_select_' + id + '" class="reg_dropdown form-control">';
            for(var i = 1; i <= 6; i++ )
            {
              html += '<option value="'+ i +'">' + i + '</option>';
            }
            html += '</select>';
            html += '</div>';

            html += '</div>';

            html += '<div style="margin:auto;"> ';
            html += '<button id="custom_edit_cancel_' + id + '" class="mdl-button mdl-js-button mdl-js-ripple-effect">';
            html += '<i class="material-icons">cancel</i>';
            html += '</button>';
            html += '<button id="custom_edit_confirm_' + id + '" class="mdl-button mdl-js-button mdl-js-ripple-effect">';
            html += '<i class="material-icons">check</i>';
            html += '</button>';
            html += "</div>";


            $("#" + id).html(html);
            $("#" + id).addClass("pinned");

            renderSortable();
            initConfirmEditCustomButton(id, $.trim(title), $.trim(description), $.trim(focus), credits);
            //Dynamically render MDL
            componentHandler.upgradeDom();
          }
        });



      }
    });
  });
}


function initConfirmEditCustomButton( id, originalTitle, originalDescription, originalGroup, originalCredits){
  $("#custom_edit_confirm_" + id).click(function(){
    var newTitle = $("#edit_custom_course_title_" + id).val();
    var newDescription = $("#edit_custom_course_description_" + id).val();
    var newGroup = $("#edit_custom_focus_select_" + id).val();
    var newCredits = parseInt($("#edit_custom_credits_select_" + id).val());
    var cutTitle = newTitle;
    if(cutTitle.length > 11)
    {
      cutTitle = cutTitle.substring(0,8) + "...";
    }

    if(newTitle == originalTitle && newDescription == originalDescription && newGroup == originalGroup && newCredits == originalCredits)
    {
      var html = '<div class="card_content">';
      html += '<div class="custom_course_title" id="custom_course_title_' + id + '">' + cutTitle + '</div> &nbsp  &nbsp';
      html += '<button id="menu_for_' + id + '" class="mdl-button mdl-js-button mdl-button--icon">';
      html += '<i class="material-icons">arrow_drop_down</i>';
      html += '</button>';
      html += '<div class="custom_course_credits" id="custom_course_credits_' + id + '"> ' + originalCredits + ' </div>';
      html += '<div class="custom_course_focus" id="custom_course_focus_' + id + '"> ' + originalGroup + ' </div>';

      html += '<ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="menu_for_' + id + '">';
      html += '<li disabled class=" mdl-menu__item mdl-menu__item--full-bleed-divider custom_course_description" id="custom_course_description_' + id + '">' + originalDescription + ' </li>';
      html += '<li class="mdl-menu__item edit_custom" id="edit_custom_' + id + '">Edit</li>';
      html += '<li class="mdl-menu__item remove-course" id="remove_' + id + '">Remove</li>';
      html += '</ul>';
      html += '</div>';

      $("#" + id).html(html);
      $("#" + id).animate({"width":"160px"},{queue: false, duration: 100},"linear");
      $("#" + id).animate({"height":"45px"},{queue: false, duration: 100},"linear");

      initEditCustomCourse("#edit_custom_" + id);
      initRemoveCourseListener("#remove_" + id);

      //Dynamically render MDL
      componentHandler.upgradeDom();

    }
    else
    {

      var html = '<div class="card_content">';
      html += '<div class="custom_course_title" id="custom_course_title_' + id + '">' + cutTitle + '</div> &nbsp  &nbsp';
      html += '<button id="menu_for_' + id + '" class="mdl-button mdl-js-button mdl-button--icon">';
      html += '<i class="material-icons">arrow_drop_down</i>';
      html += '</button>';
      html += '<div class="custom_course_credits" id="custom_course_credits_' + id + '"> ' + newCredits + ' </div>';
      html += '<div class="custom_course_focus" id="custom_course_focus_' + id + '"> ' + newGroup + ' </div>';

      html += '<ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="menu_for_' + id + '">';
      html += '<li disabled class=" mdl-menu__item mdl-menu__item--full-bleed-divider custom_course_description" id="custom_course_description_' + id + '">' + newDescription + ' </li>';
      html += '<li class="mdl-menu__item edit_custom" id="edit_custom_' + id + '">Edit</li>';
      html += '<li class="mdl-menu__item remove-course" id="remove_' + id + '">Remove</li>';
      html += '</ul>';
      html += '</div>';

      $("#" + id).html(html);
      $("#" + id).animate({"width":"160px"},{queue: false, duration: 100},"linear");
      $("#" + id).animate({"height":"45px"},{queue: false, duration: 100},"linear");

      $.ajax({
        type: 'post',
        url: 'flowchart/edit-custom',
        data: {
          id: id,
          title: newTitle + "|" + newDescription,
          group: newGroup,
          credits: newCredits,
        },
        success: function(data){
          var response = JSON.parse(data);
          var semester_letter = get_semester_letter(response[2]);
          semester_letter = semester_letter.split(" ");
          semester_letter = semester_letter[0] + semester_letter[1];

          var target_sem = $("." + semester_letter);


          $(target_sem.find('div.credit_counter_num' )).text( 'CREDITS: ' + response[0]);


          for (var group in response[1])
          {
              if (response[1].hasOwnProperty(group))
              {
                  var groupProgress = response[1][group];
                  var target = $( "td[id='" + group + "']" ).text("" + groupProgress[0] + "/" + groupProgress[1]);
              }
          }
        }
      });

      initEditCustomCourse("#edit_custom_" + id);
      initRemoveCourseListener("#remove_" + id);

      //Dynamically render MDL
      componentHandler.upgradeDom();
    }


  });

  $("#custom_edit_cancel_" + id).click( function() {

    var cutTitle = originalTitle;
    if(cutTitle.length > 11)
    {
      cutTitle = cutTitle.substring(0,8) + "...";
    }

      var html = '<div class="card_content">';
      html += '<div class="custom_course_title" id="custom_course_title_' + id + '">' + cutTitle + '</div> &nbsp  &nbsp';
      html += '<button id="menu_for_' + id + '" class="mdl-button mdl-js-button mdl-button--icon">';
      html += '<i class="material-icons">arrow_drop_down</i>';
      html += '</button>';
      html += '<div class="custom_course_credits" id="custom_course_credits_' + id + '"> ' + originalCredits + ' </div>';
      html += '<div class="custom_course_focus" id="custom_course_focus_' + id + '"> ' + originalGroup + ' </div>';

      html += '<ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="menu_for_' + id + '">';
      html += '<li disabled class=" mdl-menu__item mdl-menu__item--full-bleed-divider custom_course_description" id="custom_course_description_' + id + '">' + originalDescription + ' </li>';
      html += '<li class="mdl-menu__item edit_custom" id="edit_custom_' + id + '">Edit</li>';
      html += '<li class="mdl-menu__item remove-course" id="remove_' + id + '">Remove</li>';
      html += '</ul>';
      html += '</div>';

      $("#" + id).html(html);
      $("#" + id).animate({"width":"160px"},{queue: false, duration: 100},"linear");
      $("#" + id).animate({"height":"45px"},{queue: false, duration: 100},"linear");

      initEditCustomCourse("#edit_custom_" + id);
      initRemoveCourseListener("#remove_" + id);

      //Dynamically render MDL
      componentHandler.upgradeDom();

  });




}
