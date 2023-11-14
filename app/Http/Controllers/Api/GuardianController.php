<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use http\Env\Request;

class GuardianController extends Controller
{
    public function search(Request $request)
    {
        $type = $request->get('type');
        $webUrl = $request->get('webUrl');

        try {
            return match ($type) {
                'guardian' => (new GuardianProvider())->search($webUrl),
            };
        } catch (\UnhandledMatchError $e) {
            return [
                'status' => 'error',
                'message' => 'Source not found',
            ];
        }
    }
}
