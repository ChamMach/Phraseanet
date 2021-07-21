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
class CroppedAreaImageHeightPixels extends AbstractTag
{

    protected $Id = 'CroppedAreaImageHeightPixels';

    protected $Name = 'CroppedAreaImageHeightPixels';

    protected $FullName = 'XMP::GSpherical';

    protected $GroupName = 'GSpherical';

    protected $g0 = 'XMP';

    protected $g1 = 'XMP-GSpherical';

    protected $g2 = 'Image';

    protected $Type = 'integer';

    protected $Writable = true;

    protected $Description = 'Cropped Area Image Height Pixels';

    protected $local_g1 = 'GSpherical';

    protected $flag_Avoid = true;

}
