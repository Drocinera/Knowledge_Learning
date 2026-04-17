<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Role;
use App\Entity\Courses;
use App\Entity\Themes;
use App\Entity\Lessons;
use App\Entity\Purchases;
use App\Entity\Comprise;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * AdminController
 */
class AdminController extends AbstractController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    #[Route('/admin', name: 'app_admin_dashboard')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/users', name: 'app_admin_users')]
    public function manageUsers(EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $entityManager->getRepository(Users::class)->findAll(),
        ]);
    }

    #[Route('/admin/users/add', name: 'app_admin_add_user')]
    public function addUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $user = new Users();
            $user->setEmail($request->request->get('email'));

            $role = $entityManager->getRepository(Role::class)
                ->find($request->request->get('role'));

            if (!$role) {
                throw $this->createNotFoundException('Rôle introuvable.');
            }

            $user->setRole($role);

            $user->setActive($request->request->getBoolean('is_active'));

            $plainPassword = $request->request->get('password');
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $plainPassword)
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur ajouté avec succès.');

            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/add_user.html.twig', [
            'roles' => $entityManager->getRepository(Role::class)->findAll(),
        ]);
    }

    #[Route('/admin/users/edit/{id}', name: 'app_admin_edit_user')]
    public function editUser(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        if ($request->isMethod('POST')) {
            $role = $entityManager->getRepository(Role::class)
                ->find($request->request->get('role'));

            $user->setRole($role);
            $user->setEmail($request->request->get('email'));
            $user->setActive($request->request->getBoolean('is_active'));

            $entityManager->flush();

            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/edit_user.html.twig', [
            'user' => $user,
            'roles' => $entityManager->getRepository(Role::class)->findAll(),
        ]);
    }

    #[Route('/admin/users/delete/{id}', name: 'app_admin_delete_user', methods: ['POST'])]
    public function deleteUser(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(Users::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/admin/content', name: 'app_admin_content')]
    public function manageContent(EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin/content.html.twig', [
            'themes' => $entityManager->getRepository(Themes::class)->findAll(),
            'courses' => $entityManager->getRepository(Courses::class)->findAll(),
            'lessons' => $entityManager->getRepository(Lessons::class)->findAll(),
        ]);
    }

    #[Route('/admin/content/delete/{type}/{id}', name: 'app_admin_delete')]
    public function deleteContent(string $type, int $id, EntityManagerInterface $entityManager): Response
    {
        $map = [
            'theme' => Themes::class,
            'course' => Courses::class,
            'lesson' => Lessons::class,
        ];

        if (!isset($map[$type])) {
            throw $this->createNotFoundException('Type invalide.');
        }

        $entity = $entityManager->getRepository($map[$type])->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Contenu introuvable.');
        }

        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_content');
    }

    #[Route('/admin/purchases', name: 'app_admin_purchases')]
    public function managePurchases(EntityManagerInterface $entityManager): Response
    {
        return $this->render('admin/purchases.html.twig', [
            'purchases' => $entityManager->getRepository(Purchases::class)
                ->findBy([], ['id' => 'DESC']),
            'comprises' => $entityManager->getRepository(Comprise::class)->findAll(),
        ]);
    }
}