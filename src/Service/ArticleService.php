<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ArticleService
{
    private RequestStack $requestStack;
    private ArticleRepository $articleRepository;
    private PaginatorInterface $paginator;

    public function __construct(RequestStack $requestStack, ArticleRepository $articleRepository, PaginatorInterface $paginator)
    {
        $this->requestStack = $requestStack;
        $this->articleRepository = $articleRepository;
        $this->paginator = $paginator;
    }

    public function getPaginatedArticles(?Category $category = null): PaginationInterface
    {
        $request = $this->requestStack->getMainRequest();
        $page = $request->query->getInt('page', 1);
        $limit = 2;
        $articleQuery = $this->articleRepository->findForPagination($category);

        return $this->paginator->paginate($articleQuery, $page, $limit);

    }

}