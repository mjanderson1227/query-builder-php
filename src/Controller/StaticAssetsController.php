<?php

namespace App\Controller;

use Framework\Request;
use Framework\Response;

const STATIC_ASSETS = '../../static';

class StaticAssetsController
{
    /**
     * Serve a file from the static assets directory
     * @param Request $request A handle to the incoming request.
     */
    public function serve(Request $request): never
    {
        $file = $request->query('file', null);

        $path = implode('/', [__DIR__, STATIC_ASSETS, $file]);
        $file = file_get_contents($path);

        if (! $file) {
            Response::send('Could not find static file', 404);
        }

        Response::send($file, 200);
    }
}
