<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Role;
use App\Entity\Courses;
use App\Entity\Purchases;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AdminController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/admin', name: 'app_admin_dashboard')]
    public function index(): Response
    {
        // Vérifie si l'utilisateur a le rôle ROLE_ADMIN
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/users', name: 'app_admin_users')]
    public function manageUsers(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(Users::class)->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/users/add', name: 'app_admin_add_user')]
    public function addUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $user = new Users();
            $user->setEmail($request->request->get('email'));

            $roleId = $request->request->get('role');
            $role = $entityManager->getRepository(Role::class)->find($roleId);

            if (!$role) {
                throw $this->createNotFoundException('Le rôle sélectionné est introuvable.');
            }

            $user->setRole($role);
            $user->setActive(true);

            $user->setActive($request->request->get('is_active') === 'on');

            // Hashing password
            $hashedPassword = $this->passwordHasher->hashPassword($user, $request->request->get('password'));
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur ajouté avec succès.');

            return $this->redirectToRoute('app_admin_users');
        }

        $roles = $entityManager->getRepository(Role::class)->findAll();

        return $this->render('admin/add_user.html.twig', [
        'roles' => $roles,
    ]);
}

    #[Route('/admin/users/edit/{id}', name: 'app_admin_edit_user')]
    public function editUser(
        int $id, 
        Request $request, 
        EntityManagerInterface $entityManager,
    ): Response {
        $user = $entityManager->getRepository(Users::class)->find($id);
        $roles = $entityManager->getRepository(Role::class)->findAll();

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        if ($request->isMethod('POST')) {
            $roleId = $request->request->get('role');
            $role = $entityManager->getRepository(Role::class)->find($roleId);
            $user->setRole($role);

            $user->setEmail($request->request->get('email'));
            $user->setActive($request->request->get('is_active') === 'on');

            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur mis à jour avec succès.');

            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/edit_user.html.twig', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }
    #[Route('/admin/users/delete/{id}', name: 'app_admin_delete_user', methods: ['POST', 'DELETE'])]
    public function deleteUser(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès.');

        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/admin/content', name: 'app_admin_content')]
    public function manageContent(EntityManagerInterface $entityManager): Response
    {
        $courses = $entityManager->getRepository(Course::class)->findAll();

        return $this->render('admin/content.html.twig', [
            'courses' => $courses,
        ]);
    }

    #[Route('/admin/purchases', name: 'app_admin_purchases')]
    public function managePurchases(EntityManagerInterface $entityManager): Response
    {
        $purchases = $entityManager->getRepository(Purchase::class)->findAll();

        return $this->render('admin/purchases.html.twig', [
            'purchases' => $purchases,
        ]);
    }


}
