<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GetLastPostController extends AbstractController
{
    #[Route('/api/articles/last', name: 'article_get_last_post', methods: ['GET'])]
    public function getLastArticle(ArticleRepository $articleRepo, NormalizerInterface $normalizer): Response
    {
        return $this->json($normalizer->normalize($articleRepo->findLastArticle(), context: [
            'groups' => ['article:read']
        ]));
    }
}