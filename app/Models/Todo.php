<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * App\Models\Todo
 *
 * @property int $id
 * @property string $task
 * @property int $status
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Todo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Todo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Todo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Todo whereCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Todo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Todo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Todo whereTask($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Todo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Todo whereUserId($value)
 * @mixin \Eloquent
 */
class Todo extends Model
{
    const STATUS_COMPLETED = 1;
    const STATUS_PENDING = 0;

    protected $fillable = ['task', 'status'];

    public static function statuses()
    {
        return [
            self::STATUS_PENDING => 'PENDING',
            self::STATUS_COMPLETED => 'COMPETED',
        ];
    }

    public function isCompleted()
    {
        return self::STATUS_COMPLETED === (int)$this->status;
    }

    public function isPending()
    {
        return self::STATUS_PENDING === (int)$this->status;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusText()
    {
        return Arr::get(self::statuses(), $this->status);
    }

    public function isOwner(User $user)
    {
        return (int)$this->user_id === (int)$user->id;
    }

}
