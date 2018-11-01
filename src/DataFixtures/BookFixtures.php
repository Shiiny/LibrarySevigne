<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class BookFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $book = new Book();
        $book
            ->setTitle("Sophocle, Ajax")
            ->setContent("Bender! Ship! Stop bickering or I'm going to come back there and change your opinions manually! No, I'm Santa Claus! I love this planet! I've got wealth, fame, and access to the depths of sleaze that those things bring.
    I found what I need. And it's not friends, it's things. Tell her you just want to talk. It has nothing to do with mating. You know, I was God once. Oh, how I wish I could believe or understand that! There's only one reasonable course of action now: kill Flexo!
    Yeah, and if you were the pope they'd be all, \"Straighten your pope hat.\" And \"Put on your good vestments.\" Guards! Bring me the forms I need to fill out to have her taken away! There, now he's trapped in a book I wrote: a crummy world of plot holes and spelling errors!")
            ->setAuthorFirstname("Sandrine")
            ->setAuthorLastname("Dubel")
            ->setCategory($manager->getRepository(Category::class)->findOneByTitle('Littérature Grecque'))
            ->setYearBook("2016");

        $manager->persist($book);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }

}
