<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    use HasFactory;
    protected $table  = 'likes';
    protected $fillable = [
        'component_id',
        'user_id'
    ];
    protected $touches = ['component'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function component()
    {
        return $this->belongsTo(Component::class);
    }
    public function save(array $options = [])
    {
        $saved = parent::save($options);
        if ($saved) {
            $this->component->incrementLikes();
        }
        return $saved;
    }

    public function delete(array $options = [])
    {
        $deleted = parent::delete($options);
        if ($deleted) {
            $this->component->decrementLikes();
        }
        return $deleted;
    }
}
