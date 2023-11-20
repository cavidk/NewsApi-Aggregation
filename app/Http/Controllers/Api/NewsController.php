<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Providers\News\ApiOrgProvider;
use App\Providers\News\GuardianProvider;
use App\Providers\News\NewYorkTimesProvider;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function search(Request $request){
        $q = $request->get('q');
        $source = $request->get('source');

        try {
            return match ($source){
                'newsapiorg' => (new ApiOrgProvider())->search($q),
                'guardian' => (new GuardianProvider())->search($q),
                'new_york_times' => (new NewYorkTimesProvider())->search($q),
            };
        }catch (\UnhandledMatchError $e){
            return [
                'status' => 'error',
                'message' => 'Source not found',
            ];
        }
    }
    public function dbSearch(Request $request){
        $q = $request->get('q');
        $source = $request->get('source');

        try {
            return match ($source){
                'newsapiorg' => (new ApiOrgProvider())->dbSearch($q),
                'guardian' => (new GuardianProvider())->dbSearch($q),
//                'new_york_times' => (new NewYorkTimesProvider())->dbSearch($q),
            };
        }catch (\UnhandledMatchError $e){
            return [
                'status' => 'error',
                'message' => 'Source not found',
            ];
        }
    }
}
