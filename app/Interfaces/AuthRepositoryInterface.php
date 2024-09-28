<?php

namespace App\Interfaces;

interface AuthRepositoryInterface
{
    public function register($request);
    public function login($request);
    public function refresh();
}
