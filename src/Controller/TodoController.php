<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TodoController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/TodoController.php',
        // ]);

        return $this->render('index.html.twig');
    }

    /**
     * @Route("/all", name="all")
     */
    public function all(): Response
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findBy([],['id'=>'DESC']);

        return $this->render('all.html.twig', ['tasks' => $tasks]);
    }

    /**
     * @Route("/create", name="create-task", methods={"POST"})
     */
    public function create(Request $request) {

        $name = $request->request->get('task');

        $status = 0;

        $author = 'johndoe@name.com';

        $objectManager = $this->getDoctrine()->getManager();

        $lastTask = $objectManager->getRepository(Task::class)->findOneBy([], ['id' => 'desc']);

        $lastId = $lastTask->getId();

        $newId = $lastId + 1;

        $task = new Task;

        $task->setId($newId);

        $task->setName($name);

        $task->setStatus($status);

        $task->setAuthor($author);

        $objectManager->persist($task);

        $objectManager->flush();

        return $this->redirectToRoute('all');

    }

    /**
     * @Route("/updateStatus/{id}", name="update-status")
     */
    public function updateTaskStatus($id) {

        $objectManager = $this->getDoctrine()->getManager();

        $task = $objectManager->getRepository(Task::class)->find($id);

        $task->setStatus(!$task->getStatus());

        $objectManager->flush();

        return $this->redirectToRoute('all');

    }

    /**
     * @Route("/deleteTask/{id}", name="delete-task")
     */
    public function delete(Task $id) {

        $objectManager = $this->getDoctrine()->getManager();

        $objectManager->remove($id);

        $objectManager->flush();

        return $this->redirectToRoute('all');

    }

}
