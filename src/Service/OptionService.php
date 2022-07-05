<?php

namespace App\Service;

use App\Repository\OptionRepository;

class OptionService
{
    private OptionRepository $optionRepository;

    public function __construct(OptionRepository $optionRepository)
    {
        $optionRepository = $this->optionRepository;
    }

    public function findALl(): array
    {
        return $this->optionRepository->findAllForTwig();
    }
}