$(document).ready(function(){

  $.ajaxSetup( {
    headers: {
      'X-CSRF-Token': $( 'meta[name=_token]' ).attr( 'content' )
    }
  } );

  // Controls reset degree Modal
  var dialog = document.querySelector('dialog');
  var showDialogButton = $('#show-dialog');
  if (! dialog.showModal) {
    dialogPolyfill.registerDialog(dialog);
  }
  $(showDialogButton).bind('click', function() {
    dialog.showModal();
  });
  dialog.querySelector('.close').addEventListener('click', function() {
    dialog.close();
  });

  LoadMajors();

  $('#faculty-select').change(function(){
    LoadMajors();
  });

  $('#major-select').change(function(){
    LoadVersions();
  });

  $('#version-select').change(function(){
    LoadStreams();
    LoadSemesters();
  })

  $('#stream-select').change(function(){
    LoadSemesters();
  })

  $("#reset-degree-button").click(function(){
     dialog.showModal();
  });
});


//Loads all majors pertaining to the currently selected Faculty
function LoadMajors(){
  var selectedFaculty = $('#faculty-select option:selected').text();
  $('#major-select').empty();

  if(selectedFaculty !== "None")
  {
    $.ajax({
      type: 'post',
      url: '/auth/registration/get-majors',
      data: { faculty : selectedFaculty},
      success: function(data) {

        var response = JSON.parse(data);

        for(var i =0; i<response.length; i++)
        {
          var option = '<option value="'+response[i][1]+'">' + response[i][0]+ '</option>';

          $('#major-select').append(option);
        }
      }
    })
  }
}

//Loads all version of program
function LoadVersions(){
  var selectedMajor = $('#major-select option:selected').val();
  $('#version-select').empty();

  if(selectedMajor !== "None")
  {
    $.ajax({
      type: 'post',
      url: '/auth/registration/get-versions',
      data: { program_id : selectedMajor},
      success: function(data) {

        var response = JSON.parse(data);

        $('#version-select').append('<option>-Select-</option>');

        for(var i =0; i<response.length; i++)
        {
          var option = '<option value="'+response[i]+'">' + response[i]+ '</option>';

          $('#version-select').append(option);
        }
      }
    })
  }
}

function LoadStreams(){
  var selectedMajor = $('#major-select option:selected').val();
  var selectedVersion = $('#version-select option:selected').text();
  $('#stream-select').empty();

  if(selectedMajor !== "None" && selectedVersion !== "None")
  {

    $.ajax({
      type: 'post',
      url: '/auth/registration/get-streams',
      data: {
        program_id : selectedMajor,
        version : selectedVersion
      },
      success : function(data) {
        var response = JSON.parse(data);

        for(var i =0; i<response.length; i++)
        {
          var option = '<option value="' + response[i][0] + '">'+ response[i][1] +'</option>';
          $('#stream-select').append(option);
        }

        var option = '<option value="-1">Custom</option>';
        $('#stream-select').append(option);
      }
    })
  }
}

function LoadSemesters(){
  var selectedStream = $('#stream-select option:selected').val();

  var type = (selectedStream == -1)? "all": "fall";
  $('#semester-select').empty();

  $.ajax({
    type: 'post',
    url: '/auth/registration/get-semesters',
    data: {
      semesters: type
    },
    success: function(data) {
      var response = JSON.parse(data);

      for(var i = 0; i<response.length; i++)
      {
        var option = '<option value="'+get_semester(response[i])+'">'+ response[i] +'</option>';
        $('#semester-select').append(option);
      }
    }
  })
}
