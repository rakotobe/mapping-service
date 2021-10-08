<?php

declare(strict_types=1);

namespace Core\Domain\Bundle;

use Core\Domain\InvalidInputException;

class Bundle
{
    const TAX_CLASSIFICATION_DEFAULT = 'streaming';

    /** @var int */
    protected $bundleId;

    /** @var string */
    protected $internalName;

    /** @var string */
    protected $title;

    /** @var string */
    protected $probillerBundleId;

    /** @var int */
    protected $instance;

    /** @var string */
    protected $thumb;

    /** @var string */
    protected $description;

    /** @var string */
    protected $taxClassification;

    /**
     * Bundle constructor.
     * @param int $bundleId
     * @param string $internalName
     * @param string $title
     * @param string $probillerBundleId
     * @param int $instance
     * @param string $thumb
     * @param string $description
     * @param string $taxClassification
     * @throws InvalidInputException
     */
    public function __construct(
        int $bundleId,
        string $internalName,
        string $title,
        string $probillerBundleId,
        int $instance,
        string $thumb,
        string $description,
        string $taxClassification
    ) {

        $this->bundleId = $bundleId;
        $this->internalName = trim($internalName);
        $this->title = trim($title);
        $this->instance = $instance;
        $this->thumb = trim($thumb);
        $this->probillerBundleId = trim($probillerBundleId);
        $this->description = trim($description);
        $this->taxClassification = trim($taxClassification);
        $this->validate();
    }

    /** @throws InvalidInputException */
    protected function validate(): void
    {
       $this->validateTitle();
       $this->validateInternalName();
       $this->validateProbillerBundleId();
       $this->validateThumb();
       $this->validateTaxClassification();
    }

    /** @codeCoverageIgnore  */
    public function getBundleId(): int
    {
        return $this->bundleId;
    }

    /** @codeCoverageIgnore  */
    public function getInternalName(): string
    {
        return $this->internalName;
    }

    /**
     * @param string $internalName
     * @throws InvalidInputException
     * @codeCoverageIgnore
     */
    public function setInternalName(string $internalName): void
    {
        $this->internalName = trim($internalName);
        $this->validateInternalName();
    }

    /** @codeCoverageIgnore  */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @throws InvalidInputException
     * @codeCoverageIgnore
     */
    public function setTitle(string $title): void
    {
        $this->title = trim($title);
        $this->validateTitle();
    }

    /** @codeCoverageIgnore  */
    public function getProbillerBundleId(): string
    {
        return $this->probillerBundleId;
    }

    /**
     * @param string $probillerBundleId
     * @throws InvalidInputException
     * @codeCoverageIgnore
     */
    public function setProbillerBundleId(string $probillerBundleId): void
    {
        $this->probillerBundleId = trim($probillerBundleId);
        $this->validateProbillerBundleId();
    }

    /** @codeCoverageIgnore  */
    public function getInstance(): int
    {
        return $this->instance;
    }

    /** @codeCoverageIgnore  */
    public function setInstance(int $instance): void
    {
        $this->instance = $instance;
    }

    /** @codeCoverageIgnore  */
    public function getThumb(): ?string
    {
        return $this->thumb;
    }

    /**
     * @param string $thumb
     * @throws InvalidInputException
     * @codeCoverageIgnore
     */
    public function setThumb(string $thumb): void
    {
        $this->thumb = trim($thumb);
        $this->validateThumb();
    }

    /** @codeCoverageIgnore  */
    public function getDescription(): string
    {
        return $this->description ? $this->description : '';
    }

    /**
     * @param string $description
     * @codeCoverageIgnore
     */
    public function setDescription(string $description): void
    {
        $this->description = trim($description);
    }

    /** @codeCoverageIgnore  */
    public function getTaxClassification(): string
    {
        return $this->taxClassification ? $this->taxClassification : self::TAX_CLASSIFICATION_DEFAULT;
    }

    /**
     * @param string $taxClassification
     * @throws InvalidInputException
     * @codeCoverageIgnore
     */
    public function setTaxClassification(string $taxClassification): void
    {
        $this->taxClassification = trim($taxClassification);
        $this->validateTaxClassification();
    }

    /** @throws InvalidInputException */
    protected function validateTitle() {
        if ($this->title == '') {
            throw new InvalidInputException('Title should not be empty');
        }
    }

    /** @throws InvalidInputException */
    protected function validateInternalName() {
        if ($this->internalName == '') {
            throw new InvalidInputException('Internal name should not be empty');
        }
    }

    /** @throws InvalidInputException */
    protected function validateProbillerBundleId() {
        if ($this->probillerBundleId == '') {
            throw new InvalidInputException('Probiller Id should not be empty');
        }
    }

    /** @throws InvalidInputException */
    protected function validateThumb() {
        if ($this->thumb == '') {
            throw new InvalidInputException('Thumb should not be empty');
        }
    }

    /** @throws InvalidInputException */
    protected function validateTaxClassification() {
        if ($this->taxClassification == '') {
            throw new InvalidInputException('Tax classification should not be empty');
        }
    }
}

