<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\XMPTiff;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class SamplesPerPixel extends AbstractTag
{

    protected $Id = 'SamplesPerPixel';

    protected $Name = 'SamplesPerPixel';

    protected $FullName = 'XMP::tiff';

    protected $GroupName = 'XMP-tiff';

    protected $g0 = 'XMP';

    protected $g1 = 'XMP-tiff';

    protected $g2 = 'Image';

    protected $Type = 'integer';

    protected $Writable = true;

    protected $Description = 'Samples Per Pixel';

}
