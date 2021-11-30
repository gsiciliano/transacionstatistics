<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatisticResource extends JsonResource
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
            "sum" => $this->sum,
            "avg" => $this->avg,
            "max" => $this->max,
            "min" => $this->min,
            "count" => $this->count
        ];
    }
}
