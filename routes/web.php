<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

// Halaman Utama
Route::get('/index', function () {
    return view('index'); // Pastikan file index.blade.php ada
})->name('index');

// Halaman About
Route::get('/about', function () {
    return view('about'); // Buat file about.blade.php
})->name('about');

// routes/web.php
Route::get('/single-post', function () {
    return view('single-post');
})->name('single-post');

// Halaman Categories
Route::get('/category', function () {
    return view('category'); // File categories.blade.php
})->name('category');

// Halaman Contact
Route::get('/contact', function () {
    return view('contact'); // File contact.blade.php
})->name('contact');

// Halaman BLog details
Route::get('/blog-details', function () {
    return view('blog-details'); // File blog-details.blade.php
})->name('blog-details');


