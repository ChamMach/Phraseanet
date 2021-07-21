<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\GSpherical;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class FullPanoHeightPixels extends AbstractTag
{

    protected $Id = 'FullPanoHeightPixels';

    protected $Name = 'FullPanoHeightPixels';

    protected $FullName = 'XMP::GSpherical';

    protected $GroupName = 'GSpherical';

    protected $g0 = 'XMP';

    protected $g1 = 'XMP-GSpherical';

    protected $g2 = 'Image';

    protected $Type = 'integer';

    protected $Writable = true;

    protected $Description = 'Full Pano Height Pixels';

    protected $local_g1 = 'GSpherical';

    protected $flag_Avoid = true;

}
