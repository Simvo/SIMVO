@extends('master')
@section('flowchart')

<div class="mdl-grid" style="padding-bottom: 0px">
  <div class="mdl-cell mdl-cell--12-col" style="overflow-x: scroll">
    <div class="mdl-card mdl-shadow--2dp progress_div">

      <table id="progress_table" style="margin: 0 auto; width:100% !important;">
        <thead>
          <tr>
            @foreach ($progress as $key=>$value)
            <td class="progress_cell">{{$key}}</td>
            @endforeach
          </tr>
        </thead>

        <tbody>
          <tr>
            @foreach ($progress as $key=>$value)
            <td class="progress_cell group_cell {{str_replace(" ", "", $key)}}" id="{{ $key }}">{{ $value[0] }}/{{$value[1]}}</td>
            @endforeach
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
        <div id="course_schedule" class="schedule_wrap" style="position: relative; padding-bottom: 50px">

        </div>

        @if (is_null($groupsWithCourses))
          <div class="mdl-grid">
            <fieldset class="complementary_div mdl-cell mdl-cell--6-col">
              <legend>COMPLEMENTARY COURSES</legend>
              <a href="#" id="reveal_complementary_courses" data-reveal-id="comp_courses" class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab" style="background-color: rgba(243, 156, 18,0.6)"><i class="material-icons" style="color: white">add</i></a>
              <div class="schedule_wrap">
                <div class="semester">
                  <div class="draggable complementary_area">
                    <div class="sortable">
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>

            <fieldset class="elective_div mdl-cell mdl-cell--6-col">
              <legend>ELECTIVES</legend>
              <a href="#" id="reveal_elective_courses" data-reveal-id="electives_courses" class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab" style="background-color: rgba(39, 174, 96,0.6)"><i class="material-icons" style="color: white">add</i></a>

              <div class="schedule_wrap">
                <div class="semester">
                  <div class="draggable elective_area">
                    <div class="sortable">
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>
          </div>
        @else
          <div class="mdl-grid">
            <div class="group-container">
              @foreach ($groupsWithCourses as $key=>$value)
                <div class="group-row">
                   <p class="group-title">{{$key}}</p>
                   <div class="draggable">
                     <div class="sortable">
                       @foreach ($value as $course)
                         <div class="custom_card {{ $course[2] }}_course" id="{{ $course[1] }}">
                          <div class="card_content">
                            {{ $course[0] }}&nbsp{{ $course[1] }}
                            <button id="menu_for_{{ $course[1] }}" class="mdl-button mdl-js-button mdl-button--icon">
                              <i class="material-icons">arrow_drop_down</i>
                            </button>
                            {{ $course[2] }}
                            <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect" for="menu_for_{{ $course[1] }}">
                              <li class="mdl-menu__item show_flow" id="show_flow_{{ $course[1] }}">Show Pre-Requisites</li>
                              @if($course[2]!='required')
                                <li class="mdl-menu__item delete" id="remove_{{ $course[1] }}">Remove</li>
                              @endif
                            </ul>
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

      </div>
    </div>
  </div>
</div>
@endsection
