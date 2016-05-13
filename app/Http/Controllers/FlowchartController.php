<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Http\Requests;

class FlowchartController extends Controller
{
    public function generateFlowChart()
    {
      $user=Auth::User();

      return view('flowchart', [
        'user'=>$user,
      ]);
    }
}
