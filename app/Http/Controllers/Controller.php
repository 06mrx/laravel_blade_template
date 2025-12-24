<?php

namespace App\Http\Controllers;

abstract class Controller
{
    private function formatDuration($seconds)
    {
        if (!$seconds)
            return '00:00:00';
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }
}
