<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 15.10.15
 * Time: 15:31
 */

namespace AxS\ConfigBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use AxS\ConfigBundle\Validator\ConfigConstraint;

/**
 * @ORM\Entity(repositoryClass="ConfigRepository")
 * @ORM\Table(name="axs_config")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("mask")
 * @ConfigConstraint
 */
class Config
{
    const
        TYPE_TEXT = 0,
        TYPE_TEXTAREA = 1,
        TYPE_WYSIWYG = 2,
        TYPE_INTEGER = 3,
        TYPE_FLOAT = 4,
        TYPE_BOOLEAN = 5,
        // @axstodo Добавить поддержку типов даты и времени
        //TYPE_DATETIME = 6,
        //TYPE_DATE = 7,
        //TYPE_TIME = 8,
        TYPE_SELECT = 9,
        TYPE_MULTICHOICE = 10,
        TYPE_EMAIL = 11,
        TYPE_URL = 12,
        TYPE_REGEX = 13;

    const
        PREVIEW_LENGTH = 80;

    protected $availableTypes = [
        self::TYPE_TEXT => 'Текст',
        self::TYPE_TEXTAREA => 'Текстовое поле',
        self::TYPE_WYSIWYG => 'WYSIWYG редактор',
        self::TYPE_INTEGER => 'Целое число',
        self::TYPE_FLOAT => 'Вещественное число',
        self::TYPE_BOOLEAN => 'Да/нет',
        //self::TYPE_DATETIME => 'Дата и время',
        //self::TYPE_DATE => 'Дата',
        //self::TYPE_TIME => 'Время',
        self::TYPE_SELECT => 'Список (одно значение)',
        self::TYPE_MULTICHOICE => 'Список (несколько значений)',
        self::TYPE_EMAIL => 'E-mail',
        self::TYPE_URL => 'Url',
        self::TYPE_REGEX => 'Регулярное выражение',
    ];

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=32, unique = true)
     * @Assert\NotBlank()
     * @Assert\Regex("/^[0-9A-Z_]{4,32}$/")
     */
    protected $mask;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $value = '';

    /**
     * @ORM\Column(type="smallint")
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $choices = '';

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Assert\Type(type="boolean")
     */
    protected $required = false;

    /**
     * @ORM\ManyToOne(targetEntity="ConfigGroup", inversedBy="configs")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     **/
    protected $group;

    protected $changedFields = [];

    /**
     * Допустимые типы параметра
     *
     * @return array
     */
    public function getAvailableTypes()
    {
        return $this->availableTypes;
    }

    public function getChangedFields()
    {
        return $this->changedFields;
    }

    public function __toString()
    {
        return $this->getTitle() ? : 'Параметр';
    }

    public function getNormalizedValue()
    {
        switch ($this->type) {
            case self::TYPE_INTEGER;
                return intval($this->value);
            case self::TYPE_FLOAT:
                return floatval($this->value);
            case self::TYPE_BOOLEAN:
                return boolval($this->value);
            case self::TYPE_SELECT:
                $choices = (array)json_decode($this->getChoices());
                return isset($choices[$this->value]) ? $choices[$this->value] : null;
            case self::TYPE_MULTICHOICE:
                $choices = (array)json_decode($this->getChoices());
                $values = (array)json_decode($this->value);
                foreach ($values as $i => $key) {
                    if (isset($choices[$key])) {
                        $values[$i] = $choices[$key];
                    }
                }
                return $values;
            case self::TYPE_TEXT:
            case self::TYPE_TEXTAREA:
            case self::TYPE_WYSIWYG:
            case self::TYPE_EMAIL:
            case self::TYPE_URL:
            case self::TYPE_REGEX:
            default:
                return $this->value;
        }
    }

    public function previewValue()
    {
        $value = $this->getNormalizedValue();
        if (is_array($value)) {
            $value = join(', ', $value);
        }

        if ($this->type == self::TYPE_WYSIWYG) {
            $value = strip_tags(html_entity_decode($value));
        }

        if (mb_strlen($value) > self::PREVIEW_LENGTH) {
            $value = mb_substr($value, 0, self::PREVIEW_LENGTH) . '...';
        }

        return $value;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        if (array_intersect(['type', 'choices'], $this->changedFields)) {
            $this->value = null;
        }
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
     * @param ConfigGroup $group
     *
     * @return Config
     */
    public function setGroup(ConfigGroup $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return ConfigGroup
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Config
     */
    public function setType($type)
    {
        if ($this->type != $type) {
            $this->changedFields[] = 'type';
        }

        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set choices
     *
     * @param string $choices
     *
     * @return Config
     */
    public function setChoices($choices)
    {
        if ($this->choices != $choices) {
            $this->changedFields[] = 'choices';
        }

        $this->choices = $choices;

        return $this;
    }

    /**
     * Get choices
     *
     * @return string
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * Set required
     *
     * @param boolean $required
     *
     * @return Config
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Get required
     *
     * @return boolean
     */
    public function getRequired()
    {
        return $this->required;
    }
}
