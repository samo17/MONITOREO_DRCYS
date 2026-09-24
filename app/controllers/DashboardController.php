<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Auth.php';

class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();

        $this->view('dashboard/index');
    }
}