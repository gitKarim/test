<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Comment;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity()
 * @UniqueEntity("email" , message="cet email est deja utilisé")
 */
class User implements UserInterface
{



    /**
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *           */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $nom;

    /**
     * @ORM\Column(type="string")
     */
    private $prenom;
    /**
     * @ORM\Column(type="string")
     *
     */
    private $email;
    /**
     * @ORM\Column(type="string")
     */
    private $password;

    private $encoder ;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user")
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();

    }


    /**
     *
     */
    public function getId() :int
    {
        return $this->id;
    }

    /**
     *
     */
    public function getNom() :?string
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom() :?string
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this ;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this ;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     *
     * @param $password
     * @return User
     */
    public function setPassword($password)
    {

        $this->password = $password;

        return $this ;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|Comment[]
     */
    public function getComments(): \Doctrine\Common\Collections\Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }


    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}