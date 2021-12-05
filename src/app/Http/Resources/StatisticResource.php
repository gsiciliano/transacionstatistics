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
        $count = !empty($this->resource) ? count($this->resource) : 0;
        $sum = !empty($this->resource) ? array_sum($this->resource) : 0;
        $avg = !empty($this->resource) ? $sum/$count : 0;
        $max = !empty($this->resource) ? max($this->resource) : 0;
        $min = !empty($this->resource) ? min($this->resource) : 0;
        return [
            'sum' => $this->sanitize_float($sum),
            'avg' => $this->sanitize_float($avg),
            'max' => $this->sanitize_float($max),
            'min' => $this->sanitize_float($min),
            'count' => $count
        ];
    }
    private function sanitize_float($float){
        return number_format(round($float,2, PHP_ROUND_HALF_UP), 2, '.', '');;
    }
}
