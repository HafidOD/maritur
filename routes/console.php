<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('genSizes', function () {
    \App\Components\UploadsComponent::generateImages();
})->describe('Display an inspiring quote');

Artisan::command('cleanUploads', function () {
    \App\Components\UploadsComponent::cleanUploads();
    \App\Components\ImagesComponent::cleanImages();
})->describe('Display an inspiring quote');

Artisan::command('loadHotels', function () {
	App\Components\OmnibeesApiComponent::$useCache = false;
    App\Components\OmnibeesLoadDbComponent::loadHotels();
})->describe('Display an inspiring quote');

Artisan::command('setAvailables', function () {
    App\Components\OmnibeesLoadDbComponent::setHotelsAvailable();
})->describe('Display an inspiring quote');

Artisan::command('testApp', function () {
    App\Components\OmnibeesLoadDbComponent::loadCountries();
    // App\Components\OmnibeesLoadDbComponent::loadHotels();
})->describe('Display an inspiring quote');
