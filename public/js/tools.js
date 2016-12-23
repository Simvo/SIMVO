function get_VSB_active_semesters()
{
  var d = new Date();
  var month = d.getMonth();
  var year = d.getFullYear();
  var fall = year + "09";
  if(5<month<=12)year++;
  var winter = year + "01";

  return [fall, winter];
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

function getErrors()
{
  $.ajax({
    type : 'post',
    url : '/flowchart/getErrors',
    data : {},
    success : function(data) {
      var response = JSON.parse(data);
      console.log(response);

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
