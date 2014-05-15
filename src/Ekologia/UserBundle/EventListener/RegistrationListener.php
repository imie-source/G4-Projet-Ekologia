<?php
namespace Ekologia\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Ekologia\MainBundle\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Registry;

/**
 * Check interests and transform into tags
 */
class RegistrationListener implements EventSubscriberInterface
{
    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        $user = $event->getForm()->getData();
        foreach($user->getInterests() as $interest) {
            $tag = $this->doctrine->getRepository('EkologiaMainBundle:Tag')->findOneBy(array('name' => $interest));
            if ($tag === null) {
                $tag = new Tag();
                $tag->setName($interest);
            }
            $user->addTag($tag);
        }
    }
}