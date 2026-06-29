<?php
namespace App\Controllers;

class HomeController
{
    public function index(): void
    {
        render('home', ['title' => 'Chào mừng đến với Secure Clinic CRM']);
    }
}