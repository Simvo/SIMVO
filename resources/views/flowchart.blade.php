@extends('master')
@section('flowchart')

<script type="text/javascript">
var user_id = "{{ $user->id }}";
var user_email = "{{ $user->email }}";

mixpael.identify(user_id);

mixpanel.people.set({
  "$user": user_email
});
</script>

<div class="mdl-grid" style="padding-bottom: 0px">
  <div class="mdl-cell mdl-cell--12-col">
    <div class="mdl-card progress_div">
      <div class="mdl-card__title">
        <h4 class="mdl_card__title-text">{{$degree->program_name}}</h4>
      </div>

        <div class="mdl-card__supporting-text">
          <div id="p1" class="mdl-progress mdl-js-progress"></div>
          <script>
          document.querySelector('#p1').addEventListener('mdl-componentupgraded', function() {
            this.MaterialProgress.setProgress(44);
          });
          </script>
        </div>

    </div>
  </div>
</div>

<div class="mdl-grid" style="padding-bottom: 0px">
  <div class="mdl-cell mdl-cell--12-col" style="overflow-x: scroll">
    <div class="mdl-card mdl-shadow--2dp progress_div">
      <table id="progress_table" style="margin: 0 auto; width:100% !important;">
        <thead>
          <tr>
            @if($degreeLoaded)
            @foreach ($progress as $key=>$value)
            <td class="progress_cell">{{$key}}</td>
            @endforeach
            @endif
          </tr>
        </thead>
        <tbody>
          <tr>
            @if($degreeLoaded)
            @foreach ($progress as $key=>$value)
            <td class="progress_cell group_cell {{str_replace(" ", "", $key)}}" id="{{ $key }}">{{ $value[0] }}/{{$value[1]}}</td>
            @endforeach
            @endif
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="mdl-grid">
  <div  class="mdl-cell mdl-cell--12-col">
    <div class="mdl-card mdl-shadow--2dp area_1_card">
      <div class="mdl-card__actions mdl-card--border ">
        <fieldset class="my_program">
          <legend>MY PROGRAM</legend>
        </fieldset>
        <div id="course_schedule" class="schedule_wrap" style="padding-bottom: 50px">
          <!-- Exemption Semester -->
          @if ($degreeLoaded)
          <div class="semester">
            <h5 style="text-align:center">Exemptions</h5>
            <a href="#" id="reveal_complementary_courses_{{str_replace(" ", "", $key)}}" data-reveal-id="comp_courses" class="mdl-button mdl-js-button mdl-js-ripple-effect semester-add-comp-course-button reveal_complementary_courses" style="background-color: #aaedff">
              Add Course
            </a>
            <div class="draggable">
              <div class="validPosition sortable Exemption" id="Exemption">
                @foreach($exemptions[0] as $exemption)
                <div class="custom_card {{ $exemption[4] }}_course" id="{{ $exemption[0] }}">
                  <div class="card_content">
                    {{ $exemption[1] }} &nbsp {{ $exemption[2] }}
                    <button id="menu_for_{{ $exemption[0] }}" class="mdl-button mdl-js-button mdl-button--icon">
                      <i class="material-icons">arrow_drop_down</i>
                    </button> {{ $exemption[3] }}

                    <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="menu_for_{{ $exemption[0] }}">
                      <!--<li class="mdl-menu__item show-prereqs" id="show_prereqs_{{ $exemption[0] }}">Show Pre-Requisites</li>-->
                      <li class="mdl-menu__item remove-course" id="remove_{{ $exemption[0] }}">Remove</li>
                    </ul>
                  </div>
                </div>
                @endforeach
                <div class="custom_card credit_counter" style="text-align:center;">
                  <div class="credit_counter_num" style="display: table-cell; vertical-align: middle; font-size:15px">
                    CREDITS:{{$exemptions[1]}}
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif
          <!-- List of Semesters -->
          @foreach($schedule as $key => $classes)
          <div class="semester">
            <h5 style="text-align:center" class="semester-header" id="{{str_replace(" ", "", $key)}}_header">{{ $key }}</h5>

            <a href="#" id="reveal_complementary_courses_{{str_replace(" ", "", $key)}}" data-reveal-id="comp_courses" class="mdl-button mdl-js-button mdl-js-ripple-effect semester-add-comp-course-button reveal_complementary_courses" style="background-color: #aaedff">
              Add Course
            </a>

            <div class="draggable" >
              <div class="validPosition sortable {{ str_replace(" ", "", $key) }}" id="{{ $key . " " . str_replace(" ", "", $key) }}" >

                @foreach($classes[1] as $class)
                @if(explode(" ", $class[4])[0] == "Internship" )
                <div class="custom_card pinned {{ explode(" ", $class[4])[0] }}_course" id="{{ $class[0] }}" style="width:{{explode(" ", $class[4])[1]}}px">
                  <div class="card_content">
                    <div class="internship_company_name" id="internship_company_name_{{ $class[0] }}"> {{ $class[1] }} </div>
                    <div class="internship_position_held" id="internship_position_held_{{ $class[0] }}"> {{ $class[2] }} </div>
                    <button id="menu_for_{{ $class[0] }}" class="mdl-button mdl-js-button mdl-button--icon">
                      <i class="material-icons">arrow_drop_down</i>
                    </button>

                    <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="menu_for_{{ $class[0] }}">
                      <li class="mdl-menu__item edit-internship" id="edit_internship_{{ $class[0] }}">Edit</li>
                      <li class="mdl-menu__item remove-course" id="remove_{{ $class[0] }}">Remove</li>
                    </ul>
                  </div>
                </div>
                @elseif (explode(" ", $class[4])[0] == "Internship_holder")
                <div class="custom_card pinned {{ explode(" ", $class[4])[0] }}_{{ explode(" ", $class[4])[1] }}" id="{{ $class[0] }}" >
                  <div class="card_content">
                    <button id="menu_for_{{ $class[0] }}" class="mdl-button mdl-js-button mdl-button--icon">
                      <i class="material-icons">arrow_drop_down</i>
                    </button>

                    <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="menu_for_{{ $class[0] }}">
                      <li class="mdl-menu__item edit-internship" id="edit_internship_{{ $class[0] }}">Edit</li>
                      <li class="mdl-menu__item remove-course" id="remove_{{ $class[0] }}">Remove</li>
                    </ul>
                  </div>
                </div>
                @else
                <div class="custom_card {{ $class[4] }}_course" id="{{ $class[0] }}">
                  <div class="card_content">
                    {{ $class[1] }} &nbsp {{ $class[2] }}
                    <button id="menu_for_{{ $class[0] }}" class="mdl-button mdl-js-button mdl-button--icon">
                      <i class="material-icons">arrow_drop_down</i>
                    </button> {{ $class[3] }}

                    <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="menu_for_{{ $class[0] }}">
                      <!--<li class="mdl-menu__item show-prereqs" id="show_prereqs_{{ $class[0] }}">Show Pre-Requisites</li>-->
                      <li class="mdl-menu__item remove-course" id="remove_{{ $class[0] }}">Remove</li>
                    </ul>
                  </div>
                </div>
                @endif
                @endforeach

                <div class="custom_card credit_counter" style="text-align:center;">
                  <div class="credit_counter_num" style="display: table-cell; vertical-align: middle; font-size:15px">
                    CREDITS: {{ $classes[0] }}
                  </div>
                </div>
                @if(isset($course_errors[$key]))
                @foreach ($course_errors[$key] as $error)
                <div id='error_{{$error[0]}}' class='error {{$error[1]}}'>
                  {{ $error[2] }}
                </div>
                @endforeach
                @endif
              </div>
            </div>


          </div>

          <!-- Adding missing semester buttons -->
          @if ((substr($key, 0, 6) == "WINTER" && !array_key_exists("SUMMER " . substr($key, 7, 4), $schedule) ) || (substr($key, 0, 4) == "FALL" && !array_key_exists("WINTER " .((int)substr($key, 5, 4) + 1), $schedule)  ) || (substr($key, 0, 6) == "SUMMER" && !array_key_exists("FALL " . substr($key, 7, 4), $schedule)  )   )
          <div class="fill-semester-gap-wrap">
            <a href="#" id="{{$key}}-gap" class="add-semester mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab" style="background-color: #2980b9;"><i class="material-icons" style="color: white">add</i></a>
          </div>
          @endif

          @endforeach
        </div>

        <div id="comp_courses" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">

          <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">


            <div class="mdl-tabs__panel" id="Custom_tab">
            </div>
            <div class="mdl-tabs__panel" id="Internship_tab">
              <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--2-col">
                </div>
                <div class="mdl-cell mdl-cell--8-col">
                  <h4 id="make-degree_title">Enter your internship information here</h4>
                  <div>
                    <ul class="list-style-none">
                    </ul>
                    <table>

                      <tr>
                        <td>
                          Company
                        </td>
                        <td>
                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="internship_company_name">
                            <label class="mdl-textfield__label" for="internship_company_name">Company Name</label>
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <td>
                          Position
                        </td>
                        <td>
                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="internship_position_held">
                            <label class="mdl-textfield__label" for="internship_position_held">Position Held</label>
                          </div>
                        </td>
                      </tr>

                      <tr>
                        <td>
                          Semesters &nbsp &nbsp
                        </td>
                        <td>
                          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label program_input">
                            <select class="reg_dropdown form-control" name="Semesters" id="internship_length_select">
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                            </select>
                          </div>
                        </td>
                      </tr>






                    </table>
                    <button type="button" class="mdl-button mdl-js-button mdl-button--raised add_internship_button">Add Internship</button>
                  </div>
                </div>
                <div class="mdl-cell mdl-cell--2-col">
                </div>
              </div>
            </div>
          </div>
        </div>





      </div>
    </div>
  </div>
