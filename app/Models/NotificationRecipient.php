<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationRecipient extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_recipients';

    /**
     * Indicates if the model should be timestamped.
     * Set to false because the migration didn't include created_at/updated_at.
     * If you add them later, set this to true.
     *
     * @var bool
     */
    public $timestamps = false; // Based on the provided migration

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'notification_id',
        'student_id',
        'is_read',
        'read_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the notification associated with the recipient record.
     */
    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class);
    }

    /**
     * Get the student associated with the recipient record.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}