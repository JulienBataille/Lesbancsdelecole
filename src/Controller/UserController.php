<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/admin/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users'=>$userRepository->findAll()
        ]);
    }

    #[Route('/admin/user/{id}/add/editor', name: 'app_user_add_editor')]
    public function changeRole(User $user, EntityManagerInterface $em): Response
    {
        $user->setRoles(["ROLE_EDITOR","ROLE_USER"]);
        $em->flush();

        $this->addFlash('success','Le rôle éditeur a bien été ajouté');

        return $this->redirectToRoute('app_user');
    }

    #[Route('/admin/user/{id}/remove/editor/role', name: 'app_user_remove_editor_role')]
    public function removeRoleEditor(User $user, EntityManagerInterface $em): Response
    {
        $user->setRoles([]);
        $em->flush();

        $this->addFlash('danger','Le rôle éditeur a bien été retiré');

        return $this->redirectToRoute('app_user');
    }

    #[Route('/admin/user/{id}/remove', name: 'app_user_remove')]
    public function userRemove(UserRepository $userRepository, EntityManagerInterface $em,$id): Response
    {
        $userFind = $userRepository->find($id);
        $em->remove($userFind);
        $em->flush();

        $this->addFlash('danger',"L'utilisateur a bien été supprimé");

        return $this->redirectToRoute('app_user');
    }
}
