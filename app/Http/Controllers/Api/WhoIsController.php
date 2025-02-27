<?php

namespace App\Http\Controllers\Api;

use App\Services\WhoIsService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\WhoisInfoRequest;

class WhoIsController extends Controller
{
    /**
     * @var WhoIsService
     */
    private $whoIsService;

    /**
     * WhoIsController constructor.
     * @param WhoIsService $whoIsService
     */
    public function __construct(WhoIsService $whoIsService)
    {
        $this->whoIsService = $whoIsService;
    }
    /**
     * Get whois info for a domain
     *
     * @param WhoisInfoRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\WhoisException
     */
    public function whoIsInfo(WhoisInfoRequest $request)
    {
        $domain = $request->input('domain');
        $cleanDomain = $this->whoIsService->getCleanDomain($domain);
        return Cache::remember("whois_$cleanDomain", now()->addDays(1), function () use ($cleanDomain) {
            $info = $this->whoIsService->lookup($cleanDomain);

            return response()->json($info);
        });
    }
}
