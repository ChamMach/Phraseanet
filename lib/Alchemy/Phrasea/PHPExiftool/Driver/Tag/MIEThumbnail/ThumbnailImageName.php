<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\MIEThumbnail;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class ThumbnailImageName extends AbstractTag
{

    protected $Id = '1Name';

    protected $Name = 'ThumbnailImageName';

    protected $FullName = 'MIE::Thumbnail';

    protected $GroupName = 'MIE-Thumbnail';

    protected $g0 = 'MIE';

    protected $g1 = 'MIE-Thumbnail';

    protected $g2 = 'Image';

    protected $Type = 'string';

    protected $Writable = true;

    protected $Description = 'Thumbnail Image Name';

}
