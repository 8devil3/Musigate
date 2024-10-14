<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VideoController extends Controller
{
    public function edit(): Response
    {
        $videos = Video::where('studio_id', auth()->user()->studio->id)->pluck('url')->toArray();

        return Inertia::render('Backoffice/Studio/Videos', compact('videos'));
    }

    public function update(Request $request): RedirectResponse
    {
        // regex per validare link YT ed estrarre l'id. Origine: https://gist.github.com/afeld/1254889#gistcomment-1253992
        $yt_id_regex = '/(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/mi';

        $request->validate([
            'videos.*' => ['required', 'url:https', 'max:255' ,'regex:' . $yt_id_regex],
        ]);

        $current_studio_id = auth()->user()->studio->id;

        Video::where('studio_id', $current_studio_id)->delete();

        foreach ($request->videos as $video) {
            preg_match($yt_id_regex, $video, $matches);

            if(!empty($matches[1])){
                Video::create([
                    'studio_id' => $current_studio_id,
                    'url' => $video,
                    'yt_id' => $matches[1]
                ]);
            }
        }

        return back()->with('success', 'Video salvati');
    }
}
