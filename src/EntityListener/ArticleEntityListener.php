<?php

namespace App\EntityListener;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: Events::prePersist, entity: Article::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Article::class)]
class ArticleEntityListener
{
    public function __construct(
        private readonly Security $security,
        private readonly SluggerInterface $slugger
    ) {

    }

    public function prePersist(Article $article, LifecycleEventArgs $event): void
    {
        $article->computeSlug($this->slugger);
        $article->setUser($this->security->getUser());
    }

    public function preUpdate(Article $article, LifecycleEventArgs $event): void
    {
        $article->computeSlug($this->slugger);
    }
}