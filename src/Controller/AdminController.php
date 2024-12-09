<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Role;
use App\Entity\Courses;
use App\Entity\Themes;
use App\Entity\lessons;
use App\Entity\Purchases;
use App\Entity\Comprise;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;


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
        $themes = $entityManager->getRepository(Themes::class)->findAll();
        $courses = $entityManager->getRepository(Courses::class)->findAll();
        $lessons = $entityManager->getRepository(Lessons::class)->findAll();

        return $this->render('admin/content.html.twig', [
            'themes' => $themes,
            'courses' => $courses,
            'lessons' => $lessons,
        ]);
    }

    #[Route('/admin/content/themes/add', name: 'app_admin_add_theme')]
    public function addTheme(Request $request, EntityManagerInterface $entityManager): Response
    {
        $theme = new Themes();
        $form = $this->createFormBuilder($theme)
        ->add('name', TextType::class, ['label' => 'Nom du Thème'])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => false, // La description est facultative
        ])
        ->add('image', FileType::class, [
            'label' => 'Image',
            'mapped' => false, // Pour ne pas lier directement ce champ à l'entité
            'required' => false, // L'image est facultative
        ])
        ->add('save', SubmitType::class, ['label' => 'Ajouter le Thème'])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
        
            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
        
                // Déplace le fichier vers le répertoire configuré
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'), // Définissez ce paramètre dans votre configuration
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'upload de l\'image.');
                }
        
                // Met à jour le champ image de l'entité
                $theme->setImage($newFilename);
            }
            
            $theme->setCreatedBy($this->getUser()->getId()); // Utilisateur connecté
            $theme->setUpdatedBy($this->getUser()->getId()); // Initialiser updated_by également
        
            $entityManager->persist($theme);
            $entityManager->flush();
        
            $this->addFlash('success', 'Thème ajouté avec succès.');
            return $this->redirectToRoute('app_admin_content');
        }

        return $this->render('admin/add_theme.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/content/themes/edit/{id}', name: 'app_admin_edit_theme')]
    public function editTheme(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $theme = $entityManager->getRepository(Themes::class)->find($id);

        if (!$theme) {
            throw $this->createNotFoundException('Thème introuvable.');
        }

        $form = $this->createFormBuilder($theme)
            ->add('name', TextType::class, ['label' => 'Nom du Thème'])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false, // Facultatif
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false, // Non lié directement à l'entité
                'required' => false, // Facultatif
            ])
            ->add('save', SubmitType::class, ['label' => 'Modifier le Thème'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'upload de l\'image.');
                }

                // Mettre à jour le champ image de l'entité
                $theme->setImage($newFilename);
            }

            // Mettre à jour la date de modification et l'utilisateur
            $theme->setUpdatedAt(new \DateTimeImmutable());
            $theme->setUpdatedBy($this->getUser()->getId());

            $entityManager->persist($theme);
            $entityManager->flush();

            $this->addFlash('success', 'Thème modifié avec succès.');
            return $this->redirectToRoute('app_admin_content');
        }

        return $this->render('admin/edit_theme.html.twig', [
            'form' => $form->createView(),
            'theme' => $theme,
        ]);
    }


    #[Route('/admin/content/courses/add', name: 'app_admin_add_course')]
    public function addCourse(Request $request, EntityManagerInterface $entityManager): Response
    {
        $course = new Courses();
        $form = $this->createFormBuilder($course)
        ->add('name', TextType::class, ['label' => 'Nom du Cursus'])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => false,
        ])
        ->add('price', MoneyType::class, [
            'label' => 'Prix',
            'currency' => 'EUR',
            'required' => false,
        ])
        ->add('theme', EntityType::class, [
            'class' => Themes::class,
            'choice_label' => 'name',
            'label' => 'Thème associé',
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Ajouter le Cursus',
        ])
        ->getForm();


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $course->setCreatedBy($this->getUser()->getId());
            $course->setUpdatedBy($this->getUser()->getId());

            $entityManager->persist($course);
            $entityManager->flush();
            $this->addFlash('success', 'Cursus ajouté avec succès.');
            return $this->redirectToRoute('app_admin_content');
        }

        return $this->render('admin/add_course.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/content/courses/edit/{id}', name: 'app_admin_edit_course')]
    public function editCourse(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $course = $entityManager->getRepository(Courses::class)->find($id);

        if (!$course) {
            throw $this->createNotFoundException('Cursus introuvable.');
        }

        $form = $this->createFormBuilder($course)
        ->add('name', TextType::class, ['label' => 'Nom du Cursus'])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => false,
        ])
        ->add('price', MoneyType::class, [
            'label' => 'Prix',
            'currency' => 'EUR',
            'required' => false,
        ])
        ->add('theme', EntityType::class, [
            'class' => Themes::class,
            'choice_label' => 'name',
            'label' => 'Thème associé',
        ])
        ->add('save', SubmitType::class, [
            'label' =>'Modifier le Cursus'
        ])
        ->getForm();


        $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
            $course->setUpdatedAt(new \DateTimeImmutable());
            $course->setUpdatedBy($this->getUser()->getId());

            $entityManager->flush();
            $this->addFlash('success', 'Cursus modifié avec succès.');
            return $this->redirectToRoute('app_admin_content');
        }

        return $this->render('admin/edit_course.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/content/lessons/add', name: 'app_admin_add_lesson')]
    public function addLesson(Request $request, EntityManagerInterface $entityManager): Response
    {
        $lesson = new Lessons();
        $form = $this->createFormBuilder($lesson)
        ->add('name', TextType::class, ['label' => 'Titre de la Leçon'])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => false,
        ])
        ->add('video_url', UrlType::class, [
            'label' => 'Lien vidéo (optionnel)',
            'required' => false,
        ])
        ->add('price', MoneyType::class, [
            'label' => 'Prix',
            'currency' => 'EUR',
            'required' => false,
        ])
        ->add('course', EntityType::class, [
            'class' => Courses::class,
            'choice_label' => 'name',
            'label' => 'Cursus associé',
        ])
        ->add('save', SubmitType::class, ['label' => 'Ajouter la Leçon'])
        ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $lesson->setCreatedAt(new \DateTimeImmutable());
            $lesson->setCreatedBy($this->getUser()->getId());
            $lesson->setUpdatedBy($this->getUser()->getId());

            $entityManager->persist($lesson);
            $entityManager->flush();
            $this->addFlash('success', 'Leçon ajoutée avec succès.');
            return $this->redirectToRoute('app_admin_content');
        }

        return $this->render('admin/add_lesson.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/admin/content/lessons/edit/{id}', name: 'app_admin_edit_lesson')]
    public function editLesson(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $lesson = $entityManager->getRepository(Lessons::class)->find($id);

        if (!$lesson) {
            throw $this->createNotFoundException('Leçon introuvable.');
        }

        $form = $this->createFormBuilder($lesson)
        ->add('name', TextType::class, ['label' => 'Titre de la Leçon'])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'required' => false,
        ])
        ->add('video_url', UrlType::class, [
            'label' => 'Lien vidéo (optionnel)',
            'required' => false,
        ])
        ->add('price', MoneyType::class, [
            'label' => 'Prix',
            'currency' => 'EUR',
            'required' => false,
        ])
        ->add('course', EntityType::class, [
            'class' => Courses::class,
            'choice_label' => 'name',
            'label' => 'Cursus associé',
        ])
        ->add('save', SubmitType::class, ['label' => 'Modifier la Leçon'])
        ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $lesson->setUpdatedAt(new \DateTimeImmutable());
            $lesson->setUpdatedBy($this->getUser()->getId());

            $entityManager->flush();
            $this->addFlash('success', 'Leçon modifiée avec succès.');
            return $this->redirectToRoute('app_admin_content');
        }

        return $this->render('admin/edit_lesson.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/content/delete/{type}/{id}', name: 'app_admin_delete')]
    public function deleteContent(string $type, int $id, EntityManagerInterface $entityManager): Response
    {
        $repository = match ($type) {
            'theme' => Themes::class,
            'course' => Courses::class,
            'lesson' => Lessons::class,
            default => null,
        };

        if (!$repository) {
            throw $this->createNotFoundException('Type invalide.');
        }

        $entity = $entityManager->getRepository($repository)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Contenu introuvable.');
        }

        $entityManager->remove($entity);
        $entityManager->flush();

        $this->addFlash('success', ucfirst($type) . ' supprimé avec succès.');
        return $this->redirectToRoute('app_admin_content');
    }
  

    #[Route('/admin/purchases', name: 'app_admin_purchases')]
    public function managePurchases(EntityManagerInterface $entityManager): Response
{
    // Récupérer tous les achats
    $purchases = $entityManager->getRepository(Purchases::class)
        ->findBy([], ['user' => 'ASC']); // Trier par utilisateur

    // Récupérer les Comprises associées à chaque achat
    $comprises = $entityManager->getRepository(Comprise::class)->findAll();

    // Envoyer les données aux templates
    return $this->render('admin/purchases.html.twig', [
        'purchases' => $purchases,
        'comprises' => $comprises,
    ]);
}


}
