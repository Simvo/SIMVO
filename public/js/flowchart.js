$(document).ready(function(){

  $( '.sortable' ).sortable( {
    start: function( event, ui ) {
      is_dragging = true;
    },
    connectWith: ".sortable",
    stop: function( event, ui ) {
      is_dragging = false;
    }
  } ).on( 'mousemove', function( e ) {
  } );

});

$( ".sortable" ).sortable( {
    placeholder: 'object_ouline hvr-pulse',
    cancel: '.credit_counter, .error_course_message',
  } );


  // Remove Instance of Schedule

  $( ".delete" ).unbind().click( function( e ) {
    var target = $( this ).attr( 'id' );

    var id_array = target.split( '_' );
    var id = id_array[ 1 ];

    //console.log(id);

    $.ajax( {
      type: "post",
      url: "/flowchart/remove-sched",
      data: {
        id: id
      },
      success: function( data ) {
        var response = JSON.parse( data );
        console.log( response );

        // Update Credits in Semester
        // This is VERY UGLY only for DEMO
        $( e.target ).parent().parent().parent().parent().parent().children( '.credit_counter' ).children( '.credit_counter_num' ).text( 'CREDITS: ' + response[ 2 ] );


        // Update Progress
        console.log( response[ 1 ] );

        for ( var property in response[ 1 ] ) {
          if ( response[ 1 ].hasOwnProperty( property ) ) {
            console.log( property );
            console.log( response[ 1 ][ property ] );
            //console.log($("." + property.split(" ").join("")));

            $( "td[id='" + property + "']" ).text( "" + response[ 1 ][ property ][ 0 ] + "/" + response[ 1 ][ property ][ 1 ] );
          }
        }

        // Remove Errors related to deleted course
        $( "." + "error_adding_" + response[ 4 ].replace( " ", "" ) ).remove();

        // Remove Course from DOM
        $( '.sortable #' + id ).remove();
      }
    } )
  } );

  // Show Pre Reqs

  $( ".show_flow" ).unbind().click( function() {
    var target = $( this ).attr( 'id' );

    var id_array = target.split( '_' );

    var id = id_array[ 2 ];

    console.log( id );

    $.ajax( {
      type: "post",
      url: "/flowchart/show-flow",
      data: {
        id: id
      },
      success: function( data ) {

        var response = JSON.parse( data );

        console.log( response );

        $( '#pre_req_modal' ).empty();

        if ( response[ 1 ].length ) {
          var content = '<h4 style="text-align:center">Pre Requisites For ' + response[ 0 ] + '</h4>';
          content += "<table class='mdl-data-table' style='margin: 0 auto'>";
          content += "<thead>";
          content += "<tr>";
          content += "<th>Course Number</th>";
          content += "<th>Course Name</th>";
          content += "<th>Credits</th>";
          content += "</tr>";
          content += "</thead>";
          content += "<tbody>";

          for ( var i = 0; i < response[ 1 ].length; i++ ) {
            content += '<tr>';
            content += '<td class="mdl-data-table__cell--non-numeric class_name">' + response[ 1 ][ i ][ 0 ] + '</td>';
            content += '<td>' + response[ 1 ][ i ][ 1 ] + '</td>';
            content += '<td>' + response[ 1 ][ i ][ 2 ] + '</td>';
            content += '</tr>';
          }

          content += '</tbody>';
          content += '</table>';

        } else {
          var content = '<h4 style="text-align:center">No Pre Requisites For ' + response[ 0 ] + '</h4>';
        }

        $( '#pre_req_modal' ).append( content );

        if ( response[ 3 ].length ) {
          var content = '<h4 style="text-align:center">Courses That Have ' + response[ 0 ] + ' As A Pre-Requisite</h4>';
          content += "<table class='mdl-data-table' style='margin: 0 auto'>";
          content += "<thead>";
          content += "<tr>";
          content += "<th>Course Number</th>";
          content += "<th>Course Name</th>";
          content += "<th>Credits</th>";
          content += "</tr>";
          content += "</thead>";
          content += "<tbody>";

          for ( var i = 0; i < response[ 3 ].length; i++ ) {
            content += '<tr>';
            content += '<td class="mdl-data-table__cell--non-numeric class_name">' + response[ 3 ][ i ][ 0 ] + '</td>';
            content += '<td>' + response[ 3 ][ i ][ 1 ] + '</td>';
            content += '<td>' + response[ 3 ][ i ][ 2 ] + '</td>';
            content += '</tr>';
          }

          content += '</tbody>';
          content += '</table>';

        } else {
          var content = '<h4 style="text-align:center">No Courses Have ' + response[ 0 ] + ' As A Pre-Requisite</h4>';
        }




        content += '<a class="close-reveal-modal" aria-label="Close">&#215;</a>';

        $( '#pre_req_modal' ).append( content );

        var overview = '<h4 style="text-align:center">Course Overview</h3>';
        overview += '<p style="text-align:center">' + response[ 4 ] + '</p>';

        $( '#pre_req_modal' ).append( overview );

        $( '#pre_req_modal' ).foundation( 'reveal', 'open' );
      }
    } )
  } );




  // Add Complentary Course

  $( ".add_comp_course_button" ).click( function() {
    var selected = [];

    console.log( "Adding complementary course" );

    $( ".complentary_table_body tr" ).each( function() {

      if ( $( this ).hasClass( 'is-selected' ) ) {
        var new_class = [];

        selected.push( [ $( this ).attr( 'id' ), $( this ).find( 'td.class_name' ).text() ] );
        $( this ).remove();
      }
    } );
    console.log( selected );

    for ( var i = 0; i < selected.length; i++ ) {

      $.ajax( {
        type: "post",
        url: "/flowchart/add-to-schedule",
        data: {
          semester: "complementary_course",
          class_id: selected[ i ][ 0 ],
          class_name: selected[ i ][ 1 ],
        },
        success: function( data ) {
          var response = JSON.parse( data );
          console.log( response );

          if ( response === 'Error' ) {
            //error handler
          } else {
            var comp_course = "<div class='custom_card complementary_course' id=" + response[ 0 ] + ">";
            comp_course += "<div class='card_content'>";
            comp_course += response[ 1 ];
            comp_course += "<button id='menu_for_" + response[ 0 ] + "' class='mdl-button mdl-js-button mdl-button--icon'>";
            comp_course += "<i class='material-icons'>arrow_drop_down</i>";
            comp_course += "</button>" + response[ 2 ];
            //comp_course+="</button>";
            comp_course += "<ul class='mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect' for='menu_for_" + response[ 0 ] + "''>";
            comp_course += "<li class='mdl-menu__item delete'>Remove</li>";
            comp_course += "<li class='mdl-menu__item show_flow'>View Flow</li>";
            comp_course += "</ul>";
            comp_course += "</div>";
            comp_course += "</div>";

            $( ".complementary_area .sortable" ).append( comp_course );
          }
        }
      } )

    }

    $( '#comp_courses' ).foundation( 'reveal', 'close' );
    location.reload();
    //componentHandler.upgradeAllRegistered();
  } );

  // Add elective

  $( ".add_elective_course_button" ).click( function() {
    var selected = [];

    console.log( "Adding elective course" );

    $( ".elective_table_body tr" ).each( function() {
      //console.log($(this).hasClass('is-selected'));
      if ( $( this ).hasClass( 'is-selected' ) ) {
        var new_class = [];
        //console.log($(this).attr('id'));
        //console.log($(this).find('td.class_name').text());
        selected.push( [ $( this ).attr( 'id' ), $( this ).find( 'td.class_name' ).text() ] );
        $( this ).remove();
      }
    } );
    console.log( selected );

    for ( var i = 0; i < selected.length; i++ ) {
      $.ajax( {
        type: "post",
        url: "/flowchart/add-to-schedule",
        data: {
          semester: "elective_course",
          class_id: selected[ i ][ 0 ],
          class_name: selected[ i ][ 1 ],
        },
        success: function( data ) {
          var response = JSON.parse( data );

          if ( response === 'Error' ) {
            //error handler
          } else {
            var comp_course = "<div class='custom_card elective_course' id=" + response[ 0 ] + ">";
            comp_course += "<div class='card_content'>";
            comp_course += response[ 1 ];
            comp_course += "<button id='menu_for_" + response[ 0 ] + "' class='mdl-button mdl-js-button mdl-button--icon'>";
            comp_course += "<i class='material-icons'>arrow_drop_down</i>";
            comp_course += "</button>" + response[ 2 ];;
            //comp_course+="</button>";
            comp_course += "<ul class='mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect' for='menu_for_" + response[ 0 ] + "''>";
            comp_course += "<li class='mdl-menu__item delete'>Remove</li>";
            comp_course += "<li class='mdl-menu__item show_flow'>View Flow</li>";
            comp_course += "</ul>";
            comp_course += "</div>";
            comp_course += "</div>";

            $( ".elective_area .sortable" ).append( comp_course );
          }
        }
      } )

    }

    $( '#electives_courses' ).foundation( 'reveal', 'close' );
    location.reload();
  } );


  // Pulse effect to selected groups

  $( ".group_cell" ).mouseover( function() {

    var group = $( this ).attr( 'id' );

    console.log( group );

    $.ajax( {
      type: 'post',
      url: '/flowchart/get-group-classes',
      data: {
        group: group
      },
      success: function( data ) {

        var response = JSON.parse( data );
        console.log( response.length );

        for ( var i = 0; i < response.length; i++ ) {
          $( "#" + response[ i ] ).addClass( 'hvr-pulse' );
        }

      }
    } )
  } );

  // Remove Pulse when Mouse is removed

  $( ".group_cell" ).mouseout( function() {

    var group = $( this ).attr( 'id' );

    $.ajax( {
      type: 'post',
      url: '/flowchart/get-group-classes',
      data: {
        group: group
      },
      success: function( data ) {

        var response = JSON.parse( data );
        console.log( response.length );

        for ( var i = 0; i < response.length; i++ ) {
          $( "#" + response[ i ] ).removeClass( 'hvr-pulse' );
        }

      }
    } )
  } );
