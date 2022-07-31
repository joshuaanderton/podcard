<?php

namespace App\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Input extends Component
{
  public $type, $model, $label, $disabled, $disclaimer, $icon, $prepend, $name, $id, $class;
  public $wrapperClass, $grouped, $spaceAbove, $autocomplete, $small;

  public $default_class = [
    'block',
    'w-full',
    'placeholder-gray-400',
    'border-0',
    'focus:ring-0',
    'bg-transparent',
  ];
  
  public function __construct(
    string $type = 'text', 
    string $model = null, 
    string $label = null, 
    bool $disabled = false,
    string $disclaimer = null,
    string $icon = null, 
    string $prepend = null,
    string $name = null,
    string $id = null,
    string $class = '', 
    string $wrapperClass = null, 
    bool $grouped = false,
    bool $spaceAbove = false,
    string $autocomplete = null,
    bool $small = false
  ) {
    $this->type         = $type;
    $this->model        = $model;
    $this->label        = $label;
    $this->disabled     = $disabled;
    $this->disclaimer   = $disclaimer;
    $this->icon         = $icon;
    $this->prepend      = $prepend;
    $this->name         = $name;
    $this->id           = $id;
    $this->grouped      = $grouped;
    $this->spaceAbove   = $spaceAbove;
    $this->small        = $small;

    if (!$this->id) :
      $this->id = $this->name ?: $this->model;
    endif;

    if ($this->type == 'email') :
      $this->autocomplete = 'username';
    elseif($this->type == 'password') :
      $this->autocomplete = 'new-password';
    elseif(Str::contains($this->id, 'url')) :
      $this->autocomplete = 'url';
    endif;

    foreach([
      'first_name' => 'given-name',
      'last_name' => 'family-name',
      'phone_number' => 'tel',
    ] as $field => $autocomplete_val) :
      if ($this->id == $field || Str::contains($this->model, ".{$field}")) :
        $this->autocomplete = $autocomplete_val;
      endif;
    endforeach;

    $this->autocomplete = $autocomplete ?: $this->autocomplete;

    $this->class = $this->classes($class);
    $this->wrapperClass = $wrapperClass;

    if ($this->spaceAbove) $this->wrapperClass = "mt-6 {$this->wrapperClass}";
  }

  public function classes(string $class = '')
  {
    if ($this->small) :
      $this->default_class[] = 'text-sm';
    endif;

    if ($this->prepend || $this->icon) :
      $this->default_class[] = 'pl-0';
    else :
        $this->default_class[] = 'pl-3';
    endif;

    $this->default_class[] = 'pr-3';

    return implode(' ', array_merge($this->default_class, explode(' ', $class)));
  }

  public function render()
  {
    return view('components.input');
  }
}
