$(document).ready(function(){

  $.ajaxSetup( {
    headers: {
      'X-CSRF-Token': $( 'meta[name=_token]' ).attr( 'content' )
    }
  } );

  $('#faculty-select').change(function(){

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
  });

});
