<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//Misalnya ingin seperti ini

$routes->get('/', 'Home::index', ['filter' => 'auth']); //mengakses rute ini harus login dulu

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->get('produk', 'ProdukController::index', ['filter' => 'auth']); //mengakses rute ini harus login dulu
$routes->get('keranjang', 'TransaksiController::index', ['filter' => 'auth']); //mengakses rute ini harus login dulu
$routes->get('profile', 'ProfileController::index', ['filter' => 'auth']); //mengakses rute ini harus login dulu