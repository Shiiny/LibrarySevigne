<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $name = [];

        for ($i = 0; $i < 20; $i++) {
            $categories = new Category();
            $categories->setTitle($faker->words(2, true));
            $name[] = [$faker->firstName => $faker->lastName];

            $manager->persist($categories);

            for ($j = 0; $j < mt_rand(1, 5); $j++) {
                $books = new Book();
                foreach ($name[$i] as $k => $v) {
                    $books->setTitle($faker->words(3, true))
                        ->setContent($faker->sentences(3, true))
                        ->setAuthorFirstname($k)
                        ->setAuthorLastname($v)
                        ->setCategory($categories)
                        ->setYearBook($faker->numberBetween(1980, 2018));
                }
                $manager->persist($books);
            }
        }
        $manager->flush();
    }
}
