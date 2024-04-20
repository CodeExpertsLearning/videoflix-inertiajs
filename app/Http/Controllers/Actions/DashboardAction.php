<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;

class DashboardAction extends Controller
{
    public function __invoke(Content $content)
    {
        $contents = $content->get()->groupBy('type');

        return inertia()->render('Dashboard', compact('contents'));
    }
}