</div>
<!-- If new User, init first instance of Degree -->
@if($newUser)
<div id="make_degree" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="false" role="dialog" data-options="close_on_background_click:false">
  <div class="mdl-grid">
    <div class="mdl-cell mdl-cell--2-col">
    </div>
    <div class="mdl-cell mdl-cell--8-col">
      <h4 id="make-degree_title">Hey {{$user->firstName}}! Looks like you are new here. Let's Get you started with S!MVO</h4>
      {!! Form::open(['route' => 'newUserCreateDegree','style'=>'width:100%']) !!}
      <ul class="list-style-none">
        @foreach ($errors->all() as $error)
        <li class="submit_error">{{ $error }}</li>
        @endforeach
      </ul>
      <table>
        <tr>
          <td>
            Faculty
          </td>
          <td>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label program_input">
              {!! Form::select('Faculty', $faculties, null, ['class'=> 'reg_dropdown form-control', 'id'=>'faculty-select']) !!}
            </div>
          </td>
        </tr>

        <tr>
          <td>
            Major
          </td>
          <td>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label program_input">
              <select name="Major" id="major-select" class="reg_dropdown form-control"></select>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            Version
          </td>
          <td>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label program_input">
              <select name="Version" id="version-select" class="reg_dropdown form-control"></select>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            Select a Stream
          </td>
          <td>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label program_input">
              <select name="Stream" id="stream-select" class="reg_dropdown form-control"></select>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            Semester You Entered Selected Program
          </td>
          <td>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label program_input">
              <select name="Semester" id="semester-select" class="reg_dropdown form-control"></select>
            </div>
          </td>
        </tr>
      </table>
      {!! Form::submit('Submit', ['class'=> 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent new_user_submit']) !!}
      {!! Form::close() !!}
    </div>
    <div class="mdl-cell mdl-cell--2-col">
    </div>
  </div>
</div>
<script>
$(document).ready(function(){$('#make_degree').foundation('reveal', 'open')});
</script>
@endif
@endsection
