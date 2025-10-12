<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;

class AuthController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {

        // dd($request->request->get('email'));

        $data = json_decode($request->getContent(), true);
        
        
        // Проверяем обязательные поля
        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->json(['error' => 'Email and password are required'], Response::HTTP_BAD_REQUEST);
        }
        
        // Проверяем, не существует ли уже пользователь
        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return $this->json(['error' => 'User already exists'], Response::HTTP_CONFLICT);
        }
        
        // Создаем нового пользователя
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword(
            $passwordHasher->hashPassword($user, $data['password'])
        );

        $task = new Task();
        // Создаем пустую задачу для нового пользователя
        $task->setTask('');

        $task->setUser($user);
        $user->setTask($task);

        $entityManager->persist($task);
        $entityManager->persist($user);
        $entityManager->flush();
        
        return $this->json([
            'message' => 'User registered successfully',
            'email' => $user->getEmail()
        ], Response::HTTP_CREATED);
    }

    #[Route('/api/profile', name: 'api_profile', methods: ['GET'])]
    public function profile(): JsonResponse
    {
        // Этот метод доступен только аутентифицированным пользователям
        $user = $this->getUser();
        
        return $this->json([
            'message' => 'Welcome to your profile!',
            'user' => [
                'email' => $user->getUserIdentifier(),
                'roles' => $user->getRoles(),
            ]
        ]);
    }

    #[Route('/api/test', name: 'api_test', methods: ['GET'])]
    public function test(): Response
    {
        return $this->json(['message' => 'Hello World']);
    }
}