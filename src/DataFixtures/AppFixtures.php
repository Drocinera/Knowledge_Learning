<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Users;
use App\Entity\Themes;
use App\Entity\Courses;
use App\Entity\Lessons;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

public function __construct(UserPasswordHasherInterface $passwordHasher)
{
    $this->passwordHasher = $passwordHasher;
}

    public function load(ObjectManager $manager): void
    {
        // Create Roles
        
        $roleAdmin = new Role();
        $roleAdmin->setName('ROLE_ADMIN');
        $roleAdmin->setDescription('Administrator role with full permissions.');
        $manager->persist($roleAdmin);

        $roleUser = new Role();
        $roleUser->setName('ROLE_USER');
        $roleUser->setDescription('Standard user role with limited permissions.');
        $manager->persist($roleUser);

        // Create Users

        $user1 = new Users();
        $user1->setEmail('fakeadmin@fakemail.com');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'adminpassword'));
        $user1->setRole($roleAdmin);
        $user1->setActive(true);
        $user1->setCreatedBy('admin');
        $user1->setUpdatedBy('admin');
        $manager->persist($user1);

        $user2 = new Users();
        $user2->setEmail('fakeuser@fakemail.com');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'userpassword'));
        $user2->setRole($roleUser);
        $user2->setActive(true);
        $user2->setCreatedBy('admin');
        $user2->setUpdatedBy('admin');
        $manager->persist($user2);

        // Create themes 

            $theme1 = new Themes();
            $theme1->setName('Introduction au web');
            $theme1->setDescription('Apprendre les bases du web.');
            $theme1->setCreatedBy('admin');
            $theme1->setUpdatedBy('admin');
            $theme1->setCreatedAt(new \DateTimeImmutable());
            $theme1->setUpdatedAt(new \DateTimeImmutable());
            $theme1->setImage('Introduction_au_web.jpg');

            $theme2 = new Themes();
            $theme2->setName('HTML');
            $theme2->setDescription('Apprendre les bases d\'HTML.');
            $theme2->setCreatedBy('admin');
            $theme2->setUpdatedBy('admin');
            $theme2->setCreatedAt(new \DateTimeImmutable());
            $theme2->setUpdatedAt(new \DateTimeImmutable());
            $theme2->setImage('Image_HTML.jpg');

            $theme3 = new Themes();
            $theme3->setName('CSS');
            $theme3->setDescription('Apprendre les bases de CSS.');
            $theme3->setCreatedBy('admin');
            $theme3->setUpdatedBy('admin');
            $theme3->setCreatedAt(new \DateTimeImmutable());
            $theme3->setUpdatedAt(new \DateTimeImmutable());
            $theme3->setImage('Image_CSS.jpg');

            $theme4 = new Themes();
            $theme4->setName('JavaScript');
            $theme4->setDescription('Apprendre les bases de JavaScript.');
            $theme4->setCreatedBy('admin');
            $theme4->setUpdatedBy('admin');
            $theme4->setCreatedAt(new \DateTimeImmutable());
            $theme4->setUpdatedAt(new \DateTimeImmutable());
            $theme4->setImage('Image-JS.jpg');

            $manager->persist($theme1);
            $manager->persist($theme2);
            $manager->persist($theme3);
            $manager->persist($theme4);
    
        // Create Courses

        $course1 = new Courses();
        $course1->setName('Comprendre le Web');
        $course1->setPrice('50.00');
        $course1->setDescription('Comprendre le Web');
        $course1->setCreatedBy('admin');
        $course1->setUpdatedBy('admin');
        $course1->setCreatedAt(new \DateTimeImmutable());
        $course1->setUpdatedAt(new \DateTimeImmutable());
        $course1->setTheme($theme1);
        
        $course2 = new Courses();
        $course2->setName('Les bases du HTML');
        $course2->setPrice('50.00');
        $course2->setDescription('Les bases du HTML');
        $course2->setCreatedBy('admin');
        $course2->setUpdatedBy('admin');
        $course2->setCreatedAt(new \DateTimeImmutable());
        $course2->setUpdatedAt(new \DateTimeImmutable());
        $course2->setTheme($theme2);

        $course3 = new Courses();
        $course3->setName('Structurer un contenu');
        $course3->setPrice('60.00');
        $course3->setDescription('Structurer un contenu');
        $course3->setCreatedBy('admin');
        $course3->setUpdatedBy('admin');
        $course3->setCreatedAt(new \DateTimeImmutable());
        $course3->setUpdatedAt(new \DateTimeImmutable());
        $course3->setTheme($theme2);

        $course4 = new Courses();
        $course4->setName('Les bases du CSS');
        $course4->setPrice('30.00');
        $course4->setDescription('Les bases du CSS');
        $course4->setCreatedBy('admin');
        $course4->setUpdatedBy('admin');
        $course4->setCreatedAt(new \DateTimeImmutable());
        $course4->setUpdatedAt(new \DateTimeImmutable());
        $course4->setTheme($theme3);

        $course5 = new Courses();
        $course5->setName('Les bases du JavaScript');
        $course5->setPrice('44.00');
        $course5->setDescription('Les bases du JavaScript');
        $course5->setCreatedBy('admin');
        $course5->setUpdatedBy('admin');
        $course5->setCreatedAt(new \DateTimeImmutable());
        $course5->setUpdatedAt(new \DateTimeImmutable());
        $course5->setTheme($theme4);

        $course6 = new Courses();
        $course6->setName('Interagir avec la page');
        $course6->setPrice('48.00');
        $course6->setDescription('Interagir avec la page');
        $course6->setCreatedBy('admin');
        $course6->setUpdatedBy('admin');
        $course6->setCreatedAt(new \DateTimeImmutable());
        $course6->setUpdatedAt(new \DateTimeImmutable());
        $course6->setTheme($theme4);
        
        $manager->persist($course1);
        $manager->persist($course2);
        $manager->persist($course3);
        $manager->persist($course4);
        $manager->persist($course5);
        $manager->persist($course6);

        // Create Lessons

        $lesson1 = new Lessons();
        $lesson1->setName('Qu’est-ce qu’un site web ?');
        $lesson1->setContent('Un site web est un ensemble de pages accessibles via Internet.
Ces pages sont affichées dans un navigateur (comme Chrome ou Firefox).

🔹 Exemple concret

Quand tu vas sur un site :

tu entres une adresse (URL)
une page s’affiche avec du texte, des images, des boutons
🔹 De quoi est composé un site ?

Un site web repose sur 3 technologies principales :

HTML → structure du contenu
CSS → apparence (couleurs, mise en page)
JavaScript → interactions (clics, animations)
🔹 Types de sites
Site vitrine (présentation)
Blog
Application web (ex : réseaux sociaux)
🎯 À retenir

Un site web est une interface visible par l’utilisateur, construite avec plusieurs technologies.');
        $lesson1->setPrice('26.00');
        $lesson1->setVideoUrl('');
        $lesson1->setCreatedBy('admin');
        $lesson1->setUpdatedBy('admin');
        $lesson1->setCreatedAt(new \DateTimeImmutable());
        $lesson1->setUpdatedAt(new \DateTimeImmutable());
        $lesson1->setCourse($course1);
        
        $lesson2 = new Lessons();
        $lesson2->setName('Comment fonctionne Internet ?');
        $lesson2->setContent('Internet est un réseau mondial qui permet aux ordinateurs de communiquer entre eux.

🔹 Les acteurs principaux
1. Le navigateur (client)

C’est ce que tu utilises pour accéder aux sites :

Chrome
Firefox
Edge

👉 Il envoie une demande pour afficher une page.

2. Le serveur

C’est un ordinateur qui contient les sites web.

👉 Il reçoit la demande et renvoie les fichiers (HTML, CSS, JS).

🔹 Le fonctionnement
Tu tapes une URL (ex : google.com)
Le navigateur envoie une requête au serveur
Le serveur répond avec les fichiers
Le navigateur affiche la page
🔹 Notion importante : HTTP

C’est le protocole utilisé pour communiquer entre client et serveur.

🎯 À retenir

Un site web fonctionne grâce à une communication entre ton navigateur et un serveur.');
        $lesson2->setPrice('26.00');
        $lesson2->setVideoUrl('');
        $lesson2->setCreatedBy('admin');
        $lesson2->setUpdatedBy('admin');
        $lesson2->setCreatedAt(new \DateTimeImmutable());
        $lesson2->setUpdatedAt(new \DateTimeImmutable());
        $lesson2->setCourse($course1);

        $lesson3 = new Lessons();
        $lesson3->setName('Structure d’une page HTML');
        $lesson3->setContent('Le HTML (HyperText Markup Language) est le langage utilisé pour structurer une page web.

🔹 Structure de base

Une page HTML suit toujours une structure standard :

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mon site</title>
</head>
<body>
    <h1>Bienvenue</h1>
    <p>Ceci est ma première page web.</p>
</body>
</html>
🔹 Explication
<!DOCTYPE html> : indique que c’est un document HTML5
<html> : contient toute la page
<head> : informations invisibles (titre, encodage…)
<body> : contenu visible
🎯 À retenir

Une page HTML est composée de balises imbriquées qui définissent sa structure.');
        $lesson3->setPrice('26.00');
        $lesson3->setVideoUrl('');
        $lesson3->setCreatedBy('admin');
        $lesson3->setUpdatedBy('admin');
        $lesson3->setCreatedAt(new \DateTimeImmutable());
        $lesson3->setUpdatedAt(new \DateTimeImmutable());
        $lesson3->setCourse($course2);

        $lesson4 = new Lessons();
        $lesson4->setName('Les balises principales');
        $lesson4->setContent('Les balises HTML servent à structurer le contenu.

🔹 Les titres
<h1>Titre principal</h1>
<h2>Sous-titre</h2>

👉 Il existe 6 niveaux (h1 à h6)

🔹 Les paragraphes
<p>Ceci est un paragraphe.</p>
🔹 Les liens
<a href="https://example.com">Aller sur le site</a>
🔹 Les images
<img src="image.jpg" alt="Description de l\'image">
🎯 À retenir

Chaque type de contenu (texte, image, lien) possède sa propre balise.');
        $lesson4->setPrice('26.00');
        $lesson4->setVideoUrl('');
        $lesson4->setCreatedBy('admin');
        $lesson4->setUpdatedBy('admin');
        $lesson4->setCreatedAt(new \DateTimeImmutable());
        $lesson4->setUpdatedAt(new \DateTimeImmutable());
        $lesson4->setCourse($course2);

        $lesson5 = new Lessons();
        $lesson5->setName('Listes et tableaux');
        $lesson5->setContent('Organiser l’information est essentiel pour rendre une page lisible.

🔹 Les listes
Liste non ordonnée (avec des puces)
<ul>
    <li>Pomme</li>
    <li>Banane</li>
    <li>Orange</li>
</ul>
Liste ordonnée (numérotée)
<ol>
    <li>Étape 1</li>
    <li>Étape 2</li>
    <li>Étape 3</li>
</ol>
🔹 Les tableaux

Les tableaux servent à afficher des données structurées.

<table>
    <tr>
        <th>Nom</th>
        <th>Âge</th>
    </tr>
    <tr>
        <td>Alice</td>
        <td>25</td>
    </tr>
</table>
🎯 À retenir
<ul> et <ol> permettent de créer des listes
<table> permet d’organiser des données en lignes et colonnes');
        $lesson5->setPrice('32.00');
        $lesson5->setVideoUrl('Videos/Video_lecon_langage_html_css.mp4');
        $lesson5->setCreatedBy('admin');
        $lesson5->setUpdatedBy('admin');
        $lesson5->setCreatedAt(new \DateTimeImmutable());
        $lesson5->setUpdatedAt(new \DateTimeImmutable());
        $lesson5->setCourse($course3);

        $lesson6 = new Lessons();
        $lesson6->setName('Formulaires (input, button)');
        $lesson6->setContent('Les formulaires permettent à l’utilisateur d’envoyer des informations.

🔹 Structure de base
<form>
    <label>Nom :</label>
    <input type="text">

    <button type="submit">Envoyer</button>
</form>
🔹 Types de champs
<input type="text" placeholder="Votre nom">
<input type="email">
<input type="password">
<input type="checkbox">
🔹 Bonnes pratiques
Toujours utiliser <label>
Ajouter des placeholder
Grouper les champs logiquement
🎯 À retenir

Les formulaires sont essentiels pour interagir avec l’utilisateur (inscription, contact…).');
        $lesson6->setPrice('32.00');
        $lesson6->setVideoUrl('');
        $lesson6->setCreatedBy('admin');
        $lesson6->setUpdatedBy('admin');
        $lesson6->setCreatedAt(new \DateTimeImmutable());
        $lesson6->setUpdatedAt(new \DateTimeImmutable());
        $lesson6->setCourse($course3);

        $lesson7 = new Lessons();
        $lesson7->setName('Ajouter du CSS');
        $lesson7->setContent('Le CSS (Cascading Style Sheets) permet de styliser une page HTML (couleurs, tailles, mise en page…).

🔹 3 façons d’ajouter du CSS
1. Inline (dans une balise HTML)
<p style="color: red;">Texte rouge</p>

👉 Simple mais déconseillé (difficile à maintenir)

2. Dans le <head> (style interne)
<style>
p {
    color: blue;
}
</style>
3. Fichier externe (recommandé)
<link rel="stylesheet" href="style.css">

Et dans style.css :

p {
    color: green;
}
🎯 À retenir

La meilleure pratique est d’utiliser un fichier CSS externe.');
        $lesson7->setPrice('16.00');
        $lesson7->setVideoUrl('');
        $lesson7->setCreatedBy('admin');
        $lesson7->setUpdatedBy('admin');
        $lesson7->setCreatedAt(new \DateTimeImmutable());
        $lesson7->setUpdatedAt(new \DateTimeImmutable());
        $lesson7->setCourse($course4);

        $lesson8 = new Lessons();
        $lesson8->setName('Sélecteurs et propriétés');
        $lesson8->setContent('Le CSS fonctionne avec des sélecteurs et des propriétés.

🔹 Structure d’une règle CSS
sélecteur {
    propriété: valeur;
}
🔹 Exemples de sélecteurs
Par balise
p {
    color: blue;
}
Par classe
.important {
    color: red;
}

HTML :

<p class="important">Texte important</p>
Par ID
#titre {
    font-size: 30px;
}
🔹 Propriétés courantes
color → couleur du texte
background-color → couleur de fond
font-size → taille du texte
🎯 À retenir

Le CSS cible des éléments HTML pour leur appliquer un style.');
        $lesson8->setPrice('16.00');
        $lesson8->setVideoUrl('');
        $lesson8->setCreatedBy('admin');
        $lesson8->setUpdatedBy('admin');
        $lesson8->setCreatedAt(new \DateTimeImmutable());
        $lesson8->setUpdatedAt(new \DateTimeImmutable());
        $lesson8->setCourse($course4);

        $lesson9 = new Lessons();
        $lesson9->setName('Variables et types');
        $lesson9->setContent('JavaScript permet de stocker et manipuler des données grâce aux variables.

🔹 Déclarer une variable
let nom = "Alice";
const age = 25;
let → variable modifiable
const → constante (non modifiable)
🔹 Les types de données
Texte (string)
let prenom = "Jean";
Nombre (number)
let prix = 10;
Booléen (boolean)
let estConnecte = true;
🔹 Afficher une variable
console.log(nom);
🎯 À retenir

Les variables servent à stocker des informations utilisées dans le programme.');
        $lesson9->setPrice('23.00');
        $lesson9->setVideoUrl('');
        $lesson9->setCreatedBy('admin');
        $lesson9->setUpdatedBy('admin');
        $lesson9->setCreatedAt(new \DateTimeImmutable());
        $lesson9->setUpdatedAt(new \DateTimeImmutable());
        $lesson9->setCourse($course5);

        $lesson10 = new Lessons();
        $lesson10->setName('Conditions (if, else)');
        $lesson10->setContent('Les conditions permettent d’exécuter du code selon une situation.

🔹 Syntaxe
if (condition) {
    // code si vrai
} else {
    // code sinon
}
🔹 Exemple
let age = 18;

if (age >= 18) {
    console.log("Majeur");
} else {
    console.log("Mineur");
}
🔹 Opérateurs de comparaison
== → égal
=== → égal strict
!= → différent
> < >= <=
🎯 À retenir

Les conditions permettent de rendre le programme intelligent.');
        $lesson10->setPrice('23.00');
        $lesson10->setVideoUrl('');
        $lesson10->setCreatedBy('admin');
        $lesson10->setUpdatedBy('admin');
        $lesson10->setCreatedAt(new \DateTimeImmutable());
        $lesson10->setUpdatedAt(new \DateTimeImmutable());
        $lesson10->setCourse($course5);

        $lesson11 = new Lessons();
        $lesson11->setName('Manipuler le DOM');
        $lesson11->setContent('Le DOM (Document Object Model) représente la structure HTML de la page sous forme d’objets manipulables avec JavaScript.

🔹 Sélectionner un élément
const titre = document.querySelector("h1");

👉 Sélectionne le premier <h1> de la page

🔹 Autres sélecteurs
document.getElementById("monId");
document.querySelector(".maClasse");
document.querySelectorAll("p");
🔹 Modifier un élément
const titre = document.querySelector("h1");
titre.textContent = "Nouveau titre";
🔹 Modifier le HTML
titre.innerHTML = "<span>Bonjour</span>";
🎯 À retenir

Le DOM permet d’accéder et modifier les éléments HTML avec JavaScript.');
        $lesson11->setPrice('26.00');
        $lesson11->setVideoUrl('Videos/Video_mettre_en_œuvre_le_style _dans_assiette.mp4');
        $lesson11->setCreatedBy('admin');
        $lesson11->setUpdatedBy('admin');
        $lesson11->setCreatedAt(new \DateTimeImmutable());
        $lesson11->setUpdatedAt(new \DateTimeImmutable());
        $lesson11->setCourse($course6);

        $lesson12 = new Lessons();
        $lesson12->setName('Les événements (click, input)');
        $lesson12->setContent('Les événements permettent de réagir aux actions de l’utilisateur.

🔹 Ajouter un événement
const bouton = document.querySelector("button");

bouton.addEventListener("click", function() {
    console.log("Bouton cliqué !");
});
🔹 Exemple avec un input
const input = document.querySelector("input");

input.addEventListener("input", function() {
    console.log(input.value);
});
🔹 Types d’événements courants
click
input
submit
mouseover
🎯 À retenir

Les événements rendent une page interactive.');
        $lesson12->setPrice('26.00');
        $lesson12->setVideoUrl('');
        $lesson12->setCreatedBy('admin');
        $lesson12->setUpdatedBy('admin');
        $lesson12->setCreatedAt(new \DateTimeImmutable());
        $lesson12->setUpdatedAt(new \DateTimeImmutable());
        $lesson12->setCourse($course6);

        $manager->persist($lesson1);
        $manager->persist($lesson2);
        $manager->persist($lesson3);
        $manager->persist($lesson4);
        $manager->persist($lesson5);
        $manager->persist($lesson6);
        $manager->persist($lesson7);
        $manager->persist($lesson8);
        $manager->persist($lesson9);
        $manager->persist($lesson10);
        $manager->persist($lesson11);
        $manager->persist($lesson12);

        // Persist all created objects
        $manager->flush();
    }
}
