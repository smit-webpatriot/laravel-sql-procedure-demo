<?php

namespace App\Jobs;

use App\Models\Keyword;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportProjects implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $timeout = 0;

    private $method = 'PHP'; // PHP/SQL

    private $file;

    /**
     * Create a new job instance.
     */
    public function __construct($file, $method = 'PHP')
    {
        $this->file = $file;
        $this->method = $method;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = array_map('str_getcsv', file($this->file));

        $header = array_shift($data);

        $csv = array();
        foreach ($data as $row) {
            $csv[] = array_combine($header, $row);
        }

        foreach ($csv as $row) {
            if ($this->method == 'PHP') {
                $project = Project::create([
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'frontend_id' => Technology::firstOrCreate(['name' => $row['frontend']])->id,
                    'backend_id' => Technology::firstOrCreate(['name' => $row['backend']])->id,
                    'database_id' => Technology::firstOrCreate(['name' => $row['database']])->id,
                ]);

                $technologies = explode(',', $row['technologies']);
                $keywords = explode(',', $row['keywords']);

                $keywordIds = [];
                foreach ($keywords as $keyword) {
                    $keywordIds[] = Keyword::firstOrCreate(['name' => $keyword])->id;
                }

                $project->keywords()->sync($keywordIds);


                $technologyIds = [];

                foreach ($technologies as $technology) {
                    $technologyIds[] = Technology::firstOrCreate(['name' => $technology])->id;
                }

                $project->technologies()->sync($technologyIds);
            } else {
                DB::select('
                    CALL create_project(
                        :p_name,
                        :p_description,
                        :p_frontend,
                        :p_backend,
                        :p_database,
                        :p_technologies,
                        :p_keywords
                    )
                ', [
                    $row['name'],
                    $row['description'],
                    $row['frontend'],
                    $row['backend'],
                    $row['database'],
                    $row['technologies'],
                    $row['keywords'],
                ]);
            }
        }
    }
}
