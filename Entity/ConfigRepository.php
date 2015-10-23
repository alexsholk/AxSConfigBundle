<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 15.10.15
 * Time: 15:37
 */

namespace AxS\ConfigBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ConfigRepository extends EntityRepository
{
    protected $configuration;

    protected function loadConfiguration()
    {
        foreach ($this->findAll() as $config) {
            $this->configuration[$config->getMask()] = $config;
        }
    }

    /**
     * @param $mask
     * @return string|null
     */
    public function get($mask)
    {
        if ($this->configuration === null) {
            $this->loadConfiguration();
        }

        return isset($this->configuration[$mask]) ? $this->configuration[$mask]->getNormalizedValue() : null;
    }

    /**
     * @param $mask
     * @return string|null
     */
    public function getRaw($mask)
    {
        if ($this->configuration === null) {
            $this->loadConfiguration();
        }

        return isset($this->configuration[$mask]) ? $this->configuration[$mask]->getValue() : null;
    }
}