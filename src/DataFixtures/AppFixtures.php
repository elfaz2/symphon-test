<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for($i=0; $i > 50; $i++) {

            $vehicle = new Vehicle();

            $vehicle->setDateAdded(new \DateTime());
            $vehicle->setVehicleType(array_rand(["new","used"], 2));
            $vehicle->setMsrp(rand(1000,2000));
            $vehicle->setYear(rand(1000,2000));
            $vehicle->setMake($faker->text(10));
            $vehicle->setModel($faker->text(10));
            $vehicle->setMiles(rand(1000,2000));
            $vehicle->setVin($faker->text(5));
            $vehicle->setDeleted(false);

            $manager->persist($vehicle);
        }

        $manager->flush();
    }
}
