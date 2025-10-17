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
    #[Route('/save-task', name: 'save_task', methods: ['POST'])]
    public function saveTask(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $res = $request->getContent();
        /** @var User $user */
        $user = $this->getUser();

        $task = $user->getTask();
        $task->setTask($res);

        $em->flush();

        return $this->json('Task saved');
    }

    #[Route('/get-task', name: 'get_task', methods: ['GET'])]
    public function getTask(EntityManagerInterface $em): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $task = $user->getTask();
        return $this->json(['task' => json_decode($task->getTask(), true)]);
    }
}
