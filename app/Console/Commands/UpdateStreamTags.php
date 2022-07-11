<?php

namespace App\Console\Commands;

use App\Models\Tag;
use App\Services\TwitchClientApi;
use Illuminate\Console\Command;

class UpdateStreamTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:tags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Stream Tags';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(TwitchClientApi $api)
    {
        while (true) {
            $data = $api->getStreamTags($data['pagination']['cursor'] ?? null);

            foreach ($data['data'] as $tagData) {
                // Create stream tag model
                Tag::updateOrCreate([
                    'import_id' => $tagData['tag_id'],
                ], [
                    'title' => $tagData['localization_names']['en-us'],
                ]);
            }

            if (!array_key_exists('cursor', $data['pagination'])) {
                break;
            }
        }

        return 0;
    }
}