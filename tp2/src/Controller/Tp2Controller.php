<?php

namespace App\Controller;

use App\Entity\Formation; // Import requis
use App\Entity\Iut; // Import requis
use App\Entity\Universite; // Import requis
use App\Repository\FormationRepository; // Import requis
use Doctrine\ORM\EntityManagerInterface; // Import requis
use Symfony\Component\String\Slugger\SluggerInterface; // Requis pour sécuriser les noms de fichiers
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class Tp2Controller extends AbstractController
{
    #[Route('/', name: 'app_tp2')]
    public function index(): Response
    {
        return $this->render('tp2/index.html.twig', [
            'controller_name' => 'Tp2Controller',
            'message' => null,
        ]);
    }

    #[Route('/formation', name: 'app_tp2_formation')]
    public function formation(): Response
    {
        return $this->render('tp2/forma.html.twig', [
            'controller_name' => 'Tp2Controller',
            'message' => null,
        ]);
    }

    #[Route('/univ', name: 'app_tp2_univ')]
    public function univ(): Response
    {
        return $this->render('tp2/univ.html.twig', [
            'controller_name' => 'Tp2Controller',
            'message' => null,
        ]);
    }

    #[Route('/iut', name: 'app_tp2_iut')]
    public function iut(EntityManagerInterface $entityManager): Response
    {
        $universites = $entityManager->getRepository(Universite::class)->findAll();

        
        return $this->render('tp2/iut.html.twig', [
            'controller_name' => 'Tp2Controller',
            'universites' => $universites,
            'message' => null,
        ]);
    }

    #[Route('/iutforma', name: 'app_tp2_iutforma')]
    public function iutforma(EntityManagerInterface $entityManager): Response
    {
        $iuts = $entityManager->getRepository(Iut::class)->findAll();
        $formations = $entityManager->getRepository(Formation::class)->findAll();

        return $this->render('tp2/iutforma.html.twig', [
            'controller_name' => 'Tp2Controller',
            'iuts' => $iuts,
            'formations' => $formations,
            'message' => null,
        ]);
    }

    #[Route('/iutforma/create', name: 'app_tp2_iutforma_create')]
    public function iutforma_create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $message = null;

        // Requêtes exécutées une seule fois pour peupler le template (GET et POST)
        $iuts = $entityManager->getRepository(Iut::class)->findAll();
        $formations = $entityManager->getRepository(Formation::class)->findAll();

        if ($request->isMethod('POST')) {
            $iutId = $request->request->get('iut_id');
            $formationId = $request->request->get('formation_id');
            
            $iut = $entityManager->getRepository(Iut::class)->find($iutId);
            $formation = $entityManager->getRepository(Formation::class)->find($formationId);

            if ($iut && $formation) {
                // Vérification du doublon : On vérifie si la formation est déjà dans la collection de l'IUT
                // Attention : j'utilise getFormation() au singulier comme défini dans ton entité actuelle
                if ($iut->getFormation()->contains($formation)) {
                    $message = "Attention : Cette association existe déjà entre cet IUT et cette formation.";
                } else {
                    // La relation n'existe pas, on l'ajoute
                    $iut->addFormation($formation); 
                    $entityManager->persist($iut);
                    $entityManager->flush();
                    
                    $message = "Association créée avec succès ! IUT ID : " . $iut->getId() . " - Formation ID : " . $formation->getId();
                }
            } else {
                $message = "Erreur : IUT ou Formation introuvable.";
            }
        }

        // Un seul render pour gérer l'affichage standard et le retour post-soumission
        return $this->render('tp2/iutforma.html.twig', [
            'controller_name' => 'Tp2Controller',
            'message' => $message,
            'iuts' => $iuts,
            'formations' => $formations,
        ]);
    }


    #[Route('/formation/create', name: 'app_tp2_formation_create')]
    public function formation_create(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger): Response
    {
        $message = null;

        if ($request->isMethod('POST')) {
            $sigle = $request->request->get('sigle');
            $intitule = $request->request->get('intitule');
            
            // Récupération du FICHIER (et non du texte)
            $logoFile = $request->files->get('logoFile');


            $existing = $entityManager->getRepository(Formation::class)->findOneBy(['sigle' => $sigle]);
            if ($existing) {
                $message = "Erreur : Une formation avec ce sigle existe déjà.";
                return $this->render('tp2/forma.html.twig', [
                    'controller_name' => 'Tp2Controller',
                    'message' => $message,
                ]);
            }

            $formation = new Formation();
            $formation->setSigle($sigle);
            $formation->setIntitule($intitule);

            // Traitement de l'upload
            if ($logoFile) {
                // 1. Générer un nom sécurisé pour le fichier
                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $logoFile->guessExtension();

                // 2. Créer le chemin du dossier cible : /public/uploads/<sigle>
                $dossierSigle = $slugger->slug(strtolower($sigle));
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $dossierSigle;

                // 3. Déplacer le fichier physiquement sur le serveur
                try {
                    $logoFile->move(
                        $destination,
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Si le dossier n'a pas les droits d'écriture, ça plantera ici
                    return new Response("Erreur lors de l'upload du fichier.", 500);
                }

                // 4. Enregistrer le chemin d'accès relatif dans la base de données
                $formation->setLogoPath('/uploads/' . $dossierSigle . '/' . $newFilename);
            } else {
                // Valeur par défaut si aucun fichier (si le logo n'était pas 'required')
                $formation->setLogoPath('/uploads/default.png');
            }

            $entityManager->persist($formation);
            $entityManager->flush();
            
            $message = "Formation créée avec succès ! ID : " . $formation->getId();
        }

        return $this->render('tp2/forma.html.twig', [
            'controller_name' => 'Tp2Controller',
            'message' => $message,
        ]);
    }

    #[Route('/iut/create', name: 'app_tp2_iut_create')]
    public function create(EntityManagerInterface $entityManager, Request $request, \Symfony\Component\String\Slugger\SluggerInterface $slugger): Response
    {
        $message = null;
        
        $universites = $entityManager->getRepository(Universite::class)->findAll();

        if ($request->isMethod('POST')) {
            $ville = $request->request->get('ville');
            $universiteId = $request->request->get('universite_id');
            
            $existing = $entityManager->getRepository(Iut::class)->findOneBy(['ville' => $ville]);
            if ($existing) {
                $message = "Erreur : Un IUT avec cette ville existe déjà.";
                return $this->render('tp2/iut.html.twig', [
                    'controller_name' => 'Tp2Controller',
                    'message' => $message,
                    'universites' => $universites,
                ]);
            }

            // 1. Récupération du fichier binaire
            $logoFile = $request->files->get('logoFile');

            $universite = $entityManager->getRepository(Universite::class)->find($universiteId);

            if ($universite) {
                $iut = new Iut(); 
                $iut->setVille($ville);
                $iut->setUniversite($universite);


                // 2. Traitement de l'upload
                if ($logoFile) {
                    $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $logoFile->guessExtension();

                    // Création d'un dossier dédié au nom de la ville (ex: /uploads/iut_roanne)
                    $dossierVille = $slugger->slug(strtolower($ville));
                    $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/iut_' . $dossierVille;

                    try {
                        $logoFile->move($destination, $newFilename);
                    } catch (\Symfony\Component\HttpFoundation\File\Exception\FileException $e) {
                        return new Response("Erreur d'écriture sur le serveur (Vérifie les droits du dossier public/uploads).", 500);
                    }

                    // 3. Sauvegarde du chemin relatif en base
                    $iut->setLogoPath('/uploads/iut_' . $dossierVille . '/' . $newFilename);
                } else {
                    $iut->setLogoPath('/uploads/default.png');
                }

                $entityManager->persist($iut);
                $entityManager->flush();
                
                $message = "IUT créé avec succès ! ID : " . $iut->getId();
            } else {
                $message = "Erreur : Université introuvable.";
            }
        }

        return $this->render('tp2/iut.html.twig', [
            'controller_name' => 'Tp2Controller',
            'message' => $message,
            'universites' => $universites,
        ]);
    }
   

    #[Route('/univ/create', name: 'app_tp2_univ_create')]
    public function univ_create(EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');

            // Vérifier si une université avec le même nom existe déjà
            $existing = $entityManager->getRepository(Universite::class)->findOneBy(['nom' => $nom]);
            if ($existing) {
                $message = "Erreur : Une université avec ce nom existe déjà.";
                return $this->render('tp2/univ.html.twig', [
                    'controller_name' => 'Tp2Controller',
                    'message' => $message,
                ]);
            }


            $universite = new Universite();
            $universite->setNom($nom);

            $entityManager->persist($universite);
            $entityManager->flush();
            $message = "Université créée avec succès ! ID : " . $universite->getId();
            return $this->render('tp2/univ.html.twig', [
                'controller_name' => 'Tp2Controller',
                'message' => $message,
            ]);
        }
        return $this->render('tp2/univ.html.twig', [
            'controller_name' => 'Tp2Controller',
            'message' => null,
        ]);
    }



    #[Route('/aff', name: 'app_tp2_iut_show')]
    public function iut_show(EntityManagerInterface $entityManager, Request $request): Response
    {
        // 1. Récupérer tous les IUTs pour remplir le menu déroulant
        $iuts = $entityManager->getRepository(Iut::class)->findAll();
        $selectedIut = null;

        // 2. Si le formulaire est soumis, on récupère l'IUT ciblé
        if ($request->isMethod('POST')) {
            $iutId = $request->request->get('iut_id');
            $selectedIut = $entityManager->getRepository(Iut::class)->find($iutId);
        }

        return $this->render('tp2/iutaff.html.twig', [
            'controller_name' => 'Tp2Controller',
            'iuts' => $iuts,
            'selectedIut' => $selectedIut,
        ]);
    }

}