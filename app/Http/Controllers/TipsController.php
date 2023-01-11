<?php

namespace App\Http\Controllers;

use App\Models\Tip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TipsController extends Controller
{
    public function index()
    {
        $tips = Tip::all();
        return Inertia::render('Pages/Tips', [
            'permissions' => $tips,
            'filters' => request()->all('search'),
            'can' => [
                'create' => Auth::user()->can('permission create-tip'),
                'edit' => Auth::user()->can('permission edit-tip'),
                'delete' => Auth::user()->can('permission delete-tip'),
            ]
        ]);

    }
}
