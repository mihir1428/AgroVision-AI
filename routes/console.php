<?php
use Illuminate\Support\Facades\Artisan;
Artisan::command('agrovision:info', function () { $this->info('AgroVision AI Laravel application'); })->purpose('Show project information');
