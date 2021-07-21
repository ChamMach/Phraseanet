<?php

/*
 * This file is part of Media-Alchemyst.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\MediaAlchemyst\Transmuter;

use Alchemy\BinaryDriver\Exception\ExceptionInterface as BinaryAdapterException;
use Alchemy\Phrasea\MediaAlchemyst\Exception\RuntimeException;
use Alchemy\Phrasea\MediaAlchemyst\Exception\SpecNotSupportedException;
use Alchemy\Phrasea\MediaAlchemyst\Specification\Image;
use Alchemy\Phrasea\MediaAlchemyst\Specification\SpecificationInterface;
use Alchemy\Phrasea\MediaVorus\Exception\ExceptionInterface as MediaVorusException;
use Alchemy\Phrasea\MediaVorus\Media\MediaInterface;
use Imagine\Exception\Exception as ImagineException;
use Imagine\Image\ImageInterface;
use SwfTools\Exception\ExceptionInterface as SwfToolsException;

class Flash2Image extends AbstractTransmuter
{
    public function execute(SpecificationInterface $spec, MediaInterface $source, $dest)
    {
        if (! $spec instanceof Image) {
            throw new SpecNotSupportedException('SwfTools only accept Image specs');
        }

        $tmpDest = $this->tmpFileManager->createTemporaryFile(self::TMP_FILE_SCOPE, 'swfrender');

        try {
            // swfrender may change the output filename given the format
            $tmpDest = $this->container['swftools.flash-file']->render(
                $source->getFile()->getPathname(), $tmpDest
            );
            $this->tmpFileManager->add($tmpDest, self::TMP_FILE_SCOPE);

            $image = $this->container['imagine']->open($tmpDest);

            if ($spec->getWidth() && $spec->getHeight()) {
                $box = $this->boxFromSize($spec, $image->getSize()->getWidth(), $image->getSize()->getHeight());

                if (null !== $box) {
                    if ($spec->getResizeMode() == Image::RESIZE_MODE_OUTBOUND) {
                        /* @var $image \Imagine\Gmagick\Image */
                        $image = $image->thumbnail($box, ImageInterface::THUMBNAIL_OUTBOUND);
                    } else {
                        $image = $image->resize($box);
                    }
                }
            }

            $options = array(
                'quality'          => $spec->getQuality(),
                'resolution-units' => $spec->getResolutionUnit(),
                'resolution-x'     => $spec->getResolutionX(),
                'resolution-y'     => $spec->getResolutionY(),
//                'flatten'          => $spec->isFlatten(),
                'disable-alpha'    => $spec->isFlatten(),
            );

            $image->save($dest, $options);

            unset($image);

            $this->tmpFileManager->clean(self::TMP_FILE_SCOPE);
        } catch (BinaryAdapterException $e) {
            $this->tmpFileManager->clean(self::TMP_FILE_SCOPE);
            throw new RuntimeException('Unable to transmute flash to image due to Binary Adapter', $e->getCode(), $e);
        } catch (SwfToolsException $e) {
            $this->tmpFileManager->clean(self::TMP_FILE_SCOPE);
            throw new RuntimeException('Unable to transmute flash to image due to SwfTools', $e->getCode(), $e);
        } catch (ImagineException $e) {
            $this->tmpFileManager->clean(self::TMP_FILE_SCOPE);
            throw new RuntimeException('Unable to transmute flash to image due to Imagine', $e->getCode(), $e);
        } catch (MediaVorusException $e) {
            $this->tmpFileManager->clean(self::TMP_FILE_SCOPE);
            throw new RuntimeException('Unable to transmute flash to image due to MediaVorus', $e->getCode(), $e);
        } catch (RuntimeException $e) {
            $this->tmpFileManager->clean(self::TMP_FILE_SCOPE);
            throw $e;
        }
    }
}
