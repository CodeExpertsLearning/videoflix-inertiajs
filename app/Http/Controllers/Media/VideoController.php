<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Pion\Laravel\ChunkUpload\Handler\ContentRangeUploadHandler;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use App\Jobs\{VideoProcessingJob, CreateThumbFromVideoJob};
use Illuminate\Support\Facades\Bus;

class VideoController extends Controller
{
    public function __construct(private Video $video)
    {
    }

    public function upload(Content $content)
    {
        return inertia()->render('Media/VideoUpload', compact('content'));
    }

    public function store(Content $content, Request $request)
    {
        $video = $content->videos()->create([
            'name' => $request->name,
            'code' => Str::uuid(),
        ]);

        return $video;
    }

    public function update(Content $content, $video, Request $request)
    {
        $video = $content->videos()->findOrFail($video);
        $video->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->back();
    }

    public function destroy(string $id)
    {
        $content = $this->video->findOrFail($id);
        $content->delete();

        redirect()->back();
    }

    public function process(Content $content, $video, Request $request)
    {
        $receiver = new FileReceiver(
            UploadedFile::fake()->createWithContent('file', $request->getContent()),
            $request,
            ContentRangeUploadHandler::class
        );

        $save = $receiver->receive();

        if ($save->isFinished()) {
            $video = $this->video->find($video);
            $video->update([
                'video' => $save->getFile()->storeAs('', Str::uuid(), 'videos')
            ]);

            $makeThumbVideoJobs[] = new CreateThumbFromVideoJob($video);
            $processVideoJobs[]   = new VideoProcessingJob($video);

            Bus::batch($makeThumbVideoJobs)->allowFailures()->dispatch();
            Bus::batch($processVideoJobs)->allowFailures()->dispatch();
        }

        $save->handler();
    }
}
