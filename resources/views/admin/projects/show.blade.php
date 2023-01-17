@extends('layouts.admin')

@section('content')


<!-- if there's an image, show it; otherwise, show a placeholder -->
@if($project->cover_image)
<img class="img-fluid" src="{{asset('storage/' . $project->cover_image)}}" alt="">
@else
<div class="placeholder p-5 bg-secondary">Placeholder</div>

@endif
<h1>{{$project->title}}</h1>
<h5>{{$project->slug}}</h5>
<div class="description">
    {{$project->description}}
</div>
<div class="type">
    <strong>Type:</strong>
    {{ $project->type ? $project->type->name : 'Without type'}}
</div>

<div class="technologies">
    <strong>Technologies:</strong>
    @if(count($project->technologies) > 0 )


    @foreach ($project->technologies as $technology)
    <span>#{{$technology->name}} </span> <!-- #css #php #js -->
    @endforeach

    @else
    <span>No technology associated to the current project</span>
    @endif

</div>
@endsection