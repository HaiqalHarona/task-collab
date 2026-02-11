@extends('layouts.app')

@section('title', 'Overview')

@section('content')
    <h1>Welcome back!</h1>

    <div
        style="background: white; padding: 20px; border-radius: 8px; margin-top: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <p>This is where your task list will go.</p>
    </div>

    <div style="display: flex; gap: 20px; margin-top: 20px;">
        <div style="flex: 1; background: #e8f4f8; padding: 20px; border-radius: 8px;">
            <h3>Pending Tasks</h3>
            <p style="font-size: 2rem; font-weight: bold;">12</p>
        </div>
        <div style="flex: 1; background: #e8f8f0; padding: 20px; border-radius: 8px;">
            <h3>Completed</h3>
            <p style="font-size: 2rem; font-weight: bold;">45</p>
        </div>
    </div>
@endsection
