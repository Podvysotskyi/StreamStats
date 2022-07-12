<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Stream;
use App\Models\Tag;
use App\Services\TwitchClientApi;
use Illuminate\Console\Command;

class UpdateStreams extends Command
{
    private static $streamsCount = 1000;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:streams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Stream Data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(TwitchClientApi $api)
    {
        $ids = [];

        while (count($ids) < static::$streamsCount) {
            $result = $api->getStreams($result['pagination']['cursor'] ?? null);
            $data = $result['data'];
            shuffle($data);

            foreach ($data as $streamData) {
                // Create stream model
                $stream = Stream::updateOrCreate([
                    'import_id' => $streamData['id'],
                ], [
                    'title' => $streamData['title'],
                    'game_id' => $streamData['game_id'],
                    'game_name' => $streamData['game_name'],
                    'user_id' => $streamData['user_id'],
                    'user_name' => $streamData['user_name'],
                    'viewer_count' => $streamData['viewer_count'],
                    'started_at' => Carbon::parse($streamData['started_at']),
                ]);

                $ids[] = $stream->id;

                // Update stream tags
                if (count($streamData['tag_ids'] ?? []) > 0) {
                    $tags = Tag::query()->whereIn('import_id', $streamData['tag_ids'])->pluck('id');
                    $stream->tags()->sync($tags);
                } else {
                    $stream->tags()->sync([]);
                }

                if (count($ids) >= static::$streamsCount) {
                    break;
                }
            }
        }

        // Remove old streams from database
        Stream::query()->whereNotIn('id', $ids)->delete();

        return 0;
    }
}