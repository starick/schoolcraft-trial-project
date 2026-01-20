<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorksheetStoreRequest;
use App\Http\Resources\WorksheetResource;
use App\Models\User;
use App\Models\Worksheet;
use App\Services\JWTService;

class WorksheetController extends Controller
{
    public function store(WorksheetStoreRequest $request, JWTService $jwtService)
    {
        $validated = $request->validated();
        $jwtContent = $jwtService->decode($request->bearerToken());

        abort_unless($jwtContent['email'] === $validated['email'], 403, 'Emails do not match.');

        abort_unless($jwtContent['scope'] === 'share:worksheet', 403, 'Invalid token scope.');

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
