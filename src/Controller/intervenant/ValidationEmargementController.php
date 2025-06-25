<?php

namespace App\Controller\intervenant;
use DateTimeInterface;
use App\Repository\UserRepository;
use App\Repository\CoursRepository;
use App\Repository\CrenauxRepository;
use App\Repository\ParticiperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ValidationEmargementController extends AbstractController
{
    #[Route('/intervenant/validation/emargement/{id}', name: 'app_intervenant_validation_emargement')]
    public function validation(
        int $id,
        CoursRepository $coursRepository,
        ParticiperRepository $particierRepository,
        EntityManagerInterface $em,
        Security $secutiy,
        UserRepository $userRepository,
        Security $security,
        CrenauxRepository $crenaux_rep
    ): Response
    {
        $user = $security->getUser();
        $user_id = $user->getid();
        $cour = $coursRepository->find($id);
        $cour_id = $cour->getId();
        $id_crenaux = $cour->getCrenaux();
        $crenaux = $crenaux_rep->findOneBy(["id" => $id_crenaux]);
        $heureActuelle = new \DateTime();
        

        $heureDebut = $crenaux ? $crenaux->getHeureDebut() : null;
        $temp_retard = null;
        if ($heureDebut instanceof \DateTimeInterface) {
            $interval = $heureDebut->diff($heureActuelle);
            $temp_retard = ($heureActuelle > $heureDebut) ? ($interval->h * 60 + $interval->i) : 0;
           
           
        }

        // Récupérer toutes les participations pour ce cours
        $liste_apprenants = $particierRepository->findBy(['cours' => $cour]);
        
        // Préparer une liste structurée pour la vue
        $apprenants = [];
        foreach ($liste_apprenants as $participation) {
            
            $user = $participation->getUser();
            //Si l'apprenant a signé alors on affiche le retard en base de donnée.
            // L'apprenant n'a pas signé le retard envoyé a la vue est celui calculé.
            if ($participation->getSignature() === null) {
                $retard = $temp_retard;
            } else {
                $retard = $participation->getRetard();
            }
            
            //calcule de l'heure de signature.
            $heure_de_signature = null;
            if ($heureDebut instanceof \DateTime && $retard !== null) {
                $heure_de_signature = (clone $heureDebut)->modify("+{$retard} minutes");
            }

            $apprenants[] = [
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'signature' => $participation->getSignature() ? 'Signé' : 'Non signé',
            'validation' => $participation->isValidation() ? 'Validé' : 'Non validé',
            'retard' => $retard,
            'id' => $participation->getId(),
            'heureSignature' => $heure_de_signature,
            'role' => $user->getRoles()
            ];
            //Pour ajouter des commentaires pars validation et par apprenants.
            //'commentaire' => $participation->getCommentaire()];
            
            
        }
        //dump($apprenants);
        $liste_apprenants = $apprenants;
        
        return $this->render('intervenant/validation_emargement/index.html.twig', [
            'controller_name' => 'Intervenant/ValidationEmargementController',
            'liste_apprenants' => $liste_apprenants,
            'cours' => $cour,
            'matiere' => $cour->getMatiere(),
            'heureDebut' => $cour->getCrenaux()->getHeureDebut(),
            'heureFin' => $cour->getCrenaux()->getHeureFin(),
            'dateCours' =>  $cour->getCrenaux()->getDate()
        ]);
    }

    //Création d'une route pour la validation des signatures
    #[Route("/intervenant/validation/emargement/valider/{id}", name: "valider_signature", methods: ["POST"])]
    public function validerSignature(
        int $id,
        ParticiperRepository $participerRepository,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        //Attribut la participation via un find avec l'id de la participation
        $participation = $participerRepository->find($id);

        //Si aucune vérification n'est retourné par le repository alors lève une érreur que l'on intercepte
        if(!$participation){
            throw $this->createNotFoundException("Participation non trouvé");
        }

        //Vérifie le jeteon de session avec la méthode isCsrfTokenValid, si jeton ok set la validation "true" et flush
        //Transmet un message flash(optionnel, débugage)
        if ($this->isCsrfTokenValid('valider_signature' . $id, $request->request->get("_token"))) {
            $participation->setValidation(true);
            $em->flush();
            //$this->addFlash("success", "signature validée!");

        }

        return $this->redirectToRoute("app_intervenant_validation_emargement",[
            "id" => $participation->getCours()->getId()
        ]);

    }

}