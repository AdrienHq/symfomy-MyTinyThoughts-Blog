<?php

namespace App\Twig;

use App\Entity\Menu;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    const ADMIN_NAMESPACE = 'App\Controller\Admin';
    private RouterInterface $router;
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(RouterInterface $router, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->router = $router;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('menuLink', [$this, 'menuLink']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('ea_url_generator', [$this, 'getAdminUrl']),
        ];
    }

    public function getAdminUrl(string $controller, ?string $action = null): string
    {
        $urlGenerator = $this->adminUrlGenerator
            ->setController(self::ADMIN_NAMESPACE . DIRECTORY_SEPARATOR . $controller);

        if($action){
            $urlGenerator->setAction($action);
        }

        return $urlGenerator->generateUrl();
    }

    public function menuLink(Menu $menu): string
    {
        $article = $menu->getArticle();
        $category = $menu->getCategory();
        $page = $menu->getPage();

        $url = $menu->getLink() ?: '#';

        if($url != '#'){
            return $url;
        }

        if($article){
            $name = 'article_show';
            $slug = $article->getSlug();
        }

        if($category){
            $name = 'category_show';
            $slug = $category->getSlug();
        }

        if($page){
            $name = 'page_show';
            $slug = $page->getSlug();
        }

        if(!isset($name, $slug)){
            return $url;
        }

        return $this->router->generate($name, [
            'slug' => $slug
        ]);

    }

}