<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use \PDO;

final class TP1Ex1Q1Controller extends AbstractController
{
    #[Route('/', name: 'app_t_p1_ex1_q1')]
    public function index(): Response
    {
        return $this->render('tp1_ex1_q1/index.html.twig', [
            'controller_name' => 'TP1Ex1Q1Controller',
            'date' => $this->getCurrentDateTime(),
        ]);
    }
    #[Route('/bonjour', name: 'app_t_p1_ex1_q1_bonjour')]
    public function bonjour(Request $request): Response
    {
        $login = $request->request->get('login', '');
        $prenom = $request->request->get('prenom', '');

        return $this->render('tp1_ex1_q1/bonjour.html.twig', [
            'date' => $this->getCurrentDateTime(), 
            'login' => $login,
            'prenom' => $prenom,
        ]);
    }
    #[Route('/but', name: 'app_t_p1_ex1_q1_but')]
    public function but(): Response
    {
        return $this->render('tp1_ex1_q1/but.html.twig', [
            'date' => $this->getCurrentDateTime(),
        ]);
    }
    #[Route('/jquery', name: 'app_t_p1_ex1_q1_jquery')]
    public function jquery(): Response
    {
        return $this->render('tp1_ex1_q1/jquery.html.twig', [
            'date' => $this->getCurrentDateTime(),
        ]);
    }

    // Question 4-1 & 4-2 : Formulaire de connexion et authentification
    #[Route('/login', name: 'app_t_p1_ex1_q1_login')]
    public function login(Request $request): Response
    {
        $message = "";
        
        if ($request->isMethod('POST')) {
            $login = $request->request->get('login');
            $pass = $request->request->get('password');

            // Validation basique des entrées
            if (empty($login) || empty($pass)) {
                $message = "Veuillez remplir tous les champs.";
                return $this->render('tp1_ex1_q1/login.html.twig', ['message' => $message]);
            }

            // Connexion via PDO
            $dsn = 'mysql:host=localhost;dbname=r2.09';
            try {
                $pdo = new PDO($dsn, 'root', '');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                $message = "Erreur de connexion à la base de données.";
                return $this->render('tp1_ex1_q1/login.html.twig', ['message' => $message]);
            }

            // REQUÊTE SÉCURISÉE (préparation) - Q4-2
            $stmt = $pdo->prepare("SELECT * FROM informations_connexions WHERE login = :login AND motde_passe = :pass");
            $stmt->execute(['login' => $login, 'pass' => $pass]);
            $user = $stmt->fetch();

            if ($user) {
                // Q4-3 : Gestion des sessions - Stocker les informations en session
                $request->getSession()->set('user_id', $user['id'] ?? 1);
                $request->getSession()->set('user_login', $user['login']);
                $request->getSession()->set('authenticated', true);
                
                return $this->redirect($this->generateUrl('app_t_p1_ex1_q1_profile'));
            }
            else {
                $message = "Identifiants incorrects.";
            }
        }

        return $this->render('tp1_ex1_q1/login.html.twig', ['message' => $message]);
    }

    // Question 4-3 : Page profil utilisateur avec session
    #[Route('/profile', name: 'app_t_p1_ex1_q1_profile')]
    public function profile(Request $request): Response
    {
        // Vérification de la session
        if (!$request->getSession()->get('authenticated')) {
            $message = "Veuillez vous connecter d'abord.";
            return $this->render('tp1_ex1_q1/login.html.twig', ['message' => $message]);
        }

        $userName = $request->getSession()->get('user_login');
        
        return $this->render('tp1_ex1_q1/profile.html.twig', [
            'user_login' => $userName,
            'date' => $this->getCurrentDateTime(),
        ]);
    }

    // Question 4-3 : Déconnexion et destruction de session
    #[Route('/logout', name: 'app_t_p1_ex1_q1_logout')]
    public function logout(Request $request): Response
    {
        // Destruction de la session
        $request->getSession()->invalidate();
        
        return $this->redirect($this->generateUrl('app_t_p1_ex1_q1_login'));
    }

    #[Route('/ex4/enregistrer', name: 'blog_save')]
    public function save(Request $request)
    {
        if ($request->isMethod('POST')) {
            $pdo = new PDO('mysql:host=localhost;dbname=r2.09', 'root', '');
            
            $sql = "INSERT INTO message_blog (pseudo, message) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $request->request->get('pseudo'),
                $request->request->get('message')
            ]);
        }
        return $this->render('tp1_ex1_q1/ex4/save.html.twig');
    }

    // Page d'affichage (vulnérable)
    #[Route('/ex4/voir', name: 'blog_view')]
    public function view()
    {
        $pdo = new PDO('mysql:host=localhost;dbname=r2.09', 'root', '');
        $messages = $pdo->query("SELECT * FROM message_blog")->fetchAll(PDO::FETCH_ASSOC);

        return $this->render('tp1_ex1_q1/ex4/view.html.twig', ['messages' => $messages]);
    }


    private function getCurrentDateTime(): string
    {
        $timeZoneName = $_ENV['APP_TIMEZONE'] ?? 'Europe/Paris';

        return (new \DateTimeImmutable('now', new \DateTimeZone($timeZoneName)))->format('d/m/Y H:i:s');
    }
}