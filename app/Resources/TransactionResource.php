<?php

namespace App\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{

    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "amount" => $this->amount,
            "timestamp" => Carbon::createFromTimeString($this->timestamp)->toIso8601ZuluString()
        ];
    }
}
