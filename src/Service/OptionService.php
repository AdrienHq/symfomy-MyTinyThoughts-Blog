<?php

namespace App\Service;

use App\Repository\OptionRepository;

class OptionService
{
    private OptionRepository $optionRepository;

    public function __construct(OptionRepository $optionRepository)
    {
        $this->optionRepository = $optionRepository;
    }

    public function findALl(): array
    {
        return $this->optionRepository->findAllForTwig();
    }
}