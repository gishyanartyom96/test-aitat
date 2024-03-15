<?php

namespace App\Services\Action;

use App\Services\Api\Vivarolls\VivaRollsApi;
use Illuminate\Support\Collection;

class RfmSegmentsIndexAction
{
    private VivaRollsApi $api;

    private const RUB_CURRENCY = '₽';

    public function __construct()
    {
        $this->api = VivaRollsApi::getInstance();
    }

    public function run(): Collection
    {
        $loginData = $this->api->login();
        $this->initRequestData($loginData);
        $data = $this->api->reports();

        $id = $this->getSegmentRfmId($data);

        $report = $this->api->report($id);

        return $this->prepareReportData($report);
    }

    private function initRequestData(array $data): void
    {
        $this->api->setType($data['token_type'])
            ->setToken($data['access_token']);

        $this->api->initHeaders();
    }

    private function getSegmentRfmId(array $data): string
    {
        $collect = collect($data['grouped']['General']);

        $segmentRfm = $collect->where('name', 'get_segment_rfm')->first();

        return $segmentRfm['id'];
    }

    private function prepareReportData(array $data): Collection
    {
        $segments = [];

        foreach ($data['aggregations']['segments']['buckets'] as $key => $segment) {
            $segments[$key]['name'] = $segment['key'];
            $segments[$key]['count_clients'] = $segment['doc_count'];
            $segments[$key]['count_orders'] = $segment['orders']['value'];
            $segments[$key]['sum_orders'] = $segment['total']['value'];
            $segments[$key]['percentage_of_total'] = floor(($segment['doc_count'] / $data['total']) * 100);
            $segments[$key]['currency'] = self::RUB_CURRENCY;
        }

        return collect($segments);
    }
}
