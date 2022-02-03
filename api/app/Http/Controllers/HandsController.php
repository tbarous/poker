<?php

namespace App\Http\Controllers;

use App\FileParser;
use Error;

class HandsController extends Controller
{
    public function upload()
    {
        $file = request()->hands;

        try {
            FileParser::parse($file);
        } catch (Error $error) {
            dd($error);

            return response(['error' => $error]);
        }

        return response(['Success'], 200);
    }
}
