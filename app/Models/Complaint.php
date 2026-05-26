<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'general'     => 'General',
        'work'        => 'Work Environment',
        'management'  => 'Management',
        'salary'      => 'Salary & Benefits',
        'leave'       => 'Leave Issues',
        'harassment'  => 'Harassment',
        'technical'   => 'Technical Issues',
        'other'       => 'Other',
    ];

    protected $fillable = [
        'user_id',
        'category',
        'subject',
        'description',
        'status',
        'admin_reply',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
