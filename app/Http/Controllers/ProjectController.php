<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Jobs\ImportProjects;
use App\Models\Keyword;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projectCount = Project::count();

        $keywordCount = Keyword::count();

        $technologyCount = Technology::count();

        return view('projects.index', compact('projectCount', 'keywordCount', 'technologyCount'));
    }

    public function import(Request $request)
    {
        $file = $request->file('file');

        $file->storeAs('imports', $file->getClientOriginalName());

        $method = $request->get('method', 'PHP');

        ImportProjects::dispatch(storage_path('app/imports/' . $file->getClientOriginalName()), $method);

        return redirect()->route('projects.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
