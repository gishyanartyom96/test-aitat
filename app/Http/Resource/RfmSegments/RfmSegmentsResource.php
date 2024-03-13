<?php

namespace App\Http\Resource\RfmSegments;

use Illuminate\Http\Resources\Json\JsonResource;

class RfmSegmentsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->resource['name'],
            'count_clients' => $this->resource['count_clients'],
            'count_orders' => $this->resource['count_orders'],
            'sum_orders' => $this->resource['sum_orders'],
            'percentage_of_total' => $this->resource['percentage_of_total'],
            'currency' => $this->resource['currency'],
        ];
    }
}
