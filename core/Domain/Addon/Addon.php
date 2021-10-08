<?php

declare(strict_types=1);

namespace Core\Domain\Addon;

use Core\Domain\InvalidInputException;

class Addon
{
    const TYPE_FEATURE = 'Feature';

    const TYPE_CONTENT = 'Content';

    /** @var int */
    protected $addonId;

    /** @var string */
    protected $probillerAddonId;

    /** @var string */
    protected $internalName;

    /** @var string */
    protected $title;

    /** @var string */
    protected $type;

    /** @var string */
    protected $thumb;

    /** @var string */
    protected $featureName = null;

    /** @var string */
    protected $contentGroupId = null;

    public function __construct(
        int $addonId,
        string $probillerAddonId,
        string $internalName,
        string $title,
        string $type,
        string $thumb,
        string $featureName = null,
        string $contentGroupId = null
    ) {
        $this->addonId = $addonId;
        $this->probillerAddonId = $probillerAddonId;
        $this->internalName = $internalName;
        $this->title = $title;
        $this->type = $type;
        $this->thumb = $thumb;
        $this->featureName = $featureName;
        $this->contentGroupId = $contentGroupId;
        $this->validate();
    }

    /**
     * @codeCoverageIgnore
     */
    public function getAddonId(): int
    {
        return $this->addonId;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getProbillerAddonId(): string
    {
        return $this->probillerAddonId;
    }

    /**
     * @codeCoverageIgnore
     */
    public function setInternalName(string $internalName)
    {
        $this->internalName = $internalName;
        $this->validateInternalName();
    }

    /**
     * @codeCoverageIgnore
     */
    public function getInternalName(): string
    {
        return $this->internalName;
    }

    /**
     * @codeCoverageIgnore
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        $this->validateTitle();
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @codeCoverageIgnore
     */
    public function setType(string $type)
    {
        $this->type = $type;
        $this->validateType();
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getThumb(): string
    {
        return $this->thumb;
    }

    /**
     * @param string $thumb
     * @codeCoverageIgnore
     * @throws InvalidInputException
     */
    public function setThumb(string $thumb)
    {
        $this->thumb = $thumb;
        $this->validateThumb();
    }

    /**
     * @codeCoverageIgnore
     */
    public function setFeatureName(?string $featureName)
    {
        $this->featureName = $featureName;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getFeatureName(): ?string
    {
        return $this->featureName;
    }

    /**
     * @codeCoverageIgnore
     */
    public function setContentGroupId(?string $contentGroupId)
    {
        $this->contentGroupId = $contentGroupId;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getContentGroupId(): ?string
    {
        return $this->contentGroupId;
    }

    protected function validateInternalName()
    {
        if ($this->internalName == '') {
            throw new InvalidInputException();
        }
    }

    protected function validateTitle()
    {
        if ($this->title == '') {
            throw new InvalidInputException();
        }
    }

    protected function validateType()
    {
        if ($this->type == '') {
            throw new InvalidInputException();
        }
    }

    protected function validateThumb()
    {
        if (empty($this->thumb)) {
            throw new InvalidInputException('Thumb cannot be empty');
        }
    }

    protected function validate()
    {
        $this->validateTitle();
        $this->validateInternalName();
        $this->validateType();
        $this->validateThumb();
    }
}
