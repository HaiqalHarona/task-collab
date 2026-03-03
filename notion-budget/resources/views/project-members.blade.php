@extends('layouts.app')

@section('content')
    {{-- Members Page --}}
    <livewire:project-board.members :projectId="$project->id" />
@endsection