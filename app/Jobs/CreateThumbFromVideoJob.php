<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class CreateThumbFromVideoJob implements ShouldQueue
{
    use Dispatchable, Batchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private Video $video)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $thumb = 'thumbnails/' . $this->video->code . '/thumbnail.png';

        FFMpeg::fromDisk('videos')
            ->open($this->video->video)
            ->getFrameFromSeconds(5)
            ->export()
            ->toDisk('public')
            ->save($thumb);

        $this->video->update([
            'thumb' => $thumb
        ]);

        //VideoThumbGenerated::dispatch($this->video);
    }
}
