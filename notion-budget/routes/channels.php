<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Project;

Broadcast::channel('project.{projectId}', function ($user, $projectId) {
    \Log::info('Presence channel auth HIT', [
        'user_id' => $user->id,
        'user_name' => $user->name,
        'projectId' => $projectId,
    ]);

    $data = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'avatar' => $user->avatar
            ? (\Illuminate\Support\Str::startsWith($user->avatar, ['http://', 'https://']) ? $user->avatar : \Illuminate\Support\Facades\Storage::url($user->avatar))
            : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=' . substr(md5($user->email), 0, 6) . '&color=fff&size=32&bold=true'
    ];

    \Log::info('Presence channel returning data', $data);

    return $data;
});
