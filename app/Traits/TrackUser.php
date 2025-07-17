<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait TrackUser
{
    public static function bootTrackUser()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->modified_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->modified_by = Auth::id();
            }
        });
        static::deleting(function ($model) {
            if (Auth::check()) {
                $model->modified_by = Auth::id();
            }   
        });
        
    }
}
