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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function AssignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        });
    }
    public function scopeSort($query, array $sort)
    {
        $query->when($sort['sort'] ?? false, function ($query, $sort) {
            $query->orderBy('created_at', $sort);
        });
    }
    public function scopePaginate($query, $perPage)
    {
        return $query->paginate($perPage);
    }
    public function scopeWithRelations($query)
    {
        return $query->with(['user:id,name,email,role','AssignedUser:id,name,email,role']);
    }
    public function scopeWithTrashed($query)
    {
        return $query->withTrashed();
    }
    public function scopeOnlyTrashed($query)
    {
        return $query->onlyTrashed();
    }
    public function scopeRestore($query)
    {
        return $query->restore();
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
