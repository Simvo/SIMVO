function get_VSB_active_semesters()
{
  var d = new Date();
  var month = d.getMonth();

  if(month < 5)
    return ["201701"];
  else
    return ["201609", "201701"];
}

function checkVSB(new_semester, id, semesterID)
{
  var vsbActiveSemesters = get_VSB_active_semesters();
  // Check for VSB warnings
  if(new_semester === vsbActiveSemesters[0] || new_semester === vsbActiveSemesters[1])
  {
    $.ajax({
      type: 'post',
      url: '/flowchart/check-course-availability',
      data: {
        semester: new_semester,
        scheduleID: id
      },
      success: function(data){
      }
    });
  }
}

function checkIgnoredErrors()
{
  $.ajax({
    type: 'post',
    url: 'flowchart/check-for-ignored-errors',
    success: function(data){
      var response = JSON.parse(data);
      $(".reveal-errors").remove();
      for (var semester in response) 
      {
        if (response.hasOwnProperty(semester)) 
        {
          if(response[semester] > 0)
          {
            var targetSemester = get_semester_letter(semester).split(" ");

            $(".validPosition."+ targetSemester[0] + "." + targetSemester[1]).append("<a class='reveal-errors' id='show_" +semester+"'>click here to reveal "+response[semester]+" errors</a>");
          }
        }
      }
    }
  })
}

function addCreateScheduleLinks()
{
  $(".create_vsb").remove();

  var semesters = get_VSB_active_semesters();

   for(var i = 0; i<semesters.length ; i++)
   {
     var sem = get_semester_letter(semesters[i]).split(" ");

     var html =  '<div class="vsb_wrapper">';
     html +=     '<a class="create_vsb" id="'+semesters[i]+'" href="#">Preview Schedule</a>';
     html +=     '</div>';

     var target = $("." + sem[0] + "." + sem[1] + " .credit_counter");

     if(target.length == 0)
     {
       target = $("." + sem[0] + "" + sem[1] + " .credit_counter");
     }

     target.after(html);

     componentHandler.upgradeDom();
   }
}

function createVSBSchedule(courses, semester)
{
  var base_url = "https://vsb.mcgill.ca/vsb/criteria.jsp?access=0&lang=en&tip=0&page=results&scratch=0&term=" + semester + "&sort=none&filters=iiiiiiiii&bbs=&ds=&cams=Distance_Downtown_Macdonald_Off-Campus&locs=any&isrts=&";

  for(var i = 0; i<courses.length; i++) 
  {
    var course_name = courses[i][0].toUpperCase() + "-" + courses[i][1];
    base_url += "course_"+ i +"_0=" + course_name + "&sa_"+ i +"_0=&cs_"+i+"_0=--" + semester + "_698--&cpn_"+i+"_0=&csn_"+i+"_0=&ca_"+i+"_0=&dropdown_"+i+"_0=al&ig_"+i+"_0=0&rq_"+i+"_0=&";
  }

  var win = window.open(base_url, '_blank');

  if (win) {
      //Browser has allowed it to be opened
      win.focus();
  } else {
      //Browser has blocked it
      alert('It seems your browser did not allow the pop-up to appear. Make sure to approve pop-ups from us!');
  }
}

function getCoursesInSemester(sem)
{
   var CourseCount = $(sem).find("div.custom_card")

  return courses;
}

function getErrors()
{
  $.ajax({
    type : 'post',
    url : '/flowchart/getErrors',
    data : {},
    success : function(data) {
      var response = JSON.parse(data);

      for(var i = 0; i<response.length; i++)
      {
        var errorInstance = response[i];

        if($("#error_" + errorInstance[0]).length > 0)
        {
          continue;
        }

        var errorType = (response[i][3] === "prereq__error")?  'prereq__error' : 'vsb_error';

        var error = "<div class='" + errorType  + " course_error' id='error_"+errorInstance[0]+"'>";
        error += "<div class='ignore_error' id='hide_" + errorInstance[0]+ "'><a href='#'>x</a></div>"
        error += "<p>" + errorInstance[2] + "</p>";
        error += "</div>";

        $("#" + errorInstance[1] + " .card_content").append(error);
      }
    }
  });
}


function removeErrors(idArray)
{
  for(var i = 0; i<idArray.length ; i++)
  {
    $("#error_" + idArray[i]).remove();
  }
}

function get_semester_letter( semester )
{
  var term = semester.substring( 0, 4 );

  var result = "";

  switch ( semester.substring( 4, 6 ) ) {
    case '09':
      result += "FALL ";
      break;
    case '01':
      result += "WINTER ";
      break;
    case '05':
      result += "SUMMER ";
      break;
  }

  result += term;

  return result;
}

function get_previous_semester( current )
{
  var semester = "";
  var year = 0;

  var term = current.substring( 4, 6 );
  var curr_year = parseInt( current.substring( 0, 4 ) );

  switch ( term )
  {
    case '01':
      semester = '09';
      year = curr_year - 1;
      break;
    case '05':
      semester = '01';
      year = curr_year;
      break;
    case '09':
      semester = '05';
      year = curr_year;
      break;
  }

  semester = year.toString() + semester;
  return semester;
}

function get_next_semester( current )
{
    var semester = "";
    var year = 0;

    var term = current.substring( 4, 6 );
    var curr_year = parseInt( current.substring( 0, 4 ) );

    switch ( term )
    {
      case '01':
        semester = '05';
        year = curr_year;
        break;
      case '05':
        semester = '09';
        year = curr_year;
        break;
      case '09':
        semester = '01';
        year = curr_year + 1;
        break;
    }

    semester = year.toString() + semester;
    return semester;
  }

  function get_semester( semester )
  {
    if(semester==="Exemption")
    {
      return "Exemption";
    }

    var term = semester.split( " " );

    var result = term[ 1 ];

    switch ( term[ 0 ].toUpperCase() )
    {
      case 'FALL':
        result += "09";
        break;
      case 'WINTER':
        result += "01";
        break;
      case 'SUMMER':
        result += "05";
        break;
    }

    return result;
  }

  function formatSemesterID( semester )
  {
    semester = semester.split( " " );
    semester = semester[ 0 ] + " " + semester[ 1 ] + " "+ semester[0] + semester[1];
    return semester;
  }

  function isSemesterEmpty(sem){
    var CourseCount = $(sem).find("div.custom_card").length - 1;
    return CourseCount;
  }
