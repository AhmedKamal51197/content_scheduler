<?php

namespace App\Console\Commands;

use App\Enums\PostPlatformStatus;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:process-scheduled';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateNow = Carbon::now();
        $this->info("Processing scheduled posts at {$dateNow}...");
        $posts = Post::where('status', 'scheduled')
            ->where('scheduled_time', '<=', $dateNow)
            ->with('platforms')
            ->get();
        if ($posts->isEmpty()) {
            $this->info('No scheduled posts to process.');
            return;
        }
        foreach ($posts as $post){
           $user = $post->user;
           $activePlatformIds = $user->platforms()
                ->where('status', 1)
                ->pluck('id');
            // $this->info("Platform Id: " . $activePlatformIds);
            if ($activePlatformIds->isEmpty()) {
                $this->info("No active platforms for post ID {$post->id}. Skipping.");
                continue;
            }
            $post->status = 'published';
            $post->save();
            // Update the post status to 'published
            $post->platforms()->updateExistingPivot(
                $activePlatformIds,
                ['status' => PostPlatformStatus::published->value]
            );
        }
    }
}
