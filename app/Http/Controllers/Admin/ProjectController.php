<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Controllers\Controller;
use App\Models\Technology;
use Illuminate\Support\Facades\Storage;
use App\Models\Type;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::orderByDesc('id')->get(); // recupero tutti i progetti ordinati per id
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::all(); // prendo tutti i tipi
        $technologies = Technology::all();
        return view('admin.projects.create', compact('types', 'technologies')); // glieli passo con compact
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        // validate data
        $val_data = $request->validated();

        // save input cover_image
        if ($request->hasFile('cover_image')) {
            $cover_image = Storage::put('uploads', $val_data['cover_image']);
            //dd($cover_image);
            // replace the value of cover_image inside $val_data
            $val_data['cover_image'] = $cover_image;
        }

        // generate project slug
        $project_slug = Project::generateSlug($val_data['title']);
        $val_data['slug'] = $project_slug;

        // create project
        $project = Project::create($val_data);

        if ($request->has('technologies')) {
            $project->technologies()->attach($val_data['technologies']);
        }

        // redirect
        return to_route('admin.projects.index')->with('message', 'Project added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $types = Type::all(); // prendo tutti i tipi
        $technologies = Technology::all();

        return view('admin.projects.edit', compact('project', 'types', 'technologies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $val_data = $request->validated();

        // check if the request has a cover_image field
        if ($request->hasFile('cover_image')) {
            // check if the current project has an image if yes, delete it
            if ($project->cover_image) {
                Storage::delete($project->cover_image);
            }
            $cover_image = Storage::put('uploads', $val_data['cover_image']);
            // replace the value of cover_image inside $val_data
            $val_data['cover_image'] = $cover_image;
        }

        // update project slug
        $project_slug = Project::generateSlug($val_data['title']);
        $val_data['slug'] = $project_slug;

        // update resource
        $project->update($val_data);

        if ($request->has('technologies')) {
            $project->technologies()->sync($val_data['technologies']);
        } else {
            $project->technologies()->sync([]);
        }

        return to_route('admin.projects.index')->with('message', "Project id: $project->id updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        if ($project->cover_image) {
            Storage::delete($project->cover_image);
        }

        $project->delete();

        return to_route('admin.projects.index')->with('message', 'Project deleted successfully');
    }
}
