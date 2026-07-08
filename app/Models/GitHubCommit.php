<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GitHubCommit extends Model
{
    protected $table = 'github_commits';
    protected $fillable = [
        'sha',
        'message',
        'author_name',
        'author_email',
        'branch',
        'repository',
        'committed_at',
        'additions',
        'deletions',
        'files_changed',
        'files_summary',
        'raw_data',
    ];

    protected $casts = [
        'committed_at' => 'datetime',
        'raw_data' => 'json',
    ];
}
