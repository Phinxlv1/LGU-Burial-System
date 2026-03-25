<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ChangelogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('model')) {
            $query->where('model_type', $request->model);
        }
        if ($request->filled('user')) {
            $query->where('user_id', $request->user);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('description', 'like', '%'.$request->search.'%')
                  ->orWhere('model_label', 'like', '%'.$request->search.'%');
            });
        }

        $logs  = $query->paginate(30)->withQueryString();
        $users = \App\Models\User::orderBy('name')->pluck('name', 'id');

        return view('superadmin.changelog', compact('logs', 'users'));
    }
}