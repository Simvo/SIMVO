@extends('master')
@section('flowchart')

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
                        <li class="mdl-menu__item show-prereqs" id="show_prereqs_{{ $exemption[0] }}">Show Pre-Requisites</li>
                        @if($exemption[4]!='Required')
                          <li class="mdl-menu__item remove-course" id="remove_{{ $exemption[0] }}">Remove</li>
                        @endif
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
            @if (!is_null($groupsWithCourses))
              @if (count($groupsWithCourses) == 0)
                <a href="#" id="reveal_complementary_courses_{{str_replace(" ", "", $key)}}" data-reveal-id="comp_courses" class="mdl-button mdl-js-button mdl-js-ripple-effect semester-add-comp-course-button reveal_complementary_courses" style="background-color: #aaedff">
                  Add Course
                </a>
              @endif
            @endif
            <div class="draggable" >
              <div class="validPosition sortable {{ str_replace(" ", "", $key) }}" id="{{ $key . " " . str_replace(" ", "", $key) }}" >

                @foreach($classes[1] as $class)
                  <div class="custom_card {{ $class[4] }}_course" id="{{ $class[0] }}">
                    <div class="card_content">
                      {{ $class[1] }} &nbsp {{ $class[2] }}
                      <button id="menu_for_{{ $class[0] }}" class="mdl-button mdl-js-button mdl-button--icon">
                        <i class="material-icons">arrow_drop_down</i>
                      </button> {{ $class[3] }}

                      <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="menu_for_{{ $class[0] }}">
                        <li class="mdl-menu__item show-prereqs" id="show_prereqs_{{ $class[0] }}">Show Pre-Requisites</li>
                        @if($class[4]!='Required')
                          <li class="mdl-menu__item remove-course" id="remove_{{ $class[0] }}">Remove</li>
                        @endif
                      </ul>
                    </div>
                  </div>
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

        <div id="add_course_tutorial" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
          <h3 id="tutorial-header"> Congratulations on placing your required courses!</h3>
          <h5>
            Now you can start choosing Complementary Courses and Electives
          </h5>
          <div class="tutorial-div">
            <h5>1) Choose your desired semester </h5>
            <img src="tutorial1.png" alt="" />
            <br>
          </div>
          <div class="tutorial-div">
            <h5> 2) Select the courses you wish to add </h5>
            <img src="tutorial2.png" alt="" />
            <br>
          </div>
          <div class="tutorial-div">
            <h5> 3) Click Add </h5>
            <img src="tutorial3.png" alt="" />
            <br>
          </div>
          <div class="tutorial-div">
            <h5> BAM! You're done!</h5>
            <img src="tutorial4.png" alt="" />
          </div>




        </div>
        @if(!is_null($complementaryCourses[0]) && !is_null($complementaryCourses[1]))
          @if (count($complementaryCourses[0]) != 0 && count($complementaryCourses[1]) != 0)
        <div id="comp_courses" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">

          <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
            <div class="mdl-tabs__tab-bar">
                <a href="#complementary_tab" class="mdl-tabs__tab is-active">Complementaries</a>
                <a href="#elective_tab" class="mdl-tabs__tab">Electives</a>
                <a href="#custom_tab" class="mdl-tabs__tab">Custom</a>

            </div>
            <div class="mdl-tabs__panel is-active" id="complementary_tab">

              @foreach ($complementaryCourses[0] as $key=>$value)
                <h4 id="complementary_table_header_{{$key}}" style="text-align:center">{{$key}}  ({{$progress[$key][1]}} credits)</h4>
                <table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp complementary_table" id="complementary_table_{{$key}}">
                  <thead>
                    <tr>
                      <th class="mdl-data-table__cell--non-numeric">Course Number</th>
                      <th class="mdl-data-table__cell--non-numeric">Course Name</th>
                      <th>Credits</th>
                    </tr>
                  </thead>

                  <tbody class="complementary_table_body tech_comp_table">
                  @foreach ($value as $course)
                  <tr id="{{ $course[0] }}{{ $course[1] }}">
                    <td class="mdl-data-table__cell--non-numeric course_number">{{$course[0]}} {{ $course[1] }}</td>
                    <td class="mdl-data-table__cell--non-numeric class_name">{{ $course[4] }}</td>
                    <td>{{ $course[2] }}</td>
                  </tr>
                  @endforeach
                  </tbody>
                </table>
                @endforeach

                <button type="button" class="mdl-button mdl-js-button mdl-button--raised add_comp_course_button">Add</button>
                <a class="close-reveal-modal" aria-label="Close">&#215;</a>
            </div>
            <div class="mdl-tabs__panel" id="elective_tab">
              @foreach ($complementaryCourses[1] as $key=>$value)
                <h4 id="elective_table_header_{{$key}}" style="text-align:center">{{$key}}  ({{$progress[$key][1]}} credits)</h4>
                <table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp elective_table" id="elective_table_{{$key}}">
                  <thead>
                    <tr>
                      <th class="mdl-data-table__cell--non-numeric">Course Number</th>
                      <th class="mdl-data-table__cell--non-numeric">Course Name</th>
                      <th>Credits</th>
                    </tr>
                  </thead>

                  <tbody class="elective_table_body tech_comp_table">
                  @foreach ($value as $course)
                  <tr id="{{ $course[0] }}">
                    <td class="mdl-data-table__cell--non-numeric course_number">{{$course[0]}} {{ $course[1] }}</td>
                    <td class="mdl-data-table__cell--non-numeric class_name">{{ $course[4] }}</td>
                    <td>{{ $course[2] }}</td>
                  </tr>
                  @endforeach
                  </tbody>
                </table>
                @endforeach

                <button type="button" class="mdl-button mdl-js-button mdl-button--raised add_elec_course_button">Add</button>
                <a class="close-reveal-modal" aria-label="Close">&#215;</a>
              </div>
              <div class="mdl-tabs__panel" id="custom_tab">
              </div>
          </div>
        </div>
        @endif
        @endif



          @if(!is_null($groupsWithCourses))
            @if (count($groupsWithCourses) != 0)
            <div id="required-group-div" class="mdl-grid" style="padding-bottom: 50px;">
              <div class="group-container">
                @foreach ($groupsWithCourses as $key=>$value)
                  <div class="group-row">
                     <p class="group-title">{{$key}}</p>
                     <div class="draggable">
                       <div class="sortable" style="text-align:center; width:150px; margin:auto">
                         @foreach ($value as $course)
                           <div class="custom_card {{ $course[3] }}_course add-to-schedule" id="{{ $course[0] }} {{ $course[1] }}">
                            <div class="card_content">
                              {{ $course[0] }}&nbsp{{ $course[1] }}
                              <button id="menu_for_{{ $course[0] }}{{ $course[1] }}" class="mdl-button mdl-js-button mdl-button--icon">
                                <i class="material-icons">arrow_drop_down</i>
                              </button>
                              <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="menu_for_{{ $course[0] }}{{ $course[1] }}">
                                <li class="mdl-menu__item show-prereqs" id="show_prereqs_{{ $course[0] }}">Show Pre-Requisites</li>
                              </ul>
                              {{ $course[2] }}
                            </div>
                          </div>
                         @endforeach
                       </div>
                     </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        @endif
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
              {!! Form::select('Semester', $semesters, null, ['class'=> 'reg_dropdown form-control']) !!}
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
