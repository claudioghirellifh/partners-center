<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        /** @var \App\Models\Company $company */
        $company = $request->attributes->get('company');

        return view('admin.dashboard', compact('company'));
    }
}

