<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Subject;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    protected $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

        $authors = [];

        $userTest = new User();
        $passwordHash = $this->hasher->hashPassword($userTest, "password");
        $userTest
            ->setLastName('Doe')
            ->setFirstName('John')
            ->setEmail('john.doe@gmail.com')
            ->setPassword($passwordHash)
            ->setCity('Marseille')
            ->setPosteCode(13001)
            ->setRoles(["ROLE_USER", "ROLE_ADMIN"]);

        $manager->persist($userTest);

        for ($u = 0 ; $u < 20; $u++) {
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
            $authors[] = $user;
        }

        $categories = ["Manuel Scolaire", "Crayons, Trousse", "Papeterie, Cahier, Carnet", "Matériel de géométrie", "Colle et adhésif", "Classer, Organiser", "Sac, Cartable", "Calculatrice"];

        $categoriesEntities = [];

        foreach ($categories as $categoryName){
            $category = new Category();
            $category
                ->setName($categoryName);

            $manager->persist($category);
            $categoriesEntities[] = $category;
        }

        $subjects = ["Autres", "Français", "Mathématiques", "Histoire-géographie", "Enseignement moral et civique", "Langues vivantes", "Sciences de la vie et de la Terre", "Physique-chimie", "Technologie", "Arts plastiques", "Éducation musicale"];

        $subjectsEntities = [];


        foreach ($subjects as $subjectName) {
            $subject = new Subject();
            $subject
                ->setName($subjectName);

            $manager->persist($subject);
            $subjectsEntities[] = $subject;
        }

        for ($u = 0 ; $u < 50; $u++) {
            $product = new Product();
            $product
                ->setTitle($faker->productName)
                ->setDescription($faker->text)
                ->setCategory($categoriesEntities[mt_rand(0, count($categoriesEntities) - 1)])
                ->setSubject($subjectsEntities[mt_rand(0, count($subjectsEntities) - 1)])
                ->setAuthor($authors[mt_rand(0, count($authors) - 1)]);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
