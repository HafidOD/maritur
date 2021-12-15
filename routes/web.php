<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;
use App\Components\SessionComponent;
use App\City;
use App\ItemList;
use App\Mail\ContactForm;
use App\Reservation;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\Cache;

    Route::get('/', function (Request $req) {
        $version = 1;
        $allDests = Cache::rememberForever('allAvailableHotels'.$version, function() {
            return City::has('availableHotels')
            ->withCount('availableHotels')
            ->orderBy('available_hotels_count','DESC')
            ->get();
        });

        $allDests2 = Cache::rememberForever('allAvailableHotels2'.$version, function() {
            return City::has('availableHotels')
            ->withCount('availableHotels')
            ->orderBy('name','ASC')
            ->get();
        });

        $itemLists = ItemList::has('itemRelations')
            ->with(['itemRelations'])
            ->where('section',ItemList::SECTION_HOME)
            ->where('affiliateId',\SC::$affiliateId)
            ->orderBy('orderx','ASC')
            ->get();

        if($req->input('search',false)!==false) SessionComponent::setQuoteParams($req);
    	$quoteParams = SessionComponent::getQuoteParams();
        return view('home.index',[
            'destinationField'=>'',
            'quoteParams'=>$quoteParams,
            'allDests'=>$allDests,
            'allDests2'=>$allDests2,
            'itemLists'=>$itemLists,
            'sliders'=>\App\SliderImage::where(['section'=>0])
                ->where('affiliateId',\SC::$affiliateId)
                ->orderBy('orderx','ASC')
                ->get(),
        ]);
    });
    Route::get('cart/success', 'CartController@success');

    Route::get('/privacy', function () {
        return view('home.privacy');
    });
    Route::get('/policies', function () {
        return view('home.policies');
    });
    Route::get('/circuits', function () {
        return view('home.circuits');
    });
    Route::get('/contact', function () {
        return view('home.contact');
    });
    Route::get('/offers', function () {
        $lists = ItemList::where('section',ItemList::SECTION_OFFERS)->has('itemRelations')->get();
        $sliders = \App\SliderImage::where(['section'=>3])
            ->where('affiliateId',\SC::$affiliateId)
            ->orderBy('orderx','ASC')->get();
        return view('offers.index',['lists'=>$lists,'sliders'=>$sliders]);
    });
    Route::post('/contact', function (Request $req) {
        $success = false;
        $name = $req->input('name',false);
        $email = $req->input('email',false);
        if ($name && $email) {
            $phone = $req->input('phone','');
            $message = $req->input('message','');

            $recaptcha = new \ReCaptcha\ReCaptcha('6LfwAZAUAAAAAJn78NJZ_iEzy1OZRZi0EHOY6qNz');
            $resp = $recaptcha->verify($req->input('g-recaptcha-response'), $_SERVER['REMOTE_ADDR']);
            if ($resp->isSuccess()) {
                Mail::send(new ContactForm($name,$email,$phone,$message));
                $success = true;
                if (Mail::failures()) $success = false;
            } else {
                $errors = $resp->getErrorCodes();
            }

        }
        return view('home.contact',['success'=>$success]);
    });
    Route::get('/cart/error', function (Request $req) {
        return view('cart.error');
    });
    Route::get('/setcur/{currency}', function ($currency) {
    	SessionComponent::setCurrentCurrency($currency);
    	// return redirect()->back();
    });
    Route::get('/newuser', function () {
        $user = new \App\User;
        $user->name = 'Hafid';
        $user->email = 'webmaster3@neo-emarketing.com';
        $user->password = Hash::make('holamundo');
        $user->affiliateId = 1;
        $user->save();
    });

    // Route::get('loadtours', 'HotelsController@loadtours');
    // Route::get('tryping', 'HotelsController@tryping');

    Route::get('hotels/autocomplete', 'HotelsController@autocomplete');
    Route::post('hotels/setfilters', 'HotelsController@setFilters');
    Route::post('hotels/{cityPath}', 'HotelsController@list');
    Route::get('hotels/{cityPath}', 'HotelsController@hotels');
    Route::get('hotels/{cityPath}/{hotelPath}', 'HotelsController@hotel');
    Route::get('state/{statePath}', 'HotelsController@state');

    Route::get('tours/autocomplete', 'ToursController@autocomplete');
    Route::get('tours', 'ToursController@index');
    Route::get('tours/{cityPath}', 'ToursController@tours');
    Route::get('tours/{cityPath}/{tourPath}', 'ToursController@tour');

    Route::get('vacational-rentals', 'HousesController@index');
    Route::get('vacational-rentals/autocomplete', 'HousesController@autocomplete');
    Route::get('vacational-rentals/{cityPath}', 'HousesController@houses');
    Route::get('vacational-rentals/{cityPath}/{housePath}', 'HousesController@house');

    Route::get('transfers', 'TransfersController@actionIndex');
    Route::get('transfers/autocomplete', 'TransfersController@autocomplete');
    Route::get('transfers/{destPath}', 'TransfersController@actionDestination');
    Route::get('transfers/hotel/{hotelPath}', 'TransfersController@actionHotel');
    Route::get('transfers/tour/{tourPath}', 'TransfersController@actionTour');

    Route::get('cart', 'CartController@actionIndex');
    Route::get('confirmation/{token}', 'CartController@actionConfirm');
    Route::post('cart/addroom', 'CartController@actionAddRoom');
    Route::post('cart/addtour', 'CartController@addTour');
    Route::post('cart/addtransfer', 'CartController@addTransfer');
    Route::post('cart/removetour', 'CartController@removeTour');
    Route::post('cart/removetransfer', 'CartController@removeTransfer');
    Route::get('cart/remove/{i}', 'CartController@actionRemove');
    Route::get('cart/info', 'CartController@actionInfo');
    Route::post('cart/checkout', 'CartController@actionCheckout');
    Route::get('cart/paypalipn', 'CartController@actionPaypalIpn');
    Route::get('cart/paypalpayment', 'CartController@actionPaypalPayment');

    // AMP
    Route::prefix('amp')->group(function () {
        Route::get('home', function () {
            $itemLists = ItemList::has('itemRelations')
                        ->with(['itemRelations'])
                        ->where('section',ItemList::SECTION_HOME)
                        ->where('affiliateId',\SC::$affiliateId)
                        ->orderBy('orderx','ASC')
                        ->get();
            return view('home.amp',[
                'itemLists'=>$itemLists,
                'sliders'=>\App\SliderImage::where(['section'=>0])
                ->where('affiliateId',\SC::$affiliateId)
                ->orderBy('orderx','ASC')
                ->get(),
            ]);
        });
    });


