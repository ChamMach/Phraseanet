<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\Leaf;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class CCDVideoRect extends AbstractTag
{

    protected $Id = 'CaptProf_CCD_video_rect';

    protected $Name = 'CCDVideoRect';

    protected $FullName = 'Leaf::CaptureProfile';

    protected $GroupName = 'Leaf';

    protected $g0 = 'Leaf';

    protected $g1 = 'Leaf';

    protected $g2 = 'Image';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'CCD Video Rect';

}
