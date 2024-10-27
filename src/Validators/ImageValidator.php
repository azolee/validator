<?php

namespace Azolee\Validator\Validators;

use Azolee\Validator\Exceptions\FileNotFoundException;
use Azolee\Validator\Exceptions\InvalidMimeTypeException;
use Azolee\Validator\Exceptions\FileSizeExceededException;
use Azolee\Validator\Exceptions\InvalidImageRatioException;
use Azolee\Validator\Exceptions\InvalidImageWidthException;
use Azolee\Validator\Exceptions\InvalidImageHeightException;

class ImageValidator
{
    protected array $allowedMimeTypes;
    protected int $maxSize; // in bytes
    protected float $minRatio;
    protected float $maxRatio;
    protected int $minWidth;
    protected int $maxWidth;
    protected int $minHeight;
    protected int $maxHeight;

    public function __construct(
        array $allowedMimeTypes = null,
        int $maxSize = null,
        float $minRatio = null,
        float $maxRatio = null,
        int $minWidth = null,
        int $maxWidth = null,
        int $minHeight = null,
        int $maxHeight = null
    ) {
        $this->allowedMimeTypes = $allowedMimeTypes ?? ['image/jpeg', 'image/png', 'image/gif'];
        $this->maxSize = $maxSize ?? 2 * 1024 * 1024; // 2MB
        $this->minRatio = $minRatio ?? 0.5;
        $this->maxRatio = $maxRatio ?? 2;
        $this->minWidth = $minWidth ?? 100;
        $this->maxWidth = $maxWidth ?? 1920;
        $this->minHeight = $minHeight ?? 100;
        $this->maxHeight = $maxHeight ?? 1080;
    }

    /**
     * @throws FileNotFoundException
     * @throws InvalidMimeTypeException
     * @throws FileSizeExceededException
     * @throws InvalidImageRatioException
     * @throws InvalidImageWidthException
     * @throws InvalidImageHeightException
     */
    public function validate($data): bool
    {
        if (!file_exists($data['tmp_name'])) {
            throw new FileNotFoundException("File not uploaded correctly.");
        }

        if (($this->allowedMimeTypes ?? false) && !in_array(mime_content_type($data['tmp_name']), $this->allowedMimeTypes)) {
            throw new InvalidMimeTypeException("Invalid MIME type.");
        }

        $size = filesize($data['tmp_name']);
        if (($this->maxSize ?? false) && $size > $this->maxSize) {
            throw new FileSizeExceededException("File size exceeds the maximum limit.");
        }

        [$width, $height] = getimagesize($data['tmp_name']);
        $ratio = $width / $height;

        if (($this->minRatio ?? false) && ($ratio < $this->minRatio)) {
            throw new InvalidImageRatioException("Image ratio is too small.");
        }

        if (($this->maxRatio ?? false) && ($ratio > $this->maxRatio)) {
            throw new InvalidImageRatioException("Image ratio is too large.");
        }

        if (($this->minWidth ?? false) && $width < $this->minWidth) {
            throw new InvalidImageWidthException("Image width is too small.");
        }

        if (($this->maxWidth ?? false) && $width > $this->maxWidth) {
            throw new InvalidImageWidthException("Image width is too large.");
        }

        if (($this->minHeight ?? false) && $height < $this->minHeight) {
            throw new InvalidImageHeightException("Image height is too small.");
        }

        if (($this->maxHeight ?? false) && $height > $this->maxHeight) {
            throw new InvalidImageHeightException("Image height is too large.");
        }

        return true;
    }
}