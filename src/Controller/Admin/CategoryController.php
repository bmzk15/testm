<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Utils\Slugger;
use Doctrine\ORM\UnitOfWork;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 *
 * @package App\Controller\Admin
 * @author Boris MALEZYK <contact@borismalezyk.com>
 *
 * @Route("/admin/category")
 * @IsGranted("ROLE_ADMIN")
 */
class CategoryController extends AbstractController
{
    /**
     * @param \App\Repository\CategoryRepository $categoryRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/category", methods={"GET"}, name="admin_category_index")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy([], ['name' => 'ASC']);

        return $this->render('admin/blog/index.html.twig', ['categories' => $categories]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/new", methods={"GET", "POST"}, name="admin_category_new")
     */
    function new(Request $request): Response
    {
        return $this->register($request, new Category(), $this->generateUrl('admin_category_new'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Category                      $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{id<\d+>}/edit",methods={"GET", "POST"}, name="admin_category_edit")
     */
    public function edit(Request $request, Category $category): Response
    {
        return $this->register($request, $category, $this->generateUrl('admin_category_edit', [
            'id' => $category->getId(),
        ]));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Entity\Category                      $category
     * @param string                                    $action
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    private function register(Request $request, Category $category, string $action)
    {
        $form = $this->createForm(CategoryType::class, $category, [
            $action,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->save($category);
            #TODO - DYNAMIC MESSAGE BY ACTION
            $this->addFlash('success', 'category.register_successfully');

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/register.html.twig', [
            'category' => $category,
            'form'     => $form->createView(),
        ]);
    }

    /**
     * @param \App\Entity\Category $category
     */
    private function save(Category $category)
    {
        $category->setSlug(Slugger::slugify($category->getName()));
        $em = $this->getDoctrine()->getManager();

        if ($em->getUnitOfWork()->getEntityState($category) !== UnitOfWork::STATE_MANAGED) {
            $em->persist($category);
        }

        $em->flush();
    }
}
