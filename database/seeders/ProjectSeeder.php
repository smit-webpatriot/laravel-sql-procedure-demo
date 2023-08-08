<?php

namespace Database\Seeders;

use App\Models\Keyword;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::factory()
            ->count(2000)
            ->create();

        $projects = Project::all();

        $technologyCount = Technology::count();

        foreach ($projects as $project) {
            $projectTechnologyCount = rand(5, 20);

            for ($i = 0; $i < $projectTechnologyCount; $i++) {
                $project->technologies()->attach(rand(1, $technologyCount));
            }
        }

        $keywordCount = Keyword::count();

        foreach ($projects as $project) {
            $projectKeywordCount = rand(30, 90);

            for ($i = 0; $i < $projectKeywordCount; $i++) {
                $project->keywords()->attach(rand(1, $keywordCount));
            }
        }
    }
}
