<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $listCategories = [
            ['title' => 'Littérature Grecque'],
            ['title' => 'Littérature Française'],
            ['title' => 'Littérature Comparée'],
            ['title' => 'Histoire'],
            ['title' => 'Histoire de l\'art']
        ];

        foreach ($listCategories as $list)
        {
            $category = new Category();
            $category
                ->setTitle($list['title']);
            $manager->persist($category);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}