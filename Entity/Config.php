<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 15.10.15
 * Time: 15:31
 */

namespace AxS\ConfigBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ConfigRepository")
 * @ORM\Table(name="axs_config")
 */
class Config
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50, unique = true)
     */
    protected $mask;

    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $value = '';

    /**
     * @ORM\ManyToOne(targetEntity="ConfigGroup", inversedBy="configs")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     **/
    protected $group;

    public function __toString()
    {
        return $this->getTitle() ? : __CLASS__;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set mask
     *
     * @param string $mask
     *
     * @return Config
     */
    public function setMask($mask)
    {
        $this->mask = $mask;

        return $this;
    }

    /**
     * Get mask
     *
     * @return string
     */
    public function getMask()
    {
        return $this->mask;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Config
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Config
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set group
     *
     * @param \AxS\ConfigBundle\Entity\ConfigGroup $group
     *
     * @return Config
     */
    public function setGroup(\AxS\ConfigBundle\Entity\ConfigGroup $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \AxS\ConfigBundle\Entity\ConfigGroup
     */
    public function getGroup()
    {
        return $this->group;
    }
}
