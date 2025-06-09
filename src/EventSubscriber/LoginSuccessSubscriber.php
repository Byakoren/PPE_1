<?php
namespace App\EventSubscriber;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;



class LoginSuccessSubscriber implements EventSubscriberInterface
{
    private $urlGenerator;
    private $security; //ajout du service security

    public function __construct(UrlGeneratorInterface $urlGenerator, Security $security)
    {
    $this->urlGenerator = $urlGenerator;
    $this->security = $security; //injection du service Security
    }
    /**
     * @return array<string, string>
    */


    public static function getSubscribedEvents(): array{
        //On s'abonne à lévenemnt LoginSuccesEvent
        //Lorsque une application à réussie 'onLoginSuccess'
        //sera appelé.
        return[LoginSuccessEvent::class => 'onLoginSuccess',
    ];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $request = $event->getRequest();

        // Ne rien faire pour l'API
        if (str_starts_with($request->getPathInfo(), "/api")) {
            return;
        }

        //Récupère l'utilisateur qui vient de se connecter
        $user = $event->getUser();

        //On peut aussi utiliser le service Security pour vérifier les rôles ,
        //c'est parfois plus clair
        //$is_admin = $this->security->isGranted("admin_planning");
        //$is_intervenant = $this->security->isGranted("intervenant_planning");
        //is_apprenant = $this->security->isGranted("app_admin_planning");

        $roles = $user->getRoles();
        $redirectPath = null;

        if (in_array('ROLE_ADMIN',$roles)){
                //TODO Changer le app_login dans le redirect Path du ROLE_ADMIN
                $redirectPath = $this->urlGenerator->generate("app_admin_dashboard"); // TODO: vérifier la route cible
            
            } elseif(in_array('ROLE_APPRENANT', $roles) || in_array('ROLE_INTERVENANT', $roles)){
                $redirectPath = $this->urlGenerator->generate("app_planning_apprenant");
                
            } else{
                //Aucun role trouvé rediriger vers la page de login.
                $redirectPath= $this->urlGenerator->generate("app_login");
            }
            $response = new RedirectResponse($redirectPath);

            $event->setResponse($response);
        
    }
}

