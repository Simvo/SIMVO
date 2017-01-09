$(document).ready(function () {
   var init = false;

  $.ajaxSetup({
    headers: {
      'X-CSRF-Token': $('meta[name=_token]').attr('content')
    }
  });

  // Controls create Degree Behavior
  LoadMajors();
  var init = true;

  $('#faculty-select').change(function () {
    if(init) LoadMajors();
  });

  $('#major-select').change(function () {
    if(init) LoadVersions();
  });

  $(document).on('change', '#version-select', function() {
   LoadStreams();
  });

  $('#stream-select').change(function () {
    LoadSemesters();
  })
});


//Loads all majors pertaining to the currently selected Faculty
function LoadMajors() {
  var selectedFaculty = $('#faculty-select option:selected').text();
  $('#major-select').empty();

  if (selectedFaculty !== "None") {
    $.ajax({
      type: 'post',
      url: '/auth/registration/get-majors',
      data: {
        faculty: selectedFaculty
      },
      success: function (data) {

        var response = JSON.parse(data);

        for (var i = 0; i < response.length; i++) {
          var option = '<option value="' + response[i][1] + '">' + response[i][0] + '</option>';

          $('#major-select').append(option);
        }
        
        LoadVersions();
      }
    })
  }
}

//Loads all version of program
function LoadVersions() {
  var selectedMajor = $('#major-select option:selected').val();

  if (selectedMajor !== "None") {
    $.ajax({
      type: 'post',
      url: '/auth/registration/get-versions',
      data: {
        program_id: selectedMajor
      },
      success: function (data) {
        $('#version-select').empty();
        $('#versionSlot').empty();
        $('descSlot').empty();

        var response = JSON.parse(data);

        if (response.length > 1) {
          var versionSelect = '<td> Version ';
          versionSelect += '</td>';
          versionSelect += '<td>';
          versionSelect += '<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label program_input">';
          versionSelect += '<select name="Version" id="version-select" class="reg_dropdown form-control"></select>';
          versionSelect += '</div>';
          versionSelect += '</td>';

          versionDesc = '<td>';
          versionDesc += '<p>It seems this program has multiple versions. If your program has been changed recently (ex: ECSESS has redone all of their curriculums for students entering in FALL 2016) The higher the number, the newer the version.</p>';
          versionDesc += '<td>';

          $('#versionSlot').append(versionSelect);
          $('#descSlot').append(versionDesc);

          for (var i = 0; i < response.length; i++) {
            var option = '<option value="' + response[i] + '">' + response[i] + '</option>';

            $('#version-select').append(option);
            componentHandler.upgradeDom();
          }
        } else {
          var hiddenInput = $('<input/>', {
            type: 'hidden',
            id: "version-select",
            name: "Version",
            value: response[0]
          });
          $("#versionSlot").append(hiddenInput);
        }

        LoadStreams();
      }
    })
  }
}

function LoadStreams() {
  var selectedMajor = $('#major-select option:selected').val();
  var selectedVersion = $('#version-select option:selected').text();
  if( selectedVersion === "" || typeof selectedVersion === "undefined")
  {
    selectedVersion = $('#version-select').val();
  }
  $('#stream-select').empty();

  if (selectedMajor !== "None" && selectedVersion !== "None") {

    $.ajax({
      type: 'post',
      url: '/auth/registration/get-streams',
      data: {
        program_id: selectedMajor,
        version: selectedVersion
      },
      success: function (data) {
        var response = JSON.parse(data);

        for (var i = 0; i < response.length; i++) {
          var option = '<option value="' + response[i][0] + '">' + response[i][1] + '</option>';
          $('#stream-select').append(option);
        }

        var option = '<option value="-1">Custom</option>';
        $('#stream-select').append(option);

        LoadSemesters();
      }
    })
  }
}

function LoadSemesters() {
  var selectedStream = $('#stream-select option:selected').val();

  var type = (selectedStream == -1) ? "all" : "fall";
  $('#semester-select').empty();

  $.ajax({
    type: 'post',
    url: '/auth/registration/get-semesters',
    data: {
      semesters: type
    },
    success: function (data) {
      var response = JSON.parse(data);

      for (var i = 0; i < response.length; i++) {
        var option = '<option value="' + get_semester(response[i]) + '">' + response[i] + '</option>';
        $('#semester-select').append(option);
      }
    }
  })
}