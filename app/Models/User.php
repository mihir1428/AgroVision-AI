<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = ['name', 'email', 'password', 'is_admin', 'language'];
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array { return ['email_verified_at' => 'datetime', 'password' => 'hashed', 'is_admin' => 'boolean']; }
    public function predictions(): HasMany { return $this->hasMany(Prediction::class); }
    public function feedback(): HasMany { return $this->hasMany(Feedback::class); }
}
