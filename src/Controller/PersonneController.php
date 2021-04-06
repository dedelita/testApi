<?php

namespace App\Controller;

use App\Repository\PersonneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class PersonneController extends AbstractController
{
    private $persRepository;
    
    public function __construct(PersonneRepository $persRepository)
    {
        $this->persRepository = $persRepository;
    }

    /**
     * @Route("/register", name="add_user", methods="GET")
     */
    public function addPersonne(Request $request): Response
    {
        $nom = $request->get("nom");
        $prenom = $request->get("prenom");
        $dateNaiss = $request->get("dateNaissance");
        $age = $request->get("age");

        if (empty($nom) || empty($prenom) || empty($dateNaiss) || empty($age)) {
            return $this->json([
                'message' => 'Les champs ne doivent pas être vide!'
            ]);
        }

        $pers = $this->persRepository->findOneByNom($nom);
        if($pers) {
            $pers = $this->persRepository->findOneByPrenom($prenom);
            if($pers)
            $pers = $this->persRepository->findOneByDateNaissance($dateNaiss);

            if($pers) {
                return $this->json([
                    'message' => 'Personne déjà existante!'
                ]);
            } else {
                $this->persRepository->add($nom, $prenom, $dateNaiss, $age);
            }
        }
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PersonneController.php',
        ]);
    }

    /**
     * @Route("/getPersonnesAsc", name="get_personnes_asc", methods="GET")
     */
    public function getPersonnesAsc(Request $request): Response
    {
        $listPers = $this->persRepository->findAllAsc();

        
        return $this->json($listPers);
    }

}
