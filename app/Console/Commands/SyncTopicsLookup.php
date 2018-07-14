<?php

namespace Aham\Console\Commands;

use Illuminate\Console\Command;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\TopicsLookup;

class SyncTopicsLookup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aham:sync_topics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $topics = Topic::with('parent.parent.parent')->topic()->get();

        $topic_lookup = [];

        foreach ($topics as $topic) {
            $topic_lookup = [];

            if (is_null($topic->parent)) {
                continue;
            }

            if (is_null($topic->parent->parent)) {
                continue;
            }

            if (is_null($topic->parent->parent->parent)) {
                continue;
            }

            $topic_lookup['topic_id'] = $topic->id;
            $topic_lookup['status'] = $topic->status;
            $topic_lookup['sub_category_id'] = $topic->parent->id;
            $topic_lookup['subject_id'] = $topic->parent->parent->id;
            $topic_lookup['category_id'] = $topic->parent->parent->parent->id;

            $lookup = TopicsLookup::firstOrCreate(['topic_id' => $topic->id]);

            $lookup->fill($topic_lookup);
            $lookup->save();
        }

        $topics = Topic::onlyTrashed()->get();

        foreach ($topics as $topic) {
            $lookup = TopicsLookup::where(['topic_id' => $topic->id])->delete();
        }
    }
}
