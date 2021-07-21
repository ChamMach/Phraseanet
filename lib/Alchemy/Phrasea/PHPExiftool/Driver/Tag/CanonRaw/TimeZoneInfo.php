<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\CanonRaw;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class TimeZoneInfo extends AbstractTag
{

    protected $Id = 2;

    protected $Name = 'TimeZoneInfo';

    protected $FullName = 'CanonRaw::TimeStamp';

    protected $GroupName = 'CanonRaw';

    protected $g0 = 'MakerNotes';

    protected $g1 = 'CanonRaw';

    protected $g2 = 'Time';

    protected $Type = 'int32u';

    protected $Writable = true;

    protected $Description = 'Time Zone Info';

    protected $flag_Permanent = true;

}
