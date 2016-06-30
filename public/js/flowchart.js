$(document).ready(function()
{

  renderSortable();

  initDeleteListener(".delete-semester");
  initAddSemesterListener(".add-semester")

});

function initAddSemesterListener(target)
{
  $(target).click(function(e){
    e.preventDefault();
    var last_sem = $(this).attr("id" ).substring(0 , $(this).attr("id" ).length - 4 );
    last_sem = last_sem.split( " " );
    last_sem = last_sem[ 0 ] + " " + last_sem[ 1 ];

    var new_sem = get_semester_letter( get_next_semester( get_semester( last_sem ) ) );

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
    new_semester += '<a href="#" id="' + new_sem + '-delete" class="delete-semester mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab"><i class="material-icons">clear</i></a>';
    new_semester += '</div>';
    new_semester += '</div>';


    //$("#course_schedule").append(new_semester);
    $(this).parent().before( new_semester );
    renderSortable();

    //add delete listener to new semester
    initDeleteListener("[id='"+new_sem+"-delete']");

    //update the gap
    $(this).attr("id",new_sem+"-gap");

    //check if the next semester exists
    last_sem = $(this).attr("id" ).substring(0 , $(this).attr("id" ).length - 4 );
    last_sem = last_sem.split( " " );
    last_sem = last_sem[ 0 ] + " " + last_sem[ 1 ];
    new_sem = formatSemesterID( get_semester_letter( get_next_semester( get_semester( last_sem ) ) ) );


    //if the next semester exists then we dont need the button!
    if($("[id='"+new_sem+"']").length)
    {
      $(this).remove();
    }

  });
}

function initDeleteListener(target)
{
  $(target).click(function(e){
    e.preventDefault();

    var target_sem = $(this).attr("id" ).substring(0 , $(this).attr("id" ).length - 7 );
    target_sem = target_sem.split( " " );
    target_sem = target_sem[ 0 ] + " " + target_sem[ 1 ];
    var CourseCount = $(this).parent().parent().find("div.custom_card").length - 1;
    var prev_sem = get_semester_letter( get_previous_semester( get_semester(target_sem)));
    var next_sem = get_semester_letter( get_next_semester( get_semester(target_sem)));



    console.log("target semester: " + target_sem);
    console.log(CourseCount);
    console.log("previous semester: " + prev_sem);
    console.log("next semester: " + next_sem)


      if(CourseCount)
      {
        //Verify to delete with user


        deleteSemester(prev_sem, target_sem, next_sem);
      }
      else
      {
        //no verification required


        deleteSemester(prev_sem, target_sem, next_sem);
      }



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
