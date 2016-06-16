function get_semester_letter( semester )
{
  var term = semester.substring( 0, 4 );

  var result = "";

  switch ( semester.substring( 4, 6 ) ) {
    case '09':
      result += "FALL ";
      break;
    case '01':
      result += "WINTER ";
      break;
    case '05':
      result += "SUMMER ";
      break;
  }

  result += term;

  return result;
}

function get_next_semester( current )
{
    var semester = "";
    var year = 0;

    var term = current.substring( 4, 6 );
    var curr_year = parseInt( current.substring( 0, 4 ) );

    switch ( term )
    {
      case '01':
        semester = '05';
        year = curr_year;
        break;
      case '05':
        semester = '09';
        year = curr_year;
        break;
      case '09':
        semester = '01';
        year = curr_year + 1;
        break;
    }

    semester = year.toString() + semester;
    return semester;
  }

  function get_semester( semester )
  {
    var term = semester.split( " " );

    var result = term[ 1 ];

    switch ( term[ 0 ] )
    {
      case 'FALL':
        result += "09";
        break;
      case 'WINTER':
        result += "01";
        break;
      case 'SUMMER':
        result += "05";
        break;
    }

    return result;
  }
