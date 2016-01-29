<?php

namespace App\Interfaces;
use Illuminate\Http\Request;
use App\Http\Requests;
interface UserInterface
{
    public function index();
    public function store();
    public function show($id);
    public function update($id);
    public function destroy($id);
}