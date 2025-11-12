<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndividualDisbursementResource extends JsonResource
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
            'type' => $this->type,
            'amount' => $this->amount,
            'company' => $this->company,
            'employee' => $this->employee,
            'status' => $this->status,
            'allowance' => $this->allowance,
            'created_at' => Carbon::parse(
                $this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
