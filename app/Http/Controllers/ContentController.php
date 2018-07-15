<?php

namespace App\Http\Controllers;

use App\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function preview(Request $request)
    {
        return Content::toHTML($request->get('markdown'));
    }
}
