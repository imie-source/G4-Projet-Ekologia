<?php

namespace Ekologia\UserBundle\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Ekologia\UserBundle\Entity\GroupRepository")
 * @ORM\Table(name="eko_group")
 */
class Group extends BaseGroup {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Ekologia\UserBundle\Entity\UserGroup", cascade={"persist", "remove"}, mappedBy="group")
     */
    private $userGroups;

    /**
     * @var PUser
     * @ORM\ManyToOne(targetEntity="Ekologia\UserBundle\Entity\PUser")
     */
    private $puser;

    /**
     * @ORM\ManyToOne(targetEntity="Ekologia\UserBundle\Entity\User")
     */
    private $administrator;


    public function __construct($name = null, $roles = array()) {
        parent::__construct($name, $roles);
        $this->userGroups = new ArrayCollection();
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Group
     */
    public function setDescription($description = null) {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return User
     */
    public function getDescription() {
        return $this->description;
    }

    public function addUserGroup(UserGroup $userGroup) {
        $this->userGroups[] = $userGroup;
        return $this;
    }

    public function removeUserGroup(UserGroup $userGroup) {
        $this->userGroups->removeElement($userGroup);
        return $this;
    }

    public function getUserGroups() {
        return $this->userGroups;
    }

    public function getUserGroupsSort($sort = 'username') {
        $userGroups = $this->getUserGroups()->toArray();
        switch ($sort) {
            case 'username':
                usort($userGroups, function (UserGroup $ug1, UserGroup $ug2) {
                    return substr_compare($ug1->getUser()->getUsername(), $ug2->getUser()->getUsername(), 0);
                });
                break;
            case 'notactive':
                usort($userGroups, function (UserGroup $ug1, UserGroup $ug2) {
                    $ugActive1 = $ug1->getActive();
                    $ugActive2 = $ug2->getActive();
                    return $ugActive1 === $ugActive2 ? 0 : $ugActive1 < $ugActive2 ? -1 : 1;
                });
                break;
            case 'notactive-username':
                usort($userGroups, function (UserGroup $ug1, UserGroup $ug2) {
                    $ugActive1 = $ug1->getActive();
                    $ugActive2 = $ug2->getActive();
                    return $ugActive1 === $ugActive2
                        ? substr_compare($ug1->getUser()->getUsername(), $ug2->getUser()->getUsername(), 0)
                        : $ugActive1 < $ugActive2 ? -1 : 1;
                });
                break;
        }
        return $userGroups;

    }

    public function getUsers() {
        /* @var $users User[] */
        /* @var $userGroup UserGroup */
        $users = new ArrayCollection();
        foreach ($this->getUserGroups() as $userGroup) {
            $users[] = $userGroup->getUser();
        }
        return $users;
    }

    public function getUserCompose() {
        /* @var $users User[] */
        /* @var $userGroup UserGroup */
        $users = array();
        foreach ($this->getUserGroups() as $userGroup) {
            if ($userGroup->getCompose()) {
                $users[] = $userGroup->getUser();
            }
        }
        return $users;
    }

    public function getUserParticipate() {
        /* @var $users User[] */
        /* @var $userGroup UserGroup */
        $users = array();
        foreach ($this->getUserGroups() as $userGroup) {
            if (!$userGroup->getCompose()) {
                $users[] = $userGroup->getUser();
            }
        }
        return $users;
    }

    /**
     * Set puser
     *
     * @param PUser $puser
     * @return Group
     */
    public function setPuser(PUser $puser = null) {
        $this->puser = $puser;
        return $this;
    }

    /**
     * Get puser
     *
     * @return PUser
     */
    public function getPuser() {
        return $this->puser;
    }

    /**
     * Set coordinator
     *
     * @param User $coordinator
     * @return Group
     */
    public function setCoordinator(User $coordinator = null) {
        if ($coordinator->getUserType() === 'puser') {
            $this->setPuser($coordinator->getPuser());
        } else {
            throw new \InvalidArgumentException("Coordinator is not a PUser");
        }
        return $this;
    }

    /**
     * Get coordinator
     *
     * @return User
     */
    public function getCoordinator() {
        return $this->puser->getUser();
    }

    /**
     * Set administrator
     *
     * @param User $administrator
     * @return Group
     */
    public function setAdministrator(User $administrator = null) {
        $this->administrator = $administrator;
        return $this;
    }

    /**
     * Get administrator
     *
     * @return User
     */
    public function getAdministrator() {
        return $this->administrator;
    }

    /**
     * @param User $user
     * @param boolean $mustActive
     * @return boolean
     */
    public function isUserInclude(User $user = null, $mustActive = false) {
        /* @var $userGroup UserGroup */
        if ($user !== null) {
            foreach ($this->userGroups as $userGroup) {
                if ($userGroup->getUser()->getId() === $user->getId()) {
                    if (!$mustActive || $userGroup->getActive()) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
