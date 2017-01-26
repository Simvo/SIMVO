@extends('master') @section('flowchart')

<script>
  mixpanel.identify("{{ $user->id }}");

  mixpanel.people.set({
    "$email": "{{ $user->email }}",   
    
    "firstName" : "{{ $user->firstName }}",

    "lastName" : "{{ $user->lastName }}",

    "$last_login": new Date(),        
    @if($degreeLoaded)
    "degree": "{{ $degree->program_name }}",

    "stream": "{{ $degree->stream_version }}",

    "entering semester": "{{ $degree->enteringSemester }}",
    @endif
});
</script>
<!-- Progress Bar for Major -->
<div class="mdl-grid" style="padding-bottom: 0px">
  <div class="mdl-cell mdl-cell--12-col">
    <div class="mdl-card mdl-shadow--2dp progress_div">
      <div class="mdl-card__supporting-text">
        @if($degreeLoaded)
        <b>{{$degree->program_name}}</b>
        <b><span id="major-status">{{$remainingCredits}}</span>/{{$degree->program_credits}}</b> @endif
      </div>
      <div class="adjustable-border">
        <div id="progressBar" class="mdl-progress mdl-js-progress"></div>
      </div>
      <table id="progress_table" style="margin: 0 auto; width:100% !important;">
        <thead>
          <tr>
            @if($degreeLoaded) @foreach ($progress as $key=>$value)
            <td class="progress_cell">{{$key}}</td>
            @endforeach @endif
          </tr>
        </thead>
        <tbody>
          <tr>
            @if($degreeLoaded) @foreach ($progress as $key=>$value)
            <td class="progress_cell group_cell {{str_replace(" ", " ", $key)}}" id="{{ $key }}">{{ $value[0] }}/{{$value[1]}}</td>
            @endforeach @endif
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<!-- Progress Bar for Minor Aloing with settings buttons-->
<div class="mdl-grid" style="padding-bottom: 0px">
  <div class="mdl-cell mdl-cell--2-col">
    <button class='mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent' data-reveal-id="reset-degree-modal" type="button">Reset Degree</button>
    <div id="reset-degree-modal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
      <h4 class="mdl-dialog__title modal-title" style="text-align: center; line-height: 40.0px;">Reseting Your Degree Will Delete All of Your Courses!</h4>
      <div class="mdl-dialog__content">
        <p class="modal-text">
          Are you sure you want to continue? (This is not in any way connected to minerva, any changes here will not be relfected by
          minerva) By doing this, you can change your program and entering semester.
        </p>
      </div>
      <div class="mdl-dialog__actions">
        <a type="button" class="mdl-button" href="{{ route('resetDegree') }}">Agree</a>
        <button type="button" class="mdl-button close-reveal-modal">X</button>
      </div>
    </div>
  </div>
  <div class="mdl-cell mdl-cell--2-col">
    <a href="#" data-reveal-id="show-add-minors" class='mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent'>
        @if (count($progress_minor))
          Change Minor
        @else
          Add Minor
        @endif
      </a>
    <div id="show-add-minors" class="reveal-modal" data-reveal aria-labelledby="show-add-minors" aria-hidden="true" role="dialog">
      <h3 id="minor-title">Available Minors</h3>
      {!! Form::open(['route' => 'addMinor','style'=>'width:100%']) !!} @if ($progress_minor != null) {!! Form::submit('Change
      Minor', ['class'=> 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent add-minor-submit'])
      !!} @else {!! Form::submit('Add Minor', ['class'=> 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect
      mdl-button--accent add-minor-submit']) !!} @endif
      <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp minors-table">
        <thead>
          <tr>
            <th>Select</th>
            <th>Minor</th>
            <th>Faculty</th>
            <th>Credits</th>
          </tr>
        </thead>
        @foreach($minors as $minor)
        <tr class="mdl-cell mdl-cell--2-col">
          <td>
            <div class="label-wrapper">
              <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="{{$minor[1]}}" style="padding-bottom: 1pc">
                    <input type="radio" id="{{$minor[1]}}" class="mdl-radio__button" name="minor_chosen" value="{{$minor[1]}}">
                  </label>
            </div>
          </td>
          <td>
            {{$minor[0]}}
          </td>
          <td>
            {{$minor[2]}}
          </td>
          <td>
            {{$minor[3]}}
          </td>
        </tr>
        @endforeach
      </table>
      {!! Form::close() !!}
    </div>
  </div>
  <div class="mdl-cell mdl-cell--2-col">
    @if($progress_minor)
    <a href="{{ route('removeMinor') }}" class='mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent'>
        Remove Minor
      </a> @endif
  </div>
  @if($progress_minor)
  <div class="mdl-cell mdl-cell--6-col">
    <div class="mdl-card mdl-shadow--2dp progress_div">
      <div class="mdl-card__supporting-text">
        @if($degreeLoaded)
        <b>{{$minor_object->minor_name}}</b>
        <b><span id="minor-status">{{$remainingCreditsMinor}}</span>/{{$minor_object->minor_credits}}</b>
        @endif
      </div>
      <div class="adjustable-border">
        <div id="progressBarMinor" class="mdl-progress mdl-js-progress"></div>
      </div>
      <table id="progress_table" style="margin: 0 auto; width:100% !important;">
        <thead>
          <tr>
            @if($degreeLoaded) @foreach ($progress_minor as $key=>$value)
            <td class="progress_cell">{{$key}}</td>
            @endforeach @endif
          </tr>
        </thead>
        <tbody>
          <tr>
            @if($degreeLoaded) @foreach ($progress_minor as $key=>$value)
            <td class="progress_cell group_cell {{str_replace(" ", " ", $key)}}" id="{{ $key }}">{{ $value[0] }}/{{$value[1]}}</td>
            @endforeach @endif
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  @endif
</div>
<div class="mdl-grid">
  <div class="mdl-cell mdl-cell--12-col">
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
            <a href="#" id="reveal_complementary_courses_{{str_replace(" ", " ", $key)}}" data-reveal-id="comp_courses" class="mdl-button mdl-js-button mdl-js-ripple-effect semester-add-comp-course-button reveal_complementary_courses"
              style="background-color: #aaedff">
                  Add Course
                </a>
            <div class="draggable">
              <div class="validPosition sortable Exemption" id="Exemption">
                @foreach($exemptions[0] as $exemption)
                @if($exemption[4] !=  "Custom")
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
                 @elseif ($exemption[4]== "Custom")
                <div class="custom_card {{ $exemption[4]}}_course" id="cust{{ $exemption[0] }}">
                  <div class="card_content">
                    <div class="custom_course_title" id="custom_course_title_cust{{$exemption[0]}}">
                      @if(strlen($exemption[1])
                      < 11) {{ $exemption[1] }} @else {{ substr($exemption[1], 0, 8). "..." }} @endif </div>
                      &nbsp &nbsp
                        <button id="menu_for_cust{{ $exemption[0] }}" class="mdl-button mdl-js-button mdl-button--icon">
                        <i class="material-icons">arrow_drop_down</i>
                      </button>
                        <div class="custom_course_credits" id="custom_course_credits_cust{{$exemption[0]}}"> {{ $exemption[3]}} </div>
                        <div class="custom_course_focus" id="custom_course_focus_cust{{$exemption[0]}}"> {{$exemption[5]}} </div>
                        <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="menu_for_cust{{ $exemption[0] }}">
                          <li disabled class=" mdl-menu__item mdl-menu__item--full-bleed-divider custom_course_description" id="custom_course_description_cust{{$exemption[0]}}">
                          {{$exemption[2]}} </li>
                          <li class="mdl-menu__item edit_custom" id="edit_custom_cust{{ $exemption[0] }}">Edit</li>
                          <li class="mdl-menu__item remove-course" id="remove_cust{{ $exemption[0] }}">Remove</li>
                        </ul>
                    </div>
                  </div>
                  @endif
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
            <h5 style="text-align:center" class="semester-header" id="{{str_replace(" ", " ", $key)}}_header">{{ $key }}</h5>
            <a href="#" id="reveal_complementary_courses_{{str_replace(" ", " ", $key)}}" data-reveal-id="comp_courses" class="mdl-button mdl-js-button mdl-js-ripple-effect semester-add-comp-course-button reveal_complementary_courses"
              style="background-color: #aaedff">
                  Add Course
                </a>
            <div class="draggable">
              <div class="validPosition sortable {{ str_replace(" ", "", $key) }}" id="{{ $key . " " . str_replace(" ", "", $key) }}">
                @foreach($classes[1] as $class) @if($class[4] == "Internship" )
                <div class="custom_card pinned {{ $class[4]}}_course" id="int{{ $class[0] }}" style="width:{{$class[5]}}px">
                  <div class="card_content">
                    <div class="internship_company_name" id="internship_company_name_int{{ $class[0] }}"> {{ $class[1] }} </div>
                    <div class="internship_position_held" id="internship_position_held_int{{ $class[0] }}"> {{ $class[2] }} </div>
                    <button id="menu_for_int{{ $class[0] }}" class="mdl-button mdl-js-button mdl-button--icon">
                          <i class="material-icons">arrow_drop_down</i>
                        </button>
                    <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="menu_for_int{{ $class[0] }}">
                      <li class="mdl-menu__item edit-internship" id="edit_internship_int{{ $class[0] }}">Edit</li>
                      <li class="mdl-menu__item remove-course" id="remove_int{{ $class[0] }}">Remove</li>
                    </ul>
                  </div>
                </div>
                @elseif ($class[4]== "Custom")
                <div class="custom_card {{ $class[4]}}_course" id="cust{{ $class[0] }}">
                  <div class="card_content">
                    <div class="custom_course_title" id="custom_course_title_cust{{$class[0]}}">
                      @if(strlen($class[1])
                      < 11) {{ $class[1] }} @else {{ substr($class[1], 0, 8). "..." }} @endif </div>
                      &nbsp &nbsp
                        <button id="menu_for_cust{{ $class[0] }}" class="mdl-button mdl-js-button mdl-button--icon">
                        <i class="material-icons">arrow_drop_down</i>
                      </button>
                        <div class="custom_course_credits" id="custom_course_credits_cust{{$class[0]}}"> {{ $class[3]}} </div>
                        <div class="custom_course_focus" id="custom_course_focus_cust{{$class[0]}}"> {{$class[5]}} </div>
                        <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="menu_for_cust{{ $class[0] }}">
                          <li disabled class=" mdl-menu__item mdl-menu__item--full-bleed-divider custom_course_description" id="custom_course_description_cust{{$class[0]}}">
                          {{$class[2]}} </li>
                          <li class="mdl-menu__item edit_custom" id="edit_custom_cust{{ $class[0] }}">Edit</li>
                          <li class="mdl-menu__item remove-course" id="remove_cust{{ $class[0] }}">Remove</li>
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
                        <li class="mdl-menu__item show-prereqs" id="show_prereqs_{{ $class[0] }}">Show Pre-Requisites</li>
                        <li class="mdl-menu__item remove-course" id="remove_{{ $class[0] }}">Remove</li>
                      </ul>
                    </div>
                  </div>
                  @endif @endforeach
                  <div class="custom_card credit_counter" style="text-align:center;">
                    <div class="credit_counter_num" style="display: table-cell; vertical-align: middle; font-size:15px">
                      CREDITS: {{ $classes[0] }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Adding missing semester buttons -->
            @if ((substr($key, 0, 6) == "WINTER" && !array_key_exists("SUMMER " . substr($key, 7, 4), $schedule) ) || (substr($key, 0,
            4) == "FALL" && !array_key_exists("WINTER " .((int)substr($key, 5, 4) + 1), $schedule) ) || (substr($key, 0,
            6) == "SUMMER" && !array_key_exists("FALL " . substr($key, 7, 4), $schedule) ) )
            <div class="fill-semester-gap-wrap">
              <a href="#" id="{{$key}}-gap" class="add-semester mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab" style="background-color: #2980b9;"><i class="material-icons" style="color: white">add</i></a>
            </div>
            @endif @endforeach
          </div>
          <div id="comp_courses" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
            <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
              <div class="mdl-tabs__tab-bar">
                @foreach($groupsWithCourses as $tabtitle => $Courses) @if(!is_null($Courses)) @if($tabtitle == 'Complementary')
                <a href="#{{$tabtitle}}_tab_panel" id="{{$tabtitle}}_tab" class="mdl-tabs__tab is-active">{{$tabtitle}}</a>                @else
                <a href="#{{$tabtitle}}_tab_panel" id="{{$tabtitle}}_tab" class="mdl-tabs__tab">{{$tabtitle}}</a> @endif
                @endif @endforeach
                <a href="#Custom_tab" class="mdl-tabs__tab">Custom</a>
                <a href="#Internship_tab" class="mdl-tabs__tab">Internship</a>
              </div>
              @foreach($groupsWithCourses as $tabtitle => $Courses) @if($tabtitle == 'Complementary')
              <div class="mdl-tabs__panel is-active" id="{{$tabtitle}}_tab_panel">
                @else
                <div class="mdl-tabs__panel" id="{{$tabtitle}}_tab_panel">
                  @endif @if(!is_null($Courses))
                  <button type="button" class="mdl-button mdl-js-button mdl-button--raised add_button add_comp_course_button">Add</button>                  @foreach ($Courses as $key=>$value) @if( count($value) != 0)
                  <table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp {{$tabtitle}}_table" id="{{$tabtitle}}_table_{{$key}}">
                    <thead>
                      <tr>
                        <th class="mdl-data-table__cell--non-numeric">Course Number</th>
                        <th class="mdl-data-table__cell--non-numeric">Course Name</th>
                        <th>Credits</th>
                      </tr>
                    </thead>
                    <tbody class="{{$tabtitle}}_table_body tech_comp_table">
                    </tbody>
                  </table>
                  @endif @endforeach @endif
                  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
                </div>
                @endforeach
                <div class="mdl-tabs__panel" id="Custom_tab">
                  <div class="mdl-grid">
                    <div class="mdl-cell mdl-cell--2-col">
                    </div>
                    <div class="mdl-cell mdl-cell--8-col">
                      <h4 id="make-degree_title">Enter your Custom Course information here</h4>
                      <div>
                        <ul class="list-style-none">
                        </ul>
                        <table>
                          <tr>
                            <td>
                              Title
                            </td>
                            <td>
                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="custom_title">
                                <label class="mdl-textfield__label" for="custom_title">Title</label>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Description &nbsp &nbsp
                            </td>
                            <td>
                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input class="mdl-textfield__input" type="text" id="custom_description">
                                <label class="mdl-textfield__label" for="custom_description">(Optional description)</label>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Focus &nbsp &nbsp
                            </td>
                            <td>
                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label program_input">
                                <select class="reg_dropdown form-control" name="Focus" id="custom_focus">
                                </select>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              Credits
                            </td>
                            <td>
                              <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label program_input">
                                <select class="reg_dropdown form-control" name="Semesters" id="custom_credit_select">
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
                        <button type="button" class="mdl-button mdl-js-button mdl-button--raised add_button add_custom_course_button">Add Custom Course</button>
                      </div>
                    </div>
                    <div class="mdl-cell mdl-cell--2-col">
                    </div>
                  </div>
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
                        <button type="button" class="mdl-button mdl-js-button mdl-button--raised add_button add_internship_button">Add Internship</button>
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
  </div>
  <!-- If new User, init first instance of Degree -->
  @if($newUser)
  <div id="make_degree" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="false" role="dialog" data-options="close_on_background_click:false">
    <div class="mdl-grid">
      <div class="mdl-cell mdl-cell--2-col">
      </div>
      <div class="mdl-cell mdl-cell--8-col">
        <h4 id="make-degree_title">Hey {{$user->firstName}}! Looks like you are new here. Let's Get you started with S!MVO</h4>
        <h6 id="make-degree_title">NOTE: The ECSESS curriculum is the new one (Introduced in Fall 2016). We are currently working to import the old one into our database. We apologise for the inconvenience.</h6>
        </br>
        <h6>NOTE TO STUDENTS: The Degree Requirements and Rules were sourced from the E-Calendar and data provided by McGill IT. This may not be 100% consistent with the Engineering Faculty or Department requirements. Please ensure to confirm your classes and approve your degree with your advisor.</h6>
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
          <tr id="versionSlot">
          </tr>
          <tr id="descSlot">
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
        {!! Form::submit('Submit', ['class'=> 'mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent
        new_user_submit']) !!} {!! Form::close() !!}
      </div>
      <div class="mdl-cell mdl-cell--2-col">
      </div>
    </div>
  </div>
  <script>
  $(document).ready(function(){$('#make_degree').foundation('reveal', 'open')});
</script>
<script type="text/javascript" src="{{ asset('js/DegreeAjax.js') }}"></script>

@endif 
@if($degreeLoaded)
    <script type="text/javascript" src="{{ asset('js/flowchart.js') }}"></script>
@endif 
@endsection
