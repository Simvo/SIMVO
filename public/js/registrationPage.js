$(document).ready(function(){

  $.ajaxSetup( {
    headers: {
      'X-CSRF-Token': $( 'meta[name=_token]' ).attr( 'content' )
    }
  } );

  LoadMajors();

  $('#faculty-select').change(function(){
    LoadMajors();
  });

  $('#major-select').change(function(){
    LoadStreams();
    LoadVersions();
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

function LoadStreams(){
  var selectedMajor = $('#major-select option:selected').text();
  $('#stream-select').empty();

  if(selectedMajor !== "None")
  {
    var option = '<option value="-1">Custom</option>';
    $('#stream-select').append(option);
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

        for(var i =0; i<response.length; i++)
        {
          var option = '<option value="'+response[i]+'">' + response[i]+ '</option>';

          $('#version-select').append(option);
        }
      }
    })
  }
}
