<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\HighlightController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\TourPackageController;
use App\Http\Controllers\ConfirmedDeparturesController;
use Illuminate\Support\Facades\Route;

Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('locale.switch')->where('locale', 'en|zh_CN|fr|de|he|it|mt|es');

Route::get('/', HomeController::class)->name('home');

// Serve tour activity SVGs with correct Content-Type (image/svg+xml) - uses /svg/icons/ path
// so requests always reach Laravel (works with php artisan serve, Nginx, Apache)
Route::get('/svg/icons/{filename}', function (string $filename) {
    if (! str_ends_with($filename, '.svg')) {
        abort(404);
    }
    $path = storage_path('app/public/tour_activities/' . $filename);
    if (! is_file($path)) {
        abort(404);
    }
    return response()->file($path, ['Content-Type' => 'image/svg+xml']);
})->where('filename', '.+\.svg$')->name('storage.tour-activities.svg');
Route::get('/sitemap.xml', \App\Http\Controllers\SitemapController::class)->name('sitemap');

Route::get('/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/tours/search', [TourController::class, 'search'])->name('tours.search');
Route::get('/tours/category/{category:slug}', [TourController::class, 'categoryArchive'])->name('tours.category');
// More specific tour routes first so /tours/{slug} doesn't capture e.g. slug "xyz/available-dates"
Route::get('/tours/{slug}/price', [\App\Http\Controllers\Api\TourBookingApiController::class, 'price'])->name('tours.price');
Route::get('/tours/{slug}/available-dates', [\App\Http\Controllers\Api\TourBookingApiController::class, 'availableDates'])->name('tours.available-dates');
Route::get('/tours/{slug}/check-date', [\App\Http\Controllers\Api\TourBookingApiController::class, 'checkDate'])->name('tours.check-date');
Route::get('/tours/{slug}/book', [\App\Http\Controllers\BookingController::class, 'create'])->name('bookings.create');
Route::get('/tours/{slug}', [TourController::class, 'show'])->name('tours.show');

Route::get('/cities', [CityController::class, 'index'])->name('cities.index');
Route::get('/cities/{city}/highlights/{highlight}', [HighlightController::class, 'show'])->name('cities.highlights.show');
Route::get('/cities/{slug}', [CityController::class, 'show'])->name('cities.show');
Route::get('/hotels/{slug}', [HotelController::class, 'show'])->name('hotels.show');
Route::post('/bookings', [\App\Http\Controllers\BookingController::class, 'store'])->middleware('throttle:10,1')->name('bookings.store');
Route::get('/bookings/confirmation/{token}', [\App\Http\Controllers\BookingController::class, 'confirmation'])->name('bookings.confirmation');

Route::get('/packages', [TourPackageController::class, 'index'])->name('tour-packages.index');

Route::get('/confirmed-departures', [ConfirmedDeparturesController::class, 'index'])->name('confirmed-departures');
Route::post('/confirmed-departures', [ConfirmedDeparturesController::class, 'store'])->middleware('throttle:10,1')->name('confirmed-departures.store');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/load-more', [BlogController::class, 'loadMore'])->name('blog.load-more');
Route::get('/blog/category/uncategorized', [BlogController::class, 'uncategorizedArchive'])->name('blog.category.uncategorized');
Route::get('/blog/category/{category:slug}', [BlogController::class, 'categoryArchive'])->name('blog.category');
Route::get('/blog/tag/{tag:slug}', [BlogController::class, 'tagArchive'])->name('blog.tag');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/about', AboutController::class)->name('about');
Route::get('/our-transport', [\App\Http\Controllers\OurTransportController::class, 'index'])->name('our-transport');
Route::post('/our-transport', [\App\Http\Controllers\OurTransportController::class, 'store'])->middleware('throttle:5,1')->name('our-transport.store');
Route::get('/private-group-tour-requests', [\App\Http\Controllers\PrivateGroupTourRequestsController::class, 'index'])->name('private-group-tour-requests');
Route::post('/private-group-tour-requests', [\App\Http\Controllers\PrivateGroupTourRequestsController::class, 'store'])->middleware('throttle:5,1')->name('private-group-tour-requests.store');
Route::get('/faq', fn () => view('pages.faq'))->name('faq');
Route::get('/terms-and-cancellation-policy', fn () => view('pages.terms-and-cancellation-policy'))->name('terms');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

Route::post('/newsletter', function () {
    request()->validate(['email' => 'required|email']);
    return back()->with('success', 'Thanks for subscribing!');
})->name('newsletter.subscribe');

Route::get('/dashboard', \App\Http\Controllers\User\DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('amenities', AmenityController::class)->except(['show']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/my-bookings/{token}/cancel', [\App\Http\Controllers\User\BookingController::class, 'cancelByToken'])->name('user.bookings.cancel');
    Route::post('/wishlist/{tour}', [\App\Http\Controllers\User\WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{tour}', [\App\Http\Controllers\User\WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::post('/tours/{tour:slug}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});

require __DIR__.'/auth.php';
