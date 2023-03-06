<?php

namespace App\Controller;

use App\Data\Contact;
use App\Data\SearchData;
use App\Entity\Product;
use App\Form\AdType;
use App\Form\ContactType;
use App\Form\SearchType;
use App\Notifications\ContactNotification;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdController extends AbstractController
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger){
        $this->slugger = $slugger;
    }

    #[Route('/annonces', name: 'index_annonces')]
    public function searchProducts(ProductRepository $productRepository, Request $request) : Response
    {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
        $products = $productRepository->findSearch($data);

        return $this->render('ad/searchAds.html.twig', [
            'formView' => $form->createView(),
            'products' => $products
        ]);
    }

    #[Route('/annonce/{slug}', name: 'show_annonce', priority: -1)]
    public function showProduct($slug, Product $product, Security $security, Request $request, ContactNotification $notification): Response
    {
        $productOwner = $product->getAuthor()->getEmail();
        $user = $security->getUser();
        $contact = new Contact();
        $contact->setFirstName($user->getFirstName());
        $contact->setLastName($user->getLastName());
        $contact->setEmail($user->getUserIdentifier());
        $contact->setAd($product);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $notification->notify($contact, $productOwner);

            $this->addFlash(
                'success',
                'Votre message à bien été envoyé'
            );
        }

        return $this->render('ad/showProduct.html.twig', [
            'product' => $product,
            'formView' => $form->createView()
        ]);
    }

    #[Route('/annonce/creation', name: 'create_annonce')]
    public function create(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        $product = new Product();
        $form = $this->createForm(AdType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $title = $form->getData()["title"];
            $description = $form->getData()["description"];
            $category = $form->getData()["category"];
            $subject = $form->getData()["subject"];

            $product->setTitle($title);
            $product->setDescription($description);
            $product->setCategory($category);
            $product->setSubject($subject);
            $product->setSlug($this->slugger->slug(strtolower($product->getTitle())));
            $product->setAuthor($user);

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre annonce a bien été enregistrée'
            );

            return $this->redirectToRoute('show_annonce', [
                'slug' => $product->getSlug()
            ]);
        }

        return $this->render('ad/adCreationPage.html.twig', [
            "formView" => $form->createView()
        ]);
    }

    #[Route('/annonce/{slug}/modifier', name: 'edit_annonce')]
    public function edit(Product $product, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(AdType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $title = $product->getTitle();
            $description = $product->getDescription();
            $category = $product->getCategory();
            $subject = $product->getSubject();

            $product->setTitle($title);
            $product->setDescription($description);
            $product->setCategory($category);
            $product->setSubject($subject);
            $product->setSlug($this->slugger->slug(strtolower($product->getTitle())));

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre annonce a bien été modifiée'
            );

            return $this->redirectToRoute('show_annonce', [
                'slug' => $product->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            'formView' => $form->createView(),
            'product' => $product
        ]);
    }
}
