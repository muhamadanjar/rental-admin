<?php
namespace App\Rental\Auth\Contracts;

interface IGrupRepository
{
    public function changeStatus($id, $value);

    public function getPerm();

    public function getPermById($id);

    public function storePerm($id, $perm);

    public function getMenu();

    public function getMenuById($id);

    public function storeMenus($id, $menus);

}
