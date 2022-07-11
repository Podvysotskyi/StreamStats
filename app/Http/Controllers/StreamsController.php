<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopStreamsRequest;
use App\Http\Resources\StreamResource;
use App\Models\Stream;

class StreamsController extends Controller
{
    /**
     * Get top streams by viewer count
     */
    public function top(TopStreamsRequest $request) {
        $streams = Stream::query()
            ->orderBy('viewer_count', $request->get('sort', 'desc'))
            ->limit(100)->get();

        return StreamResource::collection($streams);
    }

    /**
     * Get total number of streams by their start time (rounded to the nearest hour)
     * Get median number of viewers for all streams
     */
    public function statistics() {
        $streamsStatistics = [];

        // Count of streams
        $count = 0;
 
        // Total viewers count
        $total_views = 0;

        // Group streams by it's started hour
        foreach (Stream::query()->cursor() as $stream) {
            $streamDate = $stream->started_at->format('Y-m-d H:00:00');

            if (array_key_exists($streamDate, $streamsStatistics)) {
                $streamsStatistics[$streamDate]['streams']++;
            } else {
                $streamsStatistics[$streamDate] = [
                    'streams' => 1,
                    'date' => $stream->started_at->format('jS F'),
                    'time' => $stream->started_at->format('H:00'),
                ];
            }

            $count++;
            $total_views += $stream->viewer_count;
        }

        krsort($streamsStatistics);

        return response()->json([
            'data' => array_values($streamsStatistics),
            'median_viewers_count' => $total_views / $count,
        ]);
    }
}
