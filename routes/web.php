<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/user', [App\Http\Controllers\UserController::class, 'index'])->name('user')->middleware(['user']);



Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('admin.logout');

Route::group(['middleware' => ['admin']], function () {
	Route::group(['prefix' => 'admin'], function () {
		Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');

		Route::get('permissions/{id?}', [App\Http\Controllers\Admin\PermissionController::class, 'showPermissions'])->name('permissions')->middleware('password.confirm');

		Route::post('save-permissions', [App\Http\Controllers\Admin\PermissionController::class, 'savePermissions'])->name('save.all.permission');


		/* Role */
		Route::get('role', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('role');
		Route::get('create-role', [App\Http\Controllers\Admin\RoleController::class, 'create'])->name('role.create');
		Route::post('store-role', [App\Http\Controllers\Admin\RoleController::class, 'store'])->name('role.store');
		Route::get('role/edit/{id}', [App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('role.edit');
		Route::post('role/request/update/{id}', [App\Http\Controllers\Admin\RoleController::class, 'update'])->name('role.update');
		Route::get('allrole', [App\Http\Controllers\Admin\RoleController::class, 'allData'])->name('allrole');
		Route::get('change/role-status/{id}', [App\Http\Controllers\Admin\RoleController::class, 'changeStatus_datatable'])->name('change.status');
		Route::post('role/existrole', [App\Http\Controllers\Admin\RoleController::class, 'existRole'])->name('role.existrole');


		/* Module */
		Route::get('module', [App\Http\Controllers\Admin\ModuleController::class, 'index'])->name('module');
		Route::get('create-module', [App\Http\Controllers\Admin\ModuleController::class, 'create'])->name('module.create');
		Route::post('store-module', [App\Http\Controllers\Admin\ModuleController::class, 'store'])->name('module.store');
		Route::get('module/edit/{id}', [App\Http\Controllers\Admin\ModuleController::class, 'edit'])->name('module.edit');
		Route::post('module/request/update/{id}', [App\Http\Controllers\Admin\ModuleController::class, 'update'])->name('module.update');
		Route::get('allmodule', [App\Http\Controllers\Admin\ModuleController::class, 'allData'])->name('allmodule');
		Route::get('change/module-status/{id}', [App\Http\Controllers\Admin\ModuleController::class, 'changeStatus_datatable'])->name('change.status');
		Route::post('module/existname', [App\Http\Controllers\Admin\ModuleController::class, 'existModule'])->name('module.existmodule');

		/* Country */
		Route::get('country', [App\Http\Controllers\Admin\CountryController::class, 'index'])->name('country');
		Route::get('create-country', [App\Http\Controllers\Admin\CountryController::class, 'create'])->name('country.create');
		Route::post('store-country', [App\Http\Controllers\Admin\CountryController::class, 'store'])->name('country.store');
		Route::get('country/edit/{id}', [App\Http\Controllers\Admin\CountryController::class, 'edit'])->name('country.edit');
		Route::post('country/request/update/{id}', [App\Http\Controllers\Admin\CountryController::class, 'update'])->name('country.update');
		Route::get('allcountry', [App\Http\Controllers\Admin\CountryController::class, 'allData'])->name('allcountry');
		Route::get('change/country-status/{id}', [App\Http\Controllers\Admin\CountryController::class, 'changeStatus_datatable'])->name('country.status');
		Route::post('country/existcountry', [App\Http\Controllers\Admin\CountryController::class, 'existCountry'])->name('country.existcountry');

		/* Languages */
		Route::get('languages', [App\Http\Controllers\Admin\LanguagesController::class, 'index'])->name('languages');
		Route::get('create-languages', [App\Http\Controllers\Admin\LanguagesController::class, 'create'])->name('languages.create');
		Route::post('store-languages', [App\Http\Controllers\Admin\LanguagesController::class, 'store'])->name('languages.store');
		Route::get('languages/edit/{id}', [App\Http\Controllers\Admin\LanguagesController::class, 'edit'])->name('languages.edit');
		Route::post('languages/request/update/{id}', [App\Http\Controllers\Admin\LanguagesController::class, 'update'])->name('languages.update');
		Route::get('alllanguages', [App\Http\Controllers\Admin\LanguagesController::class, 'allData'])->name('alllanguages');
		Route::get('change/languages-status/{id}', [App\Http\Controllers\Admin\LanguagesController::class, 'changeStatus_datatable'])->name('languages.status');
		Route::post('languages/existlanguages', [App\Http\Controllers\Admin\LanguagesController::class, 'existLanguages'])->name('languages.existlanguages');

		/* category */
		Route::get('category', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('category');
		Route::get('create-category', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('category.create');
		Route::post('store-category', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('category.store');
		Route::get('category/edit/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('category.edit');
		Route::post('category/request/update/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('category.update');
		Route::get('allcategory', [App\Http\Controllers\Admin\CategoryController::class, 'allData'])->name('allcategory');
		Route::get('change/category-status/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'changeStatus_datatable'])->name('category.status');
		Route::post('category/existcategory', [App\Http\Controllers\Admin\CategoryController::class, 'existCategory'])->name('category.existcategory');


		/* Content */
		Route::get('content', [App\Http\Controllers\Admin\ContentController::class, 'index'])->name('content');
		Route::get('create-content', [App\Http\Controllers\Admin\ContentController::class, 'create'])->name('content.create');
		Route::post('store-content', [App\Http\Controllers\Admin\ContentController::class, 'store'])->name('content.store');
		Route::get('content/edit/{id}', [App\Http\Controllers\Admin\ContentController::class, 'edit'])->name('content.edit');
		Route::post('content/request/update/{id}', [App\Http\Controllers\Admin\ContentController::class, 'update'])->name('content.update');
		Route::get('allcontent', [App\Http\Controllers\Admin\ContentController::class, 'allData'])->name('allcontent');
		Route::get('change/content-status/{id}', [App\Http\Controllers\Admin\ContentController::class, 'changeStatus_datatable'])->name('content.status');
		Route::post('content/existcontent', [App\Http\Controllers\Admin\ContentController::class, 'existContent'])->name('content.existcontent');

		/* Translation */
		Route::get('translation', [App\Http\Controllers\Admin\TranslationController::class, 'index'])->name('translation');
		Route::get('create-translation', [App\Http\Controllers\Admin\TranslationController::class, 'create'])->name('translation.create');
		Route::post('store-translation', [App\Http\Controllers\Admin\TranslationController::class, 'store'])->name('translation.store');
		Route::get('translation/edit/{id}', [App\Http\Controllers\Admin\TranslationController::class, 'edit'])->name('translation.edit');
		Route::post('translation/request/update/{id}', [App\Http\Controllers\Admin\TranslationController::class, 'update'])->name('translation.update');
		Route::get('alltranslation', [App\Http\Controllers\Admin\TranslationController::class, 'allData'])->name('alltranslation');
		
		/* Translation */
		Route::get('categoryTranslat', [App\Http\Controllers\Admin\CategoryTranslatController::class, 'index'])->name('categoryTranslat');
		Route::get('create-categoryTranslat', [App\Http\Controllers\Admin\CategoryTranslatController::class, 'create'])->name('categoryTranslat.create');
		Route::post('store-categoryTranslat', [App\Http\Controllers\Admin\CategoryTranslatController::class, 'store'])->name('categoryTranslat.store');
		Route::get('categoryTranslat/edit/{id}', [App\Http\Controllers\Admin\CategoryTranslatController::class, 'edit'])->name('categoryTranslat.edit');
		Route::post('categoryTranslat/request/update/{id}', [App\Http\Controllers\Admin\CategoryTranslatController::class, 'update'])->name('categoryTranslat.update');
		Route::get('allcategoryTranslat', [App\Http\Controllers\Admin\CategoryTranslatController::class, 'allData'])->name('allcategoryTranslat');
		

		/* App users */
		Route::get('appusers', [App\Http\Controllers\Admin\AppUsersController::class, 'index'])->name('appusers');
		Route::get('allappusers', [App\Http\Controllers\Admin\AppUsersController::class, 'allData'])->name('allappusers');
    });
});
