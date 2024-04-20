<?php

namespace App\Http\Controllers\Actions;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;

class PlayerAction extends Controller
{
    public function __invoke(Content $content)
    {
        $videos = $content->activeVideos()->get();

        return inertia()->render('Player', compact('content', 'videos'));
    }
}
