<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\Sanyo;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class ThumbnailWidth extends AbstractTag
{

    protected $Id = 1;

    protected $Name = 'ThumbnailWidth';

    protected $FullName = 'Sanyo::Thumbnail';

    protected $GroupName = 'Sanyo';

    protected $g0 = 'MakerNotes';

    protected $g1 = 'Sanyo';

    protected $g2 = 'Camera';

    protected $Type = 'int32u';

    protected $Writable = false;

    protected $Description = 'Thumbnail Width';

    protected $flag_Permanent = true;

}
