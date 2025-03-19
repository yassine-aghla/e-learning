<?php
namespace App\Interfaces;


interface AuthRepositoryInterface
{
    public function register(array $data);
    public function login(array $credentials);
    public function logout();
    public function refresh();
    public function user();
    public function update(array $data);
    public function deleteUser($id);
}