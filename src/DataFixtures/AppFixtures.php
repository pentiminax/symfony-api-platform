<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $firstUser = new User();
        $secondUser = new User();

        $hashedPassword = $this->passwordHasher->hashPassword($firstUser, 'password');

        $firstUser->setUsername('First User');
        $firstUser->setPassword($hashedPassword);
        $firstUser->setRoles(['ROLE_ADMIN']);

        $secondUser->setUsername('Second User');
        $secondUser->setPassword($hashedPassword);
        $secondUser->setRoles(['ROLE_ADMIN']);

        $manager->persist($firstUser);
        $manager->persist($secondUser);

        for ($i = 1; $i <= 10; $i++) {
            $article = new Article();

            if ($i % 2 === 0) {
                $article->setAuthor($firstUser);
            } else {
                $article->setAuthor($secondUser);
            }

            $title = "Article $i";
            $slug = $this->slugger->slug($title)->lower();

            $article->setTitle($title);
            $article->setSlug($slug);
            $article->setDescription("Description $i");
            $article->setCreatedAt(new \DateTimeImmutable());
            $article->setPublishedAt(new \DateTimeImmutable());

            $manager->persist($article);
        }

        $manager->flush();
    }
}
