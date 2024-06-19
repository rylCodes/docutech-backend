<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Documentation extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'author', 'slug', 'icon', 'description', 'tags', 'content', 'user_id'];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function (Documentation $document) {
            $document->slug = Str::slug($document->title);
            $document->content = json_encode($document->content);
        });

        // static::creating(function (Documentation $document) {
        //     $document->slug = Str::slug($document->title);
        // });

        // static::updating(function (Documentation $document) {
        //     $document->slug = Str::slug($document->title);
        // });
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
