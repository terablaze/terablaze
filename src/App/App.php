<?php

namespace App;

use Terablaze\Core\Parcel\Parcel;

class App extends Parcel
{
    public function boot(): void
    {
        $this->loadViewFrom('resources/views');
        $this->loadTranslationsFrom('resources/lang');
    }
}