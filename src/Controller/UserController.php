<?php

namespace App\Controller;

use App\Entity\Hamster;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserController extends AbstractController
{
    private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->hasher = $passwordHasher;
    }

    #[Route('/api/register', name: 'app_user_register', methods: ['POST'])]
    public function registerUser(UserRepository $repo, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $content = $request->getContent();
        $user = $serializer->deserialize($content, User::class, "json", []);
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return $this->json([
                'errors' => "L'adresse email n'est pas valide."
            ], Response::HTTP_BAD_REQUEST);
        }
        $password = $user->getPassword();
        if (!$password || strlen($password) < 8) {
            return $this->json([
                'errors' => 'Le mot de passe doit faire au moins 8 caractères.'
            ], Response::HTTP_BAD_REQUEST);
        }
        $user->setRoles([User::ROLE_USER]);
        $user->setGold(User::GOLD_STARTER);
        $user->setPassword(
            $this->hasher->hashPassword(
                $user,
                $user->getPassword()
            )
        );

        for ( $i = 0; $i < 2; $i++ ) {
            $hamster = $this->createHamster(Hamster::GENRE_M, $user);
            $em->persist($hamster);
            $hamster = $this->createHamster(Hamster::GENRE_F, $user);
            $em->persist($hamster); 
        }

        $em->persist($user);
        $em->flush();

        return $this->json(
            [
                'user' => $user
            ],
            Response::HTTP_CREATED,
            [],
            [
                "groups" => "user"
            ]
        );
    }

    #[Route('/api/delete/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function deleteUserById(User $user, EntityManagerInterface $em): JsonResponse
    {
        $id = $user->getId();
        $em->remove($user);
        $em->flush();
        return $this->json([
            'message' => "L'utilisateur suivant a bien été supprimé, id : " . $id
        ]);
    }

    #[Route('/api/user', name: 'app_user_info', methods: ['GET'])]
    public function getInfoUser(): JsonResponse
    {
        $user = $this->getUser();
        return $this->json(
            [
                'user' => $user
            ],
            Response::HTTP_OK,
            [],
            [
                "groups" => "user"
            ]
        );
    }

    public function createHamster($genre, $user): Hamster
    {
        $hamster = new Hamster();
        $hamster->setGenre($genre);
        $faker = \Faker\Factory::create('fr_FR');
        $hamster->setName($faker->name());
        $hamster->setOwner($user);
        $hamster->setHunger(Hamster::HUNGER);
        $hamster->setAge(Hamster::AGE);
        $hamster->setActive(Hamster::ACTIVE);
        return $hamster;
    }
}
