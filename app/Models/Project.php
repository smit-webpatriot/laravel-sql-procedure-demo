<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'frontend_id',
        'backend_id',
        'database_id',
    ];

    public function frontend()
    {
        return $this->belongsTo(Technology::class, 'frontend_id');
    }

    public function backend()
    {
        return $this->belongsTo(Technology::class, 'backend_id');
    }

    public function database()
    {
        return $this->belongsTo(Technology::class, 'database_id');
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'project_technologies', 'project_id', 'technology_id');
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'project_keywords', 'project_id', 'keyword_id');
    }

    public function getBackendAttribute()
    {
        return $this->backend()->first()->name;
    }

    public function getFrontendAttribute()
    {
        return $this->frontend()->first()->name;
    }

    public function getDatabaseAttribute()
    {
        return $this->database()->first()->name;
    }

    public function getKeywordsAttribute()
    {
        return implode(', ', $this->keywords()->pluck('name')->toArray());
    }

    public function getTechnologiesAttribute()
    {
        return implode(', ', $this->technologies()->pluck('name')->toArray());
    }

    protected $appends = [
        'backend',
        'frontend',
        'database',
        'keywords',
        'technologies'
    ];
}
