<?php declare (strict_types=1);

namespace App\View\Components;

use Illuminate\Support\Facades\Lang;
use Illuminate\View\Component;

class Notification extends Component
{
    public ?string $heading;

    public ?string $text;

    public bool $persist;

    public bool $success;

    public bool $error;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $heading = null,
        string $text = null,
        bool $persist = null,
        bool $success = null,
        bool $error = null
    ) {
        $this->heading = $heading;
        $this->text = $text;
        $this->persist = ! ! $persist;
        $this->error = ! ! $error;
        $this->success = ! ! $success;

        if (! $this->heading) {
            if ($this->error) {
                $this->heading = Lang::has('shared.error') ? Lang::get('shared.error') : __('Error');
            } elseif ($this->success) {
                $this->heading = Lang::has('shared.success') ? Lang::get('shared.success') : __('Success!');
            } else {
                $this->heading = Lang::has('shared.notification') ? Lang::get('shared.notification') : __('Notification');
            }
        }
    }

    public function render()
    {
        return view('components.notification');
    }
}
