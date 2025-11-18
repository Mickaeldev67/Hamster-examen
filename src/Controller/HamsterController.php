<?php

namespace App\Controller;

use App\Entity\Hamster;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Repository\HamsterRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\Json;

final class HamsterController extends AbstractController
{
    #[Route('/api/hamsters', name: 'app_hamster', methods: ['GET'])]
    public function getAllHamsters(): JsonResponse
    {
        $user = $this->getUser();
        $hamsters = $user->getHamsters();
        return $this->json([
            'hamsters' => $hamsters
        ], JsonResponse::HTTP_OK, [], [
            "groups" => "hamster"
        ]);
    }

    #[Route('/api/hamsters/{id}', name: 'app_hamster', methods: ['GET'])]
    public function getHamsterById(Hamster $hamster, Security $security): JsonResponse
    {
        $user = $this->getUser();

        if($hamster->getOwner()->getId() === $user->getId()) {
            return $this->json([
                'hamster' => $hamster
            ], JsonResponse::HTTP_OK, [], [
                "groups" => "hamster"
            ]);
        } else {
            return $this->json([
                'error' => 'Hamster not found'
            ], JsonResponse::HTTP_FORBIDDEN);
        }
    }
}
