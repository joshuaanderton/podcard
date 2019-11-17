<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

trait Helpable
{
    public function tokenGenerate($field_name, $length = 8)
    {
        $token = null;
        while ($token == null || $this->where($field_name, $token)->count() > 0) :
            $token = Str::random($length);
        endwhile;

        $this->update([$field_name => $token]);

        return $this;
    }

    public function fullName()
    {
        $first_name = !empty($this->first_name) ? $this->first_name : false;
        $last_name =  !empty($this->last_name)  ? $this->last_name  : false;
        return $first_name ? trim("{$first_name} {$last_name}") : "";
    }

    public function emailClean()
    {
        return preg_replace('/[+](.*?)(@)/', '$2', $this->email);
    }

    public function avatarUrl($size = 80)
    {
        return "https://www.gravatar.com/avatar/" . md5($this->emailClean()) . "?s={$size}&d=https://s3-us-west-2.amazonaws.com/upscribe/media/icon-signup.png";
    }
}