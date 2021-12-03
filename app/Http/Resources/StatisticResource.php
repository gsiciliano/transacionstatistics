<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatisticResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $count = count($this->resource);
        $sum = array_sum($this->resource);
        $avg = $sum/$count;
        $max = max($this->resource);
        $min = min($this->resource);
        return [
            'sum' => round($sum,2, PHP_ROUND_HALF_UP),
            'avg' => round($avg,2, PHP_ROUND_HALF_UP),
            'max' => round($max,2, PHP_ROUND_HALF_UP),
            'min' => round($min,2, PHP_ROUND_HALF_UP),
            'count' => $count
        ];
    }
}
