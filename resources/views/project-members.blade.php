@extends('layouts.app')

@section('no-sidebar', true)

@section('content')
    {{-- Members Page --}}
    <livewire:project-board.members :projectId="$project->id" />
@endsection