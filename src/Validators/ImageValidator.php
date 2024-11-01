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
    public function __construct(
        protected array $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'],
        protected int $maxSize = 2 * 1024 * 1024, // 2MB
        protected float $minRatio = 0.5,
        protected float $maxRatio = 2.0,
        protected int $minWidth = 100,
        protected int $maxWidth = 1920,
        protected int $minHeight = 100,
        protected int $maxHeight = 1080
    ) {}

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
        if (!file_exists($data)) {
            throw new FileNotFoundException("File not uploaded correctly.");
        }

        if (($this->allowedMimeTypes ?? false) && !in_array(mime_content_type($data), $this->allowedMimeTypes)) {
            throw new InvalidMimeTypeException("Invalid MIME type.");
        }

        $size = filesize($data);
        if (($this->maxSize ?? false) && $size > $this->maxSize) {
            throw new FileSizeExceededException("File size exceeds the maximum limit.");
        }

        [$width, $height] = getimagesize($data);
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