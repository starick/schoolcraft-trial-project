<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorksheetStoreRequest;
use App\Http\Resources\WorksheetResource;
use App\Models\User;
use App\Models\Worksheet;

class WorksheetController extends Controller
{
    public function store(WorksheetStoreRequest $request)
    {
        $validated = $request->validated();
        // /** @var User $user */
        // $user = auth()->user();

        // TODO get JWT email and compare with request email (service)
        $jwtEmail = 'asdasd';

        abort_unless($jwtEmail === $validated['email'], 403, 'Emails do not match.');

        $file = $request->file('file');
        $filePath = $file->store('worksheets', 'private');

        $user = User::firstOrCreate(
            ['email' => $validated['email']],
        );

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $filePath,
            'user_id' => $user->id,
        ];

        $worksheet = Worksheet::create($data);

        return response()->json(new WorksheetResource($worksheet), 201);
    }
}
