<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\ContentRequest;
use App\Models\Content;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    public function __construct(private Content $content)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contents = $this->content->paginate(10);

        return inertia()->render('Media/Content', compact('contents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia()->render('Media/ContentCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContentRequest $request)
    {
        $data = $request->validated();
        $data['cover'] = $request->photo && $request->photo instanceof UploadedFile ? $request->photo->store('media', 'public') : null;

        $content = $this->content->create($data);

        return redirect()->route('contents.edit', $content);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('contents.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $content = $this->content->with('videos')->findOrFail($id);

        return inertia()->render('Media/ContentEdit', compact('content'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContentRequest $request, string $id)
    {
        $data = $request->validated();
        $content = $this->content->findOrFail($id);

        if($request->photo) {
            if($content->cover)
                Storage::disk('public')->delete($content->cover);

            $data['cover'] = $request->photo->store('media', 'public');
        }

        $content->update($data);

        redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $content = $this->content->findOrFail($id);
        $content->delete();

        redirect()->back();
    }
}
