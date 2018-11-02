<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $populator = new Populator($faker, $manager);
        $populator->addEntity(Category::class, 20, [
            'title' => function() use ($faker) {
                return $faker->words(2, true);
            }
        ]);
        $populator->addEntity(Book::class, 100, [
            'title' => function() use ($faker) {
                return $faker->words(3, true);
            },
            'content' => function() use ($faker) {
                return $faker->sentences(3, true);
            },
            'authorFirstname' => function() use ($faker) {
                return $faker->firstName;
            },
            'authorLastname' => function() use ($faker) {
                return $faker->lastName;
            },
            'yearBook' => function() use ($faker) {
                return $faker->numberBetween(1980, 2018);
            }
        ]);
        $populator->execute();
    }
}
