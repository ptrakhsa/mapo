<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PopularPlaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DocController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page');
        $path = resource_path('views/docs');
        $files = collect(File::files($path))->map(function ($file) {
            return Str::remove('.blade.php', $file->getFilename());
        });
        $navs = $files->toArray();
        $search = $files->search($page);


        if (is_integer($search)) {
            return view("docs.$files[$search]", compact('navs'));
        }
        return view("docs.doc-0-intro", compact('navs'));
    }
}
