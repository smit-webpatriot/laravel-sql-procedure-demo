<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;

class CreateFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $projects = Project::all()->map(function ($project) {
            return [
                'name' => $project->name,
                'description' => $project->description,
                'frontend' => $project->frontend,
                'backend' => $project->backend,
                'database' => $project->database,
                'technologies' => $project->technologies,
                'keywords' => $project->keywords,
            ];
        });

        // Write to CSV file
        $file = fopen('projects.csv', 'w');

        fputcsv($file, [
            'name',
            'description',
            'frontend',
            'backend',
            'database',
            'technologies',
            'keywords',
        ]);

        foreach ($projects as $project) {
            fputcsv($file, $project);
        }
    }
}
