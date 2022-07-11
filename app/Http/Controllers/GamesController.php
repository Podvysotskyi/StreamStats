<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use Illuminate\Support\Facades\DB;

class GamesController extends Controller
{
    /**
     * Get total number of streams for each game
     * Get viewer count for each game
     */
    public function top() {
        // Get games list
        $gamesSubquery = DB::table('streams')->select('game_id', 'game_name')->distinct();

        // Get stream count for each game
        $gameStreamCountSubquery = DB::table('streams')
            ->select('game_id', DB::raw('COUNT(*) AS streams_count'))
            ->groupBy('game_id');
        
        // Get viewers count for each game
        $gamesViewsCountSubquery = DB::table('streams')
            ->select('game_id', DB::raw('SUM(viewer_count) AS viewers_count'))
            ->groupBy('game_id');

        $gamesTop = DB::query()->fromSub($gamesSubquery, 'games')
            ->select('game_name', 'streams_count', 'viewers_count')
            ->leftJoinSub($gamesViewsCountSubquery, 'game_views', function($join) {
                $join->on('games.game_id', '=', 'game_views.game_id');
            })->leftJoinSub($gameStreamCountSubquery, 'game_streams', function($join) {
                $join->on('games.game_id', '=', 'game_streams.game_id');
            })->orderBy('viewers_count', 'desc')->get();

        return GameResource::collection($gamesTop);
    }
}
