<?php

namespace App\DataFixtures;

use App\Entity\Option;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OptionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $options[] = new Option('Blog Title', 'blog_about', 'My Tiny Thoughts', TextType::class);
        $options[] = new Option('Copyrights', 'blog_copyrights', 'All rights reserved', TextType::class);
        $options[] = new Option('Number of articles by page', 'blog_articles_limit', 5, NumberType::class);
        $options[] = new Option('All user can sign in', 'users_can_register', true, CheckboxType::class);

        foreach ($options as $option){
            $manager->persist($option);
        }
        $manager->flush();
    }
}