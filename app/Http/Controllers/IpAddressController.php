<?php

namespace App\Http\Controllers;

use App\Http\Requests\IpAddressRequest;
use App\Http\Resources\IpAddressResource;
use App\Models\IpAddress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class IpAddressController extends Controller
{
    public function index() : JsonResource
    {
        $ipAddresses = IpAddress::all();

        return IpAddressResource::collection($ipAddresses);
    }

    public function store(IpAddressRequest $request) : IpAddressResource
    {
        $ipAddress = IpAddress::create([
            'label' => $request->input('label'),
            'ip_address' => $request->input('ip_address'),
        ]);

        return new IpAddressResource($ipAddress);
    }

    public function show(IpAddress $ipAddress) : JsonResponse
    {
        return response()->json(
            $ipAddress->only(['id', 'ip_address', 'label'])
        );
    }

    public function update(IpAddress $ipAddress, IpAddressRequest $request) : Response
    {
        $ipAddress->label = $request->input('label');
        $ipAddress->save();

        return response()->noContent();
    }
}
