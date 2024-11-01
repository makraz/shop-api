<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\Factory\ProductFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ProductFactory::createOne([
            'imageFile' => new UploadedFile(path: __DIR__.'/assets/image.png', originalName: 'image.png', test: true),
        ]);
    }
}
