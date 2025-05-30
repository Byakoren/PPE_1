<?php 

namespace App\Controller\apprenant;

use App\Entity\Participer;
use App\Repository\CoursRepository;
use App\Repository\ParticiperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

final class EmargementApprenantController extends AbstractController
{
    #[Route('/emargement/{id}', name: 'app_emargement', methods: ['GET', 'POST'])]
    public function signer(
        int $id,
        Request $request,
        CoursRepository $coursRepository,
        ParticiperRepository $participerRepository,
        EntityManagerInterface $em,
        Security $security
    ): Response {
        // Récupère l'utilisateur connecté
        $user = $security->getUser();

        // Récupère le cours à partir de l'ID passé dans l'URL
        $cours = $coursRepository->find($id);

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

            // Enregistre la signature
            $participation->setSignature($signature);
            $em->persist($participation);
            $em->flush();

            $this->addFlash('success', 'Signature enregistrée avec succès !');
            return $this->redirectToRoute('app_emargement', ['id' => $id]);
        }

        // Affiche la page avec infos du cours et signature si existante
        return $this->render('apprenant/emargement.html.twig', [
            'cours' => $cours,
            'signature' => $participation?->getSignature()
        ]);
    }
}
