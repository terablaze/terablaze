<?php

namespace App;

use TeraBlaze\Core\Parcel\Parcel;

class App extends Parcel
{
    public function boot(): void
    {
        $this->loadViewFrom('views');
    }
}