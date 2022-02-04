<?php

namespace App\Http\Controllers;

use App\FileParser;
use Doctrine\DBAL\Exception;

class HandsController extends Controller
{
    /**
     * @return mixed
     * @throws \Exception
     */
    public function upload()
    {
        $file = request()->hands;

        try {
            $fileParser = new FileParser();

            $fileParser->parse($file);

            $data = ['Successfully inserted hands.'];

            return response()->json($data, 200);
        } catch (Exception $e) {
            $data = ['error' => $e->getMessage()];

            return response()->json($data);
        }
    }
}
