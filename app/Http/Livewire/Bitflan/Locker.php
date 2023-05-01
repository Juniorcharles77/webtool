<?php

namespace App\Http\Livewire\Bitflan;

use App\Bitflan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Locker extends Component
{
    public $licenseKey   = '';
    public $licenseError = false;

    public function submitLicense() {
		File::put( storage_path( 'bitflan/license.stp' ), $this->licenseKey );

		if( File::exists( storage_path( 'bitflan/lock.stp' ) ) )
			File::delete( storage_path( 'bitflan/lock.stp' ) );

		redirect(url('/'));
    }

    public function render()
    {
        return view('install.locker');
    }
}
