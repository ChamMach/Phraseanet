<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\XMPIcs;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class TimeStamp extends AbstractTag
{

    protected $Id = 'TimeStamp';

    protected $Name = 'TimeStamp';

    protected $FullName = 'XMP::ics';

    protected $GroupName = 'XMP-ics';

    protected $g0 = 'XMP';

    protected $g1 = 'XMP-ics';

    protected $g2 = 'Image';

    protected $Type = 'date';

    protected $Writable = true;

    protected $Description = 'Time Stamp';

    protected $local_g2 = 'Time';

    protected $flag_Avoid = true;

}
