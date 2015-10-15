<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 15.10.15
 * Time: 15:31
 */

namespace AxS\ConfigBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="ConfigGroupRepository")
 * @ORM\Table(name="axs_config_group")
 */
class ConfigGroup
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $title;

    /**
     * @ORM\OneToMany(targetEntity="Config", mappedBy="group")
     **/
    protected $configs;

    public function __construct()
    {
        $this->configs = new ArrayCollection();
    }

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
     * Set title
     *
     * @param string $title
     *
     * @return ConfigGroup
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
     * Add config
     *
     * @param \AxS\ConfigBundle\Entity\Config $config
     *
     * @return ConfigGroup
     */
    public function addConfig(\AxS\ConfigBundle\Entity\Config $config)
    {
        $this->configs[] = $config;

        return $this;
    }

    /**
     * Remove config
     *
     * @param \AxS\ConfigBundle\Entity\Config $config
     */
    public function removeConfig(\AxS\ConfigBundle\Entity\Config $config)
    {
        $this->configs->removeElement($config);
    }

    /**
     * Get configs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConfigs()
    {
        return $this->configs;
    }
}
