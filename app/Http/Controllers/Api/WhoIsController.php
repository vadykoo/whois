<?php

namespace App\Http\Controllers\Api;

use App\Services\WhoIsService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\WhoisInfoRequest;

class WhoIsController extends Controller
{
    private $whoIsService;

    public function __construct(WhoIsService $whoIsService)
    {
        $this->whoIsService = $whoIsService;
    }

    public function whoIsInfo(WhoisInfoRequest $request)
    {
        $domain = $request->input('domain');
        $cleanDomain = $this->whoIsService->getCleanDomain($domain);
        return Cache::remember("whois_$cleanDomain", now()->addDays(1), function () use ($domain) {
            $info = $this->whoIsService->lookup($domain);

            return response()->json($info);
        });
    }
}
