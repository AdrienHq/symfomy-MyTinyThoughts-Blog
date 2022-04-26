<?php

namespace App\Service;

use App\Repository\MenuRepository;
use App\Entity\Menu;

class MenuService
{
    private MenuRepository $menuRepository;

    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }

    /**
     * @return Menu[]
     */
    public function findAll(): array
    {
        return $this->menuRepository->findAllForFront();

    }

}