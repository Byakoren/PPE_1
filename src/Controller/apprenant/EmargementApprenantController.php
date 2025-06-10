<?php 

namespace App\Controller\apprenant;

use DateTime;
use App\Entity\Participer;
use App\Repository\CoursRepository;
use App\Repository\CrenauxRepository;
use App\Repository\ParticiperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class EmargementApprenantController extends AbstractController
{
    #[Route('/emargement/{id}', name: 'app_emargement', methods: ['GET', 'POST'])]
    public function signer(
        int $id,
        Request $request,
        CoursRepository $coursRepository,
        ParticiperRepository $participerRepository,
        EntityManagerInterface $em,
        Security $security,
        CrenauxRepository $crenaux_rep
    ): Response {
        // Récupère l'utilisateur connecté
        $user = $security->getUser();

        // Récupère le cours à partir de l'ID passé dans l'URL
        $cours = $coursRepository->find($id);

        //Récupération du crénaux pour calculer et enregistrer le retard
        $id_crenaux = $cours->getCrenaux();
        $crenaux = $crenaux_rep->findOneBy(["id" => $id_crenaux]);
        $heureActuelle = new DateTime();

        // Vérifie que l'utilisateur et le cours existent
        if (!$cours || !$user) {
            throw $this->createNotFoundException("Cours ou utilisateur introuvable.");
        }

        // Vérifie si l'utilisateur a déjà une participation pour ce cours
        $participation = $participerRepository->findOneBy([
            'user' => $user,
            'cours' => $cours
        ]);

        // Si une soumission de formulaire est faite (POST)
        if ($request->isMethod('POST')) {
            $signature = $request->request->get('signature');

            // Vérifie qu'une signature a bien été envoyée
            if (!$signature) {
                $this->addFlash('error', 'Signature absente.');
                return $this->redirectToRoute('app_emargement', ['id' => $id]);
            }

            // Si aucune participation existante, on en crée une nouvelle
            if (!$participation) {
                $participation = new Participer();
                $participation->setUser($user);
                $participation->setCours($cours);
            }
            //____Calcul du retard___
            //Enregistre le temp de retard.Si 0 pas de retard.
            //Vérifie si $crenaux existe.
            // Si il existe, attibut à $heureDebut la valeur de $crenaux->getHeureDebut()
            $heureDebut = $crenaux ? $crenaux->getHeureDebut() : null;
            //initilialise la variable temp_retard en NULL.
            $temp_retard = null;
            //Si la valeur heureDebut appartient bien a la class DateTimeInterface
            //calcul la différence entre $heureDebut et heureActuelle, le retard en gros.
            //Enfin la variable temp de retard est changé si l'heure actuelle est supèrieur a l'heure de début.
            //Sinon elle est égale a 0
            if ($heureDebut instanceof \DateTimeInterface) {
                $interval = $heureDebut->diff($heureActuelle);
                $temp_retard = ($heureActuelle > $heureDebut) ? ($interval->h * 60 + $interval->i) : 0;
            } else {
                $temp_retard = 0;
            }
            //Enregistre le retard
            $participation->setRetard($temp_retard);

            // Enregistre la signature
            $participation->setSignature($signature);
            $em->persist($participation);
            $em->flush();

            $this->addFlash('success', 'Signature enregistrée avec succès !');
            
            //return $this->redirectToRoute('app_emargement', ['id' => $id]);
            return $this->redirectToRoute('app_intervenant_validation_emargement', ['id' => $id]);
           
        }

        //Affiche la page avec infos du cours et signature si existante
        return $this->render('apprenant/emargement.html.twig', [
          'cours' => $cours,
          'signature' => $participation?->getSignature()
        ]);
        


    }
}
