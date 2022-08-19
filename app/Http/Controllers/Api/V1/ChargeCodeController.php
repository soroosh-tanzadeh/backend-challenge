<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ChargeCodes\CreateChargeCodeRequest;
use App\Http\Resources\ChargeCodeResource;
use App\Models\ChargeCode;
use App\Service\ChargeCodeService;
use Illuminate\Http\Request;

class ChargeCodeController extends Controller
{
    private ChargeCodeService $service;


    public function __construct(ChargeCodeService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->response(true, $this->service->getChargeCodes());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateChargeCodeRequest $request)
    {
        $result = $this->service->createChargeCode($request->charge_amount, $request->max_usage, $request->code);
        return $this->response($result instanceof ChargeCode, ChargeCodeResource::make($result));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {
        ChargeCode::query()->where("code", $code)->firstOrFail();

        return $this->response(true, $this->service->getUsageReport($code));
    }
}
