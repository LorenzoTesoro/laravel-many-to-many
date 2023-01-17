@extends('layouts.admin')

@section('content')

<div class="container mb-5">
    <h1 class="py-5">Update Project: {{$project->title}}</h1>

    @if ($errors->any())

    <div class="alert alert-danger" role="alert">
        <ul>
            @foreach ($errors->all() as $error )
            <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>


    @endif

    <form action="{{route('admin.projects.update', $project->slug)}}" method="post" class="card p-3" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control @error('cover_image') is-invalid @enderror" placeholder="" aria-describedby="titleHlper" value="{{old('title', $project->title)}}">
            <small id="titleHlper" class="text-muted">Add the project title here</small>
        </div>
        @error('title')
        <div class="alert alert-danger" role="alert">
            {{$message}}
        </div>
        @enderror

        <div class="mb-3 d-flex gap-4">
            <img width="140" src="{{ asset('storage/' . $project->cover_image)}}" alt="">
            <div>
                <label for="cover_image" class="form-label">Replace project Image</label>
                <input type="file" name="cover_image" id="cover_image" class="form-control  @error('cover_image') is-invalid @enderror" placeholder="" aria-describedby="coverImageHelper">
                <small id="coverImageHelper" class="text-muted">Replace the project cover image</small>
            </div>
        </div>
        @error('cover_image')
        <div class="alert alert-danger" role="alert">
            {{$message}}
        </div>
        @enderror

        <div class="mb-3">
            <label for="type_id" class="form-label">Types</label>
            <select class="form-select form-select-lg @error('type_id') 'is-invalid' @enderror" name="type_id" id="type_id">
                <option value="">Without type</option>

                @forelse ($types as $type )
                <!-- Check if the post has a category assigned or not-->
                <option value="{{$type->id}}" {{ $type->id == old('type_id',  $project->type ? $project->type->id : '') ? 'selected' : '' }}>
                    {{$type->name}}
                </option>
                @empty
                <option value="">Sorry, no types in the system.</option>
                @endforelse

            </select>
        </div>
        @error('type_id')
        <div class="alert alert-danger" role="alert">
            {{$message}}
        </div>
        @enderror


        <div class="mb-3">
            <label for="technologies" class="form-label">Technologies</label>
            <select multiple class="form-select form-select-sm" name="technologies[]" id="technologies">
                <option value="" disabled>Select a technology</option>
                @forelse ($technologies as $technology)

                @if ($errors->any())
                <!-- Pagina con errori di validazione, deve usare old per verificare quale id di technology preselezionare -->
                <option value="{{$technology->id}}" {{ in_array($technology->id, old('technologies', [])) ? 'selected' : '' }}>{{$technology->name}}</option>
                @else
                <!-- Pagina caricate per la prima volta: deve mostrarare i technology preseleziononati dal db -->
                <option value="{{$technology->id}}" {{ $project->technologies->contains($technology->id) ? 'selected' : ''}}>{{$technology->name}}</option>
                @endif
                @empty
                <option value="" disabled>Sorry 😥 no technologies in the system</option>
                @endforelse

            </select>
        </div>


        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="4">{{old('description'), $project->description}}</textarea>
        </div>
        @error('description')
        <div class="alert alert-danger" role="alert">
            {{$message}}
        </div>
        @enderror
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

@endsection