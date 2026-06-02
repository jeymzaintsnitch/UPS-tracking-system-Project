<?php

namespace App\Http\Controllers;

use App\Models\AuthenticationLog;
use Illuminate\Http\Request;

class AuthenticationLogController extends Controller
{
    public function index()
    {
        $query = AuthenticationLog::with('user')->orderBy('created_at', 'desc');

        $logs = $query->paginate(20);

        return view('authentication_logs.index', compact('logs'));
    }
}
