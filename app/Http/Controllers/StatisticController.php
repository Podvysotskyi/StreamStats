<?php

namespace App\Http\Controllers;

use App\Http\Resources\StreamResource;
use App\Models\Stream;
use App\Models\Tag;
use App\Services\TwitchUserApi;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    private TwitchUserApi $api;

    public function __construct(TwitchUserApi $api) {
        $this->api = $api;
    }

    /**
     * Get user statistics data
     */
    public function statistics(Request $request) {
        $followedStreams = $this->getUserFolowedStreams($request->user());
        $followedTopStreams = $this->getFollowedTopStreams($followedStreams);

        return response()->json([
            'followed_top_streams' => StreamResource::collection($followedTopStreams),
            'lowest_followed_stream_for_top' => $this->getLowestFollowedStreamViewersCountForTop($followedStreams, $followedTopStreams),
            'shared_stream_tags' => $this->getUserSharedTags($followedStreams),
        ]);
    }

    /**
     * Get list of streams followed by user
     */
    private function getUserFolowedStreams($user) {
        return Cache::remember("{$user->id}:streams", 300, function() use ($user) {
            $streams = [];
            while (true) {
                $data = $this->api->getFollowedStreams($user, $data['pagination']['cursor'] ?? null);
        
                $streams = array_merge($streams, $data['data']);
        
                if (!array_key_exists('cursor', $data['pagination'] ?? [])) {
                    break;
                }
            }
            return $streams;
        });
    }

    /**
     * Which of the top 1000 streams is the logged in user following
     */
    private function getFollowedTopStreams($followedStreams) {
        if (count($followedStreams) === 0) {
            return [];
        }

        $userStreamIds = array_map(fn($stream) => $stream['id'], $followedStreams);
        $streams = Stream::query()
            ->whereIn('import_id', $userStreamIds)
            ->orderBy('viewer_count', 'desc')
            ->get()->keyBy('import_id');

        return $streams;
    }

    /**
     * How many viewers does the lowest viewer count stream that the logged in user is following need to gain in order to make it into the top 1000
     */
    private function getLowestFollowedStreamViewersCountForTop($followedStreams, $streams): int {
        if (count($followedStreams) === 0) {
            return 0;
        }
        
        $lowestViewedTopStreamCount = DB::table('streams')->selectRaw('MIN(viewer_count) AS value')->first()->value;
        $lowestViewedStream = null;

        foreach ($followedStreams as $streamData) {
            if ($streams->has($streamData['id'])) continue;

            if ($streamData['viewer_count'] >= $lowestViewedTopStreamCount) continue;
            
            if (is_null($lowestViewedStream)) {
                $lowestViewedStream = $streamData;
            } else if ($lowestViewedStream['viewer_count'] > $streamData['viewer_count']) {
                $lowestViewedStream = $streamData;
            }
        }

        if (is_null($lowestViewedStream)) {
            return 0;
        }

        return $lowestViewedTopStreamCount - $lowestViewedStream['viewer_count'];
    }

    /**
     * Which tags are shared between the user followed streams and the top 1000 streams?
     */
    private function getUserSharedTags($followedStreams): Collection {
        $userTags = [];
        foreach ($followedStreams as $streamData) {
            array_push($userTags, ...$streamData['tag_ids']);
        }

        if (count($userTags) === 0) {
            return [];
        }
 
        $userTags = array_unique($userTags);
        return Tag::whereHas('streams')
            ->whereIn('import_id', $userTags)
            ->pluck('title');
    }
}