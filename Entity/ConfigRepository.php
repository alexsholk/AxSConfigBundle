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
        $configuration = $this->findAll();
        foreach ($configuration as $config) {
            /** @var Config $config */
            $this->configuration[$config->getMask()] = $config->getValue();
        }
    }

    /**
     * @param $mask
     * @return string|null
     */
    public function getValue($mask)
    {
        if ($this->configuration === null) {
            $this->loadConfiguration();
        }

        return isset($this->configuration[$mask]) ? $this->configuration[$mask] : null;
    }
}