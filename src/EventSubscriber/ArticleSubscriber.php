<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Article;
use App\Exception\ArticleNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ArticleSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['checkArticleAvailability', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function checkArticleAvailability(ViewEvent $event): void
    {
        $article = $event->getControllerResult();

        if (!$article instanceof Article || !$event->getRequest()->isMethodSafe()) {
            return;
        }

        if ($article->getPublishedAt()->getTimestamp() > (new \DateTimeImmutable())->getTimestamp()) {
            throw new ArticleNotFoundException('Not found');
        }
    }
}