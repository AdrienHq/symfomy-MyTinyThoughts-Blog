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
    private OptionService $optionService;

    public function __construct(RequestStack $requestStack, ArticleRepository $articleRepository, PaginatorInterface $paginator, OptionService $optionService)
    {
        $this->requestStack = $requestStack;
        $this->articleRepository = $articleRepository;
        $this->paginator = $paginator;
        $this->optionService = $optionService;
    }

    public function getPaginatedArticles(?Category $category = null): PaginationInterface
    {
        $request = $this->requestStack->getMainRequest();
        $page = $request->query->getInt('page', 1);
        $limit = $this->optionService->getValue('blog_articles_limit');
        $articleQuery = $this->articleRepository->findForPagination($category);

        return $this->paginator->paginate($articleQuery, $page, $limit);

    }

}