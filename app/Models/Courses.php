<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Courses extends Model
{
    use CrudTrait;
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'venue_id', 'event_category_id',
        'is_bookable', 'published', 'status', 'description',
        'detail_heading_1', 'detail_text_1',
        'detail_heading_2', 'detail_text_2',
        'detail_heading_3', 'detail_text_3',
        'enquiries', 'enquiries_link',
        'start_date', 'end_date', 'start_time', 'end_time',
        'price', 'hero_image', 'detail_image_1', 'detail_image_2', 'button_text', 'use_time','featured'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'start_time' => 'datetime:H:i',
        'end_time'   => 'datetime:H:i',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class,  'category_id', 'id');
    }
    public function venue()
    {
        return $this->belongsTo(Venues::class);
    }

    public function getUrlAttribute()
    {
        return route('events.show', $this->slug);
    }

        public function scopePublished($query)
    {
        return $query->where('published', true)
                     ->where('status', 'ACTIVE');
    }

    public function sponsors()
    {
        return $this->belongsToMany(Companies::class, 'companies', 'course_id', 'company_id');
    }

    public function getStartTimeAttribute($value)
    {
        return $value ? date('H:i', strtotime($value)) : null;
    }

    public function getEndTimeAttribute($value)
    {
        return $value ? date('H:i', strtotime($value)) : null;
    }


    public function tickets(): MorphMany
    {
        return $this->morphMany(EventTickets::class, 'ticketable');
    }


    public function attendees()
    {
        return $this->hasMany(EventAttendees::class, 'event_id');
    }

    public function attendeesCount(): int
    {
        return $this->attendees()->count();
    }
}
