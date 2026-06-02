<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TemperatureController extends Controller
{
    public function index()
    {
        // Load the page with an empty result at first
        Log::debug("testing");
        return view('temp', [ 'result' => '', 'input' => '' ]);
    }

    public function convert(Request $request)
    {
        $temp = $request->temperature;
        $type = $request->conversion_type;
        $result = "";
        Log::debug("========== testing ==========");

        // Basic if/else math
        if ($type == 'c_to_f') {
            $result = ($temp * 9/5) + 32 . " °F";
        } else if ($type == 'f_to_c') {
            $result = ($temp - 32) * 5/9 . " °C";
        } else if ($type == 'c_to_k') {
            $result = $temp + 273.15 . " K";
        } else if ($type == 'k_to_c') {
            $result = $temp - 273.15 . " °C";
        }

        Log::info("Temperature :" . $result);

        // Load the page again, but pass the answer to it
        return view('temp', ['result' => $result, 'input' => $temp]);
    }
}

