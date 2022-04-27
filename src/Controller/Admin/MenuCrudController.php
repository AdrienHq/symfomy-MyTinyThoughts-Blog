<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuCrudController extends AbstractCrudController
{
    const MENU_PAGES = 0;
    const MENU_ARTICLES = 1;
    const MENU_LINKS = 2;
    const MENU_CATEGORIES = 3;

    private RequestStack $requestStack;
    private MenuRepository $menuRepository;

    public function __construct(RequestStack $requestStack, MenuRepository $menuRepository){
        $this->requestStack=$requestStack;
        $this->menuRepository=$menuRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Menu::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $subMenuIndex = $this->getSubMenuIndex();

        return $this->menuRepository->getIndexQueryBuilder($this->getFieldNameFromSubMenuIndex($subMenuIndex));
    }

    public function configureCrud(Crud $crud): Crud
    {
        $subMenuIndex = $this->getSubMenuIndex();
        $entityLabelInSingular = 'a menu';
        $entityLabelInPlural = null;

        switch ($subMenuIndex){
            case self::MENU_ARTICLES:
                $entityLabelInPlural = 'Articles';
                break;
            case self::MENU_CATEGORIES:
                $entityLabelInPlural = 'Categories';
                break;
            case self::MENU_LINKS:
                $entityLabelInPlural = 'Links';
                break;
            case self::MENU_PAGES:
                $entityLabelInPlural = 'Pages';
                break;
        };

        return $crud
            ->setEntityLabelInSingular($entityLabelInSingular)
            ->setEntityLabelInPlural($entityLabelInPlural);

    }

    public function configureFields(string $pageName): iterable
    {
        $subMenuIndex = $this->getSubMenuIndex();

        yield TextField::new('name', 'Navigation');
        yield NumberField::new('menuOrder', 'Order');
        yield $this->getFieldsFromSubMenuIndex($subMenuIndex)->setRequired(true);
        yield BooleanField::new('isVisible', 'Visible');
        yield AssociationField::new('subMenus', 'Sub-Elements');

    }

    private function getFieldNameFromSubMenuIndex(int $subMenuIndex): string
    {
        switch ($subMenuIndex){
            case self::MENU_ARTICLES:
                $fieldName = 'article';
                break;
            case self::MENU_CATEGORIES:
                $fieldName = 'category';
                break;
            case self::MENU_LINKS:
                $fieldName = 'link';
                break;
            default:
                $fieldName = 'page';
                break;
        }
        return $fieldName;
    }

    /**
     * @param int $subMenuIndex
     * @return AssociationField|TextField
     */
    private function getFieldsFromSubMenuIndex(int $subMenuIndex)
    {
        $fieldName = $this->getFieldNameFromSubMenuIndex($subMenuIndex);

        return ($fieldName == 'link') ? TextField::new($fieldName, 'Link') : AssociationField::new($fieldName);

    }

    private function getSubMenuIndex(): int
    {
        return $this->requestStack->getMainRequest()->query->getInt('submenuIndex');
    }


}
