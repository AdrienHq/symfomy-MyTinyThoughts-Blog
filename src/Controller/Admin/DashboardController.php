<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Menu;
use App\Entity\Page;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use PhpParser\Node\Expr\Yield_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Symfony MyTinyThoughts CMS');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Return to the blog', 'fa fa-undo', 'app_home');

        if($this->isGranted('ROLE_ADMIN')){
            yield MenuItem::subMenu('Menus', 'fas fa-list')->setSubItems([
                MenuItem::linkToCrud('Pages', 'fas fa-file', Menu::class),
                MenuItem::linkToCrud('Articles', 'fas fa-newspaper', Menu::class),
                MenuItem::linkToCrud('Links', 'fas fa-link', Menu::class),
                MenuItem::linkToCrud('Categories', 'fab fa-delicious', Menu::class),
            ]);
        }

        if($this->isGranted('ROLE_AUTHOR')){
            yield MenuItem::subMenu('Articles', 'fas fa-newspaper')->setSubItems([
                MenuItem::linkToCrud('All articles', '', Article::class),
                MenuItem::linkToCrud('Add', '', Article::class)->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Categories', '', Category::class)
            ]);
        }

        if($this->isGranted('ROLE_ADMIN')){
            yield MenuItem::linkToCrud('Comments', 'fas fa-comment', Comment::class);
        }

        yield MenuItem::subMenu('Users accounts', 'fas fa-user')->setSubItems([
            MenuItem::linkToCrud('All users', 'fas fa-user-friends', User::class),
            MenuItem::linkToCrud('Add', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW),
        ]);
    }
}
