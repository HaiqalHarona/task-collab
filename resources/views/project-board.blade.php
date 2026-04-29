@extends('layouts.app')

@section('no-sidebar', true)

@section('content')
    <link rel="stylesheet" href="{{ asset('css/project-board.css') }}">

    <livewire:project-board.header :projectId="$project->id" />
    <livewire:project-board.kanban-board :projectId="$project->id" />
@endsection