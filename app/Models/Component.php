<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;
    protected $table  = 'component';
    protected $fillable = [
        'category_id',
        'user_id',
        'name',
        'discription',
        'viewes',
        'likes',
        'code_referance',
    ];
     /**
     * Get the likes for the item.
     */

    public function likes()
    {
        return $this->hasMany(Likes::class);
    }
    public function incrementLikes()
    {
        $this->increment('likes');
    }

    public function decrementLikes()
    {
        $this->decrement('likes');
    }

}
