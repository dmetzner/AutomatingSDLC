<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\GuidType;
use Doctrine\ORM\Mapping as ORM;
use FR3D\LdapBundle\Model\LdapUserInterface;
use Sonata\UserBundle\Entity\BaseUser as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements LdapUserInterface
{
  /**
   * @ORM\Id
   * @ORM\Column(name="id", type="guid")
   * @ORM\GeneratedValue(strategy="CUSTOM")
   * @ORM\CustomIdGenerator(class="App\Utils\MyUuidGenerator")
   */
  protected $id;

  /**
   * @deprecated API v1
   *
   * @ORM\Column(type="string", length=300, nullable=true)
   */
  protected $upload_token;

  /**
   * @ORM\Column(type="text", nullable=true)
   */
  protected $avatar;

  /**
   * @ORM\Column(type="string", length=5, nullable=false, options={"default": ""})
   */
  protected $country;

  /**
   * @ORM\Column(type="string", nullable=true)
   */
  protected $additional_email;

  /**
   * @ORM\Column(type="string", nullable=true)
   */
  protected $dn;

  /**
   * Programs owned by this user.
   * When this user is deleted, all the programs owned by him should be deleted too.
   *
   * @ORM\OneToMany(
   *     targetEntity="Program",
   *     mappedBy="user",
   *     fetch="EXTRA_LAZY",
   *     cascade={"remove"}
   * )
   *
   * @var Collection|Program[]
   */
  protected $programs;

  /**
   * Notifications which are available for this user (shown upon login).
   * When this user is deleted, all notifications for him should also be deleted.
   *
   * @ORM\OneToMany(
   *     targetEntity="CatroNotification",
   *     mappedBy="user",
   *     fetch="EXTRA_LAZY",
   *     cascade={"remove"}
   * )
   *
   * @var Collection|CatroNotification[]
   */
  protected $notifications;

  /**
   * Comments written by this user.
   * When this user is deleted, all the comments he wrote should be deleted too.
   *
   * @ORM\OneToMany(
   *     targetEntity="UserComment",
   *     mappedBy="user",
   *     fetch="EXTRA_LAZY",
   *     cascade={"remove"}
   * )
   *
   * @var Collection|UserComment[]
   */
  protected $comments;

  /**
   * FollowNotifications mentioning this user as a follower.
   * When this user will be deleted, all FollowNotifications mentioning
   * him as a follower, should also be deleted.
   *
   * @ORM\OneToMany(
   *     targetEntity="App\Entity\FollowNotification",
   *     mappedBy="follower",
   *     fetch="EXTRA_LAZY",
   *     cascade={"remove"}
   * )
   *
   * @var Collection|FollowNotification[]
   */
  protected $follow_notification_mentions;

  /**
   * LikeNotifications mentioning this user as giving a like to another user.
   * When this user will be deleted, all LikeNotifications mentioning
   * him as a user giving a like to another user, should also be deleted.
   *
   * @ORM\OneToMany(
   *     targetEntity="App\Entity\LikeNotification",
   *     mappedBy="like_from",
   *     fetch="EXTRA_LAZY",
   *     cascade={"remove"}
   * )
   *
   * @var Collection|LikeNotification[]
   */
  protected $like_notification_mentions;

  /**
   * @ORM\ManyToMany(targetEntity="\App\Entity\User", mappedBy="following")
   */
  protected $followers;

  /**
   * @ORM\ManyToMany(targetEntity="\App\Entity\User", inversedBy="followers")
   */
  protected $following;

  /**
   * @ORM\OneToMany(
   *     targetEntity="\App\Entity\ProgramLike",
   *     mappedBy="user",
   *     cascade={"persist", "remove"},
   *     orphanRemoval=true
   * )
   *
   * @var Collection|ProgramLike[]
   */
  protected $likes;

  /**
   * @ORM\OneToMany(
   *     targetEntity="\App\Entity\UserLikeSimilarityRelation",
   *     mappedBy="first_user",
   *     cascade={"persist", "remove"},
   *     orphanRemoval=true
   * )
   *
   * @var Collection|UserLikeSimilarityRelation[]
   */
  protected $relations_of_similar_users_based_on_likes;

  /**
   * @ORM\OneToMany(
   *     targetEntity="\App\Entity\UserLikeSimilarityRelation",
   *     mappedBy="second_user",
   *     cascade={"persist", "remove"},
   *     orphanRemoval=true
   * )
   *
   * @var Collection|UserLikeSimilarityRelation[]
   */
  protected $reverse_relations_of_similar_users_based_on_likes;

  /**
   * @ORM\OneToMany(
   *     targetEntity="\App\Entity\UserRemixSimilarityRelation",
   *     mappedBy="first_user",
   *     cascade={"persist", "remove"},
   *     orphanRemoval=true
   * )
   *
   * @var Collection|UserRemixSimilarityRelation[]
   */
  protected $relations_of_similar_users_based_on_remixes;

  /**
   * @ORM\OneToMany(
   *     targetEntity="\App\Entity\UserRemixSimilarityRelation",
   *     mappedBy="second_user",
   *     cascade={"persist", "remove"},
   *     orphanRemoval=true
   * )
   *
   * @var Collection|UserRemixSimilarityRelation[]
   */
  protected $reverse_relations_of_similar_users_based_on_remixes;

  /**
   * @ORM\Column(type="string", length=300, nullable=true)
   */
  protected $gplus_access_token;

  /**
   * @ORM\Column(type="string", length=5000, nullable=true)
   */
  protected $gplus_id_token;

  /**
   * @ORM\Column(type="string", length=300, nullable=true)
   */
  protected $gplus_refresh_token;

  /**
   * @ORM\Column(type="boolean", options={"default": false})
   */
  protected $limited = false;

  /**
   * @ORM\OneToMany(targetEntity="App\Entity\ProgramInappropriateReport", mappedBy="reportingUser", fetch="EXTRA_LAZY")
   */
  protected $program_inappropriate_reports;

  /**
   * User constructor.
   */
  public function __construct()
  {
    parent::__construct();
    $this->programs = new ArrayCollection();
    $this->followers = new ArrayCollection();
    $this->following = new ArrayCollection();
    $this->country = '';
  }

  /**
   * @param mixed $gplus_access_token
   */
  public function setGplusAccessToken($gplus_access_token)
  {
    $this->gplus_access_token = $gplus_access_token;
  }

  /**
   * @return mixed
   */
  public function getGplusAccessToken()
  {
    return $this->gplus_access_token;
  }

  /**
   * @param mixed $gplus_id_token
   */
  public function setGplusIdToken($gplus_id_token)
  {
    $this->gplus_id_token = $gplus_id_token;
  }

  /**
   * @return mixed
   */
  public function getGplusIdToken()
  {
    return $this->gplus_id_token;
  }

  /**
   * @param mixed $gplus_refresh_token
   */
  public function setGplusRefreshToken($gplus_refresh_token)
  {
    $this->gplus_refresh_token = $gplus_refresh_token;
  }

  /**
   * @return mixed
   */
  public function getGplusRefreshToken()
  {
    return $this->gplus_refresh_token;
  }

  /**
   * Get id.
   *
   * @return GuidType
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Add programs.
   *
   * @return User
   */
  public function addProgram(Program $programs)
  {
    $this->programs[] = $programs;

    return $this;
  }

  /**
   * Remove programs.
   */
  public function removeProgram(Program $programs)
  {
    $this->programs->removeElement($programs);
  }

  /**
   * Get programs.
   *
   * @return Collection
   */
  public function getPrograms()
  {
    return $this->programs;
  }

  /**
   * @return mixed
   */
  public function getUploadToken()
  {
    return $this->upload_token;
  }

  /**
   * @param $upload_token
   */
  public function setUploadToken($upload_token)
  {
    $this->upload_token = $upload_token;
  }

  /**
   * @return string
   */
  public function getCountry()
  {
    return $this->country;
  }

  /**
   * @param $country
   *
   * @return $this
   */
  public function setCountry($country)
  {
    $this->country = $country;

    return $this;
  }

  /**
   * @param $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * @param mixed $additional_email
   */
  public function setAdditionalEmail($additional_email)
  {
    $this->additional_email = $additional_email;
  }

  /**
   * @return mixed
   */
  public function getAdditionalEmail()
  {
    return $this->additional_email;
  }

  /**
   * @return mixed
   */
  public function getAvatar()
  {
    return $this->avatar;
  }

  /**
   * @param $avatar
   *
   * @return $this
   */
  public function setAvatar($avatar)
  {
    $this->avatar = $avatar;

    return $this;
  }

  /**
   * Set Ldap Distinguished Name.
   *
   * @param string $dn
   *                   Distinguished Name
   */
  public function setDn($dn)
  {
    $this->dn = strtolower($dn);
  }

  /**
   * Get Ldap Distinguished Name.
   *
   * @return string Distinguished Name
   */
  public function getDn(): string
  {
    return null !== $this->dn ? $this->dn : '';
  }

  /**
   * @return bool
   */
  public function isLimited()
  {
    return $this->limited;
  }

  /**
   * @param $limited
   */
  public function setLimited($limited)
  {
    $this->limited = $limited;
  }

  /**
   * @return ProgramLike[]|Collection
   */
  public function getLikes()
  {
    return (null != $this->likes) ? $this->likes : new ArrayCollection();
  }

  /**
   * @param ProgramLike[]|Collection $likes
   */
  public function setLikes($likes)
  {
    $this->likes = $likes;
  }

  /**
   * Returns the Notifications which are available for this user (shown upon login).
   *
   * @return CatroNotification[]|Collection the Notifications which are available for this user (shown upon login)
   */
  public function getNotifications()
  {
    return (null != $this->notifications) ? $this->notifications : new ArrayCollection();
  }

  /**
   * Sets the Notifications which are available for this user (shown upon login).
   *
   * @param CatroNotification[]|Collection $notifications notifications which are available for this user (shown upon login)
   */
  public function setNotifications($notifications)
  {
    $this->notifications_for_this_user = $notifications;
  }

  /**
   * @return mixed
   */
  public function getFollowers()
  {
    return $this->followers;
  }

  /**
   * @param $follower
   */
  public function addFollower($follower)
  {
    $this->followers->add($follower);
  }

  /**
   * @param $follower
   */
  public function removeFollower($follower)
  {
    $this->followers->removeElement($follower);
  }

  /**
   * @param $user
   *
   * @return bool
   */
  public function hasFollower($user)
  {
    return $this->followers->contains($user);
  }

  /**
   * @return mixed
   */
  public function getFollowing()
  {
    return $this->following;
  }

  /**
   * @param $follower
   */
  public function addFollowing($follower)
  {
    $this->following->add($follower);
  }

  /**
   * @param $follower
   */
  public function removeFollowing($follower)
  {
    $this->following->removeElement($follower);
  }

  /**
   * @param $user
   *
   * @return bool
   */
  public function isFollowing($user)
  {
    return $this->following->contains($user);
  }

  /**
   * Returns the FollowNotifications mentioning this user as a follower.
   *
   * @return FollowNotification[]|Collection
   */
  public function getFollowNotificationMentions()
  {
    return $this->follow_notification_mentions;
  }

  /**
   * Sets the FollowNotifications mentioning this user as a follower.
   *
   * @param FollowNotification[]|Collection $follow_notification_mentions
   */
  public function setFollowNotificationMentions($follow_notification_mentions): void
  {
    $this->follow_notification_mentions = $follow_notification_mentions;
  }

  /**
   * @return mixed
   */
  public function getProgramInappropriateReports()
  {
    return $this->program_inappropriate_reports;
  }

  /**
   * @return mixed
   */
  public function getProgramInappropriateReportsCount()
  {
    $programs_collection = $this->getPrograms();
    $programs = $programs_collection->getValues();
    $count = 0;
    foreach ($programs as $program)
    {
      $count += $program->getReportsCount();
    }

    return $count;
  }

  /**
   * @return mixed
   */
  public function getComments()
  {
    return $this->comments;
  }

  /**
   * @return mixed
   */
  public function getReportedCommentsCount()
  {
    $comments_collection = $this->getComments();
    $criteria = Criteria::create()->andWhere(Criteria::expr()->eq('isReported', 1));

    return $comments_collection->matching($criteria)->count();
  }
}