// backend
Route::middleware(['auth','adminLoginAffiliate'])->group(function () {
    $aux = 'namespace';
    Route::$aux('Admin')->group(function () {
        Route::prefix('admin')->group(function () {
            Route::get('/', 'ReservationsController@index');
            Route::get('reservations/listing', 'ReservationsController@listing');
            Route::get('reservations/view/{id}', 'ReservationsController@view');
            Route::get('reservations/cancel/{id}', 'ReservationsController@cancel');
            Route::get('reservations/cancel-room/{id}', 'ReservationsController@cancelRoom');
            Route::get('reservations/test', 'ReservationsController@test');

            Route::get('affiliates/listing', 'AffiliatesController@listing');
            Route::get('affiliates/{model}/delete', 'AffiliatesController@delete');
            Route::resource('affiliates', 'AffiliatesController');

            Route::get('users/listing', 'UsersController@listing');
            Route::get('users/{model}/delete', 'UsersController@delete');
            Route::resource('users', 'UsersController');

            Route::get('tours', 'ToursController@actionIndex');
            Route::get('tours/destinations-listing/{tour}', 'ToursController@destinationsListing');
            Route::get('tours/add-destination/{tour}', 'ToursController@addDestination');
            Route::post('tours/add-destination/{tour}', 'ToursController@doAddDestination');
            Route::get('tours/delete-destination/{tour}/{destinationId}', 'ToursController@deleteDestination');
            Route::get('tours/deactivate-tour/{tour}', 'ToursController@deactivateTour');
            Route::get('tours/activate-tour/{tour}', 'ToursController@activateTour');
            Route::get('tours/affiliate-prices/{tour}', 'ToursController@affiliatePrices');
            Route::post('tours/affiliate-prices/{tour}', 'ToursController@updateAffiliatePrices');
            Route::get('tours/search', 'ToursController@search');
            Route::get('tours/list/{listName}', 'ToursController@list');
            Route::post('tours/update-list/{listName}', 'ToursController@updateList');
            Route::get('tours/new', 'ToursController@actionNew');
            Route::get('tours/delete/{model}', 'ToursController@delete');
            Route::get('tours/gallery/{id}', 'ToursController@actionGallery');
            Route::post('tours/create', 'ToursController@actionCreate');
            Route::get('tours/listing', 'ToursController@actionListing');
            Route::get('tours/edit/{id}', 'ToursController@actionEdit');
            Route::post('tours/update/{id}', 'ToursController@actionUpdate');
            Route::post('tours/upload', 'ToursController@actionUpload');

            Route::get('tour-prices/listing/{tour}', 'TourPricesController@listing');
            Route::get('tour-prices/new/{tour}', 'TourPricesController@new');
            Route::get('tour-prices/edit/{tourPrice}', 'TourPricesController@edit');
            Route::get('tour-prices/delete/{model}', 'TourPricesController@delete');
            Route::post('tour-prices/update/{model}', 'TourPricesController@update');
            Route::post('tour-prices', 'TourPricesController@store');

            Route::get('tour-price-transportations/listing/{tourPrice}', 'TourPriceTransportationsController@listing');
            Route::get('tour-price-transportations/new/{tourPrice}', 'TourPriceTransportationsController@new');
            Route::get('tour-price-transportations/edit/{model}', 'TourPriceTransportationsController@edit');
            Route::get('tour-price-transportations/delete/{model}', 'TourPriceTransportationsController@delete');
            Route::post('tour-price-transportations', 'TourPriceTransportationsController@store');
            Route::post('tour-price-transportations/update/{model}', 'TourPriceTransportationsController@update');
            // Route::resource('tour-prices', 'TourPricesController');

            Route::get('houses', 'HousesController@actionIndex');
            Route::get('houses/new', 'HousesController@actionNew');
            Route::get('houses/gallery/{id}', 'HousesController@actionGallery');
            Route::post('houses/create', 'HousesController@actionCreate');
            Route::get('houses/listing', 'HousesController@actionListing');
            Route::get('houses/edit/{id}', 'HousesController@actionEdit');
            Route::post('houses/update/{id}', 'HousesController@actionUpdate');
            Route::post('houses/upload', 'HousesController@actionUpload');

            Route::get('transport-services/destination/{dest}', 'TransportServicesController@destination');
            Route::get('transport-services/destinations-listing', 'TransportServicesController@destinationsListing');
            Route::get('transport-services/listing/{dest}', 'TransportServicesController@listing');
            Route::get('transport-services/delete/{id}', 'TransportServicesController@destroy');
            Route::get('transport-services/create/{dest}', 'TransportServicesController@create');
            Route::post('transport-services/update/{dest}', 'TransportServicesController@update');
            Route::resource('transport-services', 'TransportServicesController');

            Route::get('transport-service-types/listing', 'TransportServiceTypesController@listing');
            Route::get('transport-service-types/delete/{id}', 'TransportServiceTypesController@destroy');
            Route::get('transport-service-types/gallery/{id}', 'TransportServiceTypesController@actionGallery');
            Route::resource('transport-service-types', 'TransportServiceTypesController');

            Route::post('uploads/add', 'UploadsController@actionAdd');
            Route::post('uploads/update-positions', 'UploadsController@actionUpdatePositions');
            Route::get('uploads/delete/{id}', 'UploadsController@actionDelete');

            Route::get('tour-providers/listing', 'TourProvidersController@listing');
            Route::get('tour-providers/delete/{id}', 'TourProvidersController@destroy');
            Route::resource('tour-providers', 'TourProvidersController');

            Route::get('tour-categories/listing', 'TourCategoriesController@listing');
            Route::get('tour-categories/delete/{id}', 'TourCategoriesController@destroy');
            Route::resource('tour-categories', 'TourCategoriesController');

            Route::get('destinations/listing', 'DestinationsController@listing');
            Route::get('destinations/search', 'DestinationsController@search');
            Route::get('destinations/gallery/{destination}', 'DestinationsController@actionGallery');
            Route::post('destinations/info/{destination}', 'DestinationsController@setInfo');
            Route::resource('destinations', 'DestinationsController');

            Route::get('sliders/delete/{section}', 'SlidersController@delete');
            Route::get('sliders/{section}', 'SlidersController@slider');
            Route::post('sliders/upload/{section}', 'SlidersController@upload');

            Route::get('item-lists/listing', 'ItemListsController@listing');
            Route::get('item-lists/delete/{id}', 'ItemListsController@destroy');
            Route::resource('item-lists', 'ItemListsController');

            Route::get('item-relations/listing', 'ItemRelationsController@listing');
            Route::get('item-relations/new/{itemList}', 'ItemRelationsController@new');
            Route::get('item-relations/{model}/edit', 'ItemRelationsController@edit');
            Route::get('item-relations/delete/{model}', 'ItemRelationsController@delete');
            Route::post('item-relations/update/{model}', 'ItemRelationsController@update');
            Route::post('item-relations', 'ItemRelationsController@store');

            Route::get('hotels/search', 'HotelsController@search');
            
            Route::get('settings', 'SettingsController@index');
            Route::post('settings/update', 'SettingsController@update');
            Route::post('settings/update-payment-methods', 'SettingsController@updatePaymentMethods');
        });
        // Controllers Within The "App\Http\Controllers\Admin" Namespace
    });
});

Auth::routes();

Route::get('/sitemap.xml', function () {
    $cities = City::has('hotels')
    // ->take(10)
    ->get();
    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($cities as $city) {
        $priority = '1.0';
        $xml.="<url>";
        $xml.="<loc>".url('hotels',[$city->path])."</loc>";
        $xml.="<lastmod>".date('Y-m-d')."</lastmod>";
        $xml.="<priority>$priority</priority>";
        $xml.="</url>";
        foreach ($city->hotels as $hotel) {
            $priority = '0.9';
            $xml.="<url>";
            $xml.="<loc>".url('hotels',[$city->path,$hotel->path])."</loc>";
            $xml.="<lastmod>".date('Y-m-d')."</lastmod>";
            $xml.="<priority>$priority</priority>";
            $xml.="</url>";
        }
        foreach ($city->tours as $hotel) {
            $priority = '0.8';
            $xml.="<url>";
            $xml.="<loc>".url('hotels',[$city->path,$hotel->path])."</loc>";
            $xml.="<lastmod>2018-01-06</lastmod>";
            $xml.="<priority>$priority</priority>";
            $xml.="</url>";
        }
    }
    $xml.= '</urlset>';
    return response($xml)
    ->header('Content-Type','text/xml');
});

Route::get('/test', function () {
    return Hash::make('hola');
});
Route::get('/cleansession', function () {
    session(['roomsSelection'=>null]);
    session(['toursSelection'=>[]]);
});
Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});
