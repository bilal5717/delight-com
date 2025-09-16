<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndexingHistory extends Model
{
    use HasFactory;

    protected $table = 'indexing_history';

    protected $fillable = [
        'url', 'reference_id', 'type', 'indexing_count'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class)->where("type", "posts");
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->where("type", "categories");
    }

    public function pages()
    {
        return $this->belongsTo(Page::class)->where("type", "pages");
    }
}
