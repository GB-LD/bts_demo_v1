<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $hasher;
    protected $slugger;

    public function __construct(UserPasswordHasherInterface $hasher, SluggerInterface $slugger)
    {
        $this->hasher = $hasher;
        $this->slugger = $slugger;
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

        $userTest = new User();
        $passwordHash = $this->hasher->hashPassword($userTest, "password");
        $userTest
            ->setLastName('Doe')
            ->setFirstName('John')
            ->setEmail('john.doe@gmail.com')
            ->setPassword($passwordHash)
            ->setCity('Marseille')
            ->setPosteCode(13001);

        $manager->persist($userTest);

        for ($u = 0 ; $u < 50; $u++) {
            $user = new User();
            $passwordHash = $this->hasher->hashPassword($user, $faker->password());
            $user
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail($faker->email())
                ->setPassword($passwordHash)
                ->setCity('Marseille')
                ->setPosteCode(13001);

            $manager->persist($user);
        }

        $categories = ["Agendas et Calendriers", "Crayons", "Correction et taille-crayons", "Découpe", "Matériel de géométrie", "Colle et adhésif", "Ardoises", "Dessin et musique", "Cahiers", "Protège doc, chemise, trieurs", "classeurs", "Papiers", "Calculatrice"];

        foreach ($categories as $categoryName){
            $category = new Category();
            $category
                ->setName($categoryName)
                ->setSlug($this->slugger->slug($category->getName()));

            for ($u = 0 ; $u < mt_rand(15, 20); $u++) {
                $product = new Product();
                $product
                    ->setTitle($faker->productName)
                    ->setDescription($faker->text)
                    ->setSlug($this->slugger->slug($product->getTitle()))
                    ->setCategory($category);

                $manager->persist($product);
            }

            $manager->persist($category);
        }

        $manager->flush();
    }
}
