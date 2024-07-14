<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IpAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ip_address' => $this->ip_address,
            'label' => $this->label,
            'created_by' => $this->creator->name,
            'updated_by' => is_null($this->updated_by) ? null : $this->updator->name,
            'created_at' => Carbon::parse($this->created_at)->format('M d, Y h:i A'),
            'updated_at' => Carbon::parse($this->updated_at)->format('M d, Y h:i A'),
        ];
    }
}
