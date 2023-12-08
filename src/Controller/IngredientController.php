<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Faker\Calculator\Ean;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientController extends AbstractController
{/**
 * This function display all ingredients
 *
 * @param IngredientRepository $repository
 * @param PaginatorInterface $paginator
 * @param Request $request
 * @return Response
 */
    #[Route('/ingredient', name: 'ingredient_index', methods: ['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $Ingredients = $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), 
            10 
        );

        return $this->render('Pages/ingredient/index.html.twig', ['ingredients' => $Ingredients ]);
    }

    #[Route('/ingredient/nouveau', name:'ingredient.new', methods: ['GET', 'POST'])]
    public function new(Request $request,
    EntityManagerInterface $manager
    ): Response
{
    $ingredient= new Ingredient();
    $form = $this->createForm(IngredientType::class, $ingredient);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
        $ingredient = $form->getData();
        $manager -> persist($ingredient);
        $manager ->flush();

        
    $this->addFlash(
        'success',
        'votre ingredient a été créé avec succès!'
    );
    return  $this->redirectToRoute('ingredient_index');
    }


    return $this->render('Pages/ingredient/new.html.twig', 
['form'=> $form-> createview() ]);
} 

#[Route('/ingredient/edition/{id}', 'ingredient.edit', methods: ['GET', 'POST'])]
public function edit(Ingredient $ingredient,Request $request, EntityManagerInterface $manager) : Response
{
    
    $form=$this->createForm(IngredientType::class, $ingredient);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
        $ingredient = $form->getData();
        $manager -> persist($ingredient);
        $manager ->flush();

        
    $this->addFlash(
        'success',
        'votre ingredient a été modifié avec succès!'
    );
    return  $this->redirectToRoute('ingredient_index');
    }

return $this->render('Pages/ingredient/edit.html.twig', 
['form'=>$form->createView()]);

}
#[Route('/ingredient/suppression/{id}', 'ingredient.delete', methods: ['GET'])]
public function delete(EntityManagerInterface $manager,Ingredient $ingredient
):Response{

    $manager -> remove($ingredient);
    $manager ->flush();

    
$this->addFlash(
    'success',
    'votre ingredient a été supprimer avec succès!'
);
return  $this->redirectToRoute('ingredient_index');
}
}
