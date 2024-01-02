<?php

declare(strict_types=1);

namespace App\Actions\Pages;

use Inertia\Inertia;
use Inertia\Response;

class VideoBuilder
{
    public function __invoke(): Response
    {
        Inertia::setRootView('ja-inertia::app');

        return Inertia::render('Pages/VideoBuilder', [
            //
        ]);
    }
}
