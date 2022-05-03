<?php

namespace App\Service;

use App\Entity\Article;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CommentService
{
    private RequestStack $requestStack;
    private CommentRepository $commentRepository;
    private PaginatorInterface $paginator;

    public function __construct(RequestStack $requestStack, CommentRepository $commentRepository, PaginatorInterface $paginator)
    {
        $this->requestStack = $requestStack;
        $this->commentRepository = $commentRepository;
        $this->paginator = $paginator;
    }

    public function getPaginatedComments(?Article $article = null): PaginationInterface
    {
        $request = $this->requestStack->getMainRequest();
        $page = $request->query->getInt('page', 1);
        $limit = 5;
        $commentsQuery = $this->commentRepository->findForPagination($article);

        return $this->paginator->paginate($commentsQuery, $page, $limit);

    }

}