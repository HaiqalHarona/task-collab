<?php

use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('project.{projectId}', function ($user, $projectId) {

    $data = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'avatar' => $user->avatar
            ? (\Illuminate\Support\Str::startsWith($user->avatar, ['http://', 'https://']) ? $user->avatar : \Illuminate\Support\Facades\Storage::url($user->avatar))
            : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=' . substr(md5($user->email), 0, 6) . '&color=fff&size=32&bold=true'
    ];
    return $data;
});
