<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class TopicLesson extends Model { protected $fillable = ['topic_id','title','lesson_notes','worked_example_question','worked_example_solution','status']; public function topic(): BelongsTo { return $this->belongsTo(Topic::class); } }
