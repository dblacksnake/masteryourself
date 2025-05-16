<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ParticipantController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'email' => 'required|email|unique:participants,email',
            'instagram_tag' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'video' => 'nullable|file|mimetypes:video/mp4,video/mpeg,video/quicktime|max:10240',
            'photo' => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif|max:2048',
            'cv' => 'nullable|file|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:2048',
        ]);

        $data = $validated;

        if ($request->hasFile('video')) {
            $data['video_path'] = $request->file('video')->store('videos', 'public');
        }

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('photos', 'public');
        }

        if ($request->hasFile('cv')) {
            $data['cv_path'] = $request->file('cv')->store('cvs', 'public');
        }

        $participant = Participant::create($data);

        return response()->json(['message' => 'Inscription réussie !'], 201);
    }
}
