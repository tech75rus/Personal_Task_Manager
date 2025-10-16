<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class TaskController extends AbstractController
{
    #[Route('/save-task', name: 'app_task', methods: ['POST'])]
    public function saveTask(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $res = $request->getContent();
        /** @var User $user */
        $user = $this->getUser();

        $task = $user->getTask();
        $task->setTask($res);

        $em->flush();

        return $this->json($res);
    }
}
