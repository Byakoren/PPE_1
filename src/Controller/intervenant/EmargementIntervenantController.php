<?php

namespace App\Controller\intervenant;

use App\Entity\Participer;
use App\Repository\CoursRepository;
use App\Repository\ParticiperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class EmargementIntervenantController extends AbstractController
{
    //Ajout de la route intervenant/emargement/{id} ,avec le slug correspondant a l'identifiant.
    //Contraint les méthodes utilisé à GET et POST. POST pour envoyer les données dans passé par l'URL.
    #[Route('intervenant/emargement/{id}', name: 'app_emargement_intervenant' , methods: ['GET','POST'])]
    public function signer(
        int $id,
        Request $request,
        CoursRepository $coursRepository,
        ParticiperRepository $participerRepository,
        EntityManagerInterface $em,
        Security $security,
    ): Response
    {
        //Récupère l'utilisateur connecté grace au security bundle.
        $user = $security->getUser();

        //Récupère le cours à partir de l'ID passé dans l'URL.
        $cours = $coursRepository->find($id);

        //Vérifie que l'utilisateur et le cours existent.
        if(!$user || !$cours){
            throw $this->createNotFoundException("Cours ou utilisateur introuvable.");
        }

        //Check si l'utilisateur a déja une participation pour le cours.
        //Retourne un object ou NULL.
        $participation = $participerRepository->findOneBy([
            'user' => $user,
            'cours' => $cours
        ]);

        // Si une soumission de formulaire est faite (POST)
        //Vérifie le champ signature dans la requête,si vide redirige vers la page avec un méssage.
        //Déja codé dans le app.js ligne 29.
        if ($request->isMethod('POST')){
            $signature = $request->request->get('signature');
            if(!$signature){
                $this->addFlash('error','Signature absente.');
                return $this->redirectToRoute('app_emargement_intervenant', ['id'=> $id]);
            }
        

            //Si aucune participation existante, on en crée une nouvelle.
            if(!$participation){
                $participation = new Participer();
                $participation->setUser($user);
                $participation->setCours($cours);

            }

            //Enregistre la signature.
            $participation->setSignature($signature);
            $em->persist($participation);
            $em->flush();

            $this->addFLash("success","Signature enregistre avec succès !");
            return $this->redirectToRoute("app_emargement_intervenant", ['id'=> $id]);
        }


        

        return $this->render('intervenant/emargement_intervenant/index.html.twig', [
            "cours" => $cours,
            "signature" => $participation?->getSignature()
        ]);
    }
}
