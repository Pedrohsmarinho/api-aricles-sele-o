<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;




class Article extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'title',
    'resume',
    'text',
    'slug'
  ];

  public function title() : Attribute {
    return new Attribute(
        set: fn($value) => [
            'title' => $value,
            'slug'  => Str::slug($value)
        ]
    );
  }
  
  protected static function boot()
  {
    parent::boot();
    static::creating(function ($model) {
      if (empty($model->{$model->getKeyName()})) {
        $model->{$model->getKeyName()} = Str::uuid()->toString();
      }
    });
  }

  protected $hidden = [
    'id'
  ];
  public function getIncrementing()
  {
    return false;
  }

  public function getRouteKeyName()
  {
    return 'string';
  }
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
