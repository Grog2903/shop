<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends BaseFixtures implements DependentFixtureInterface
{

    function loadData(ObjectManager $manager)
    {
        $this->createMany(Product::class, 80, function (Product $product) {
            $product
                ->setTitle($this->faker->name)
                ->setDescription($this->faker->text(250))
                ->setContent($this->faker->text(1500))
                ->setPrice($this->faker->numberBetween(1000, 10000))
                ->setCategory($this->getRandomReference(Category::class));
        });
    }


    public function getDependencies()
    {
        return [
            CategoryFixtures::class
        ];
    }
}
