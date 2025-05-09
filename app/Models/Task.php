<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['creator:id,name,email,role','assignedUsers:id,name,email,role']);
    }

    public function getCreatedAtAttribute($value): string
    {
        $date = Carbon::parse($value);
        return $date->timezone(config('app.timezone'))->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value): string
    {
        $date = Carbon::parse($value);
        return $date->timezone(config('app.timezone'))->format('Y-m-d H:i:s');
    }
}
