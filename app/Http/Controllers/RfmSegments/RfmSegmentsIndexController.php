<?php

namespace App\Http\Controllers\RfmSegments;

use App\Http\Controllers\Controller;
use App\Http\Resource\RfmSegments\RfmSegmentsResource;
use App\Services\Action\RfmSegmentsIndexAction;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RfmSegmentsIndexController extends Controller
{
    public function __invoke(RfmSegmentsIndexAction $action): AnonymousResourceCollection
    {
        $rfm = $action->run();

        return RfmSegmentsResource::collection($rfm);
    }
}
