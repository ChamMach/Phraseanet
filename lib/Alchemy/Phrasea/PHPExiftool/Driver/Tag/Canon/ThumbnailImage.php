<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\Canon;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class ThumbnailImage extends AbstractTag
{

    protected $Id = 'mixed';

    protected $Name = 'ThumbnailImage';

    protected $FullName = 'mixed';

    protected $GroupName = 'Canon';

    protected $g0 = 'MakerNotes';

    protected $g1 = 'Canon';

    protected $g2 = 'Video';

    protected $Type = 'mixed';

    protected $Writable = false;

    protected $Description = 'Thumbnail Image';

    protected $local_g2 = 'Preview';

    protected $flag_Permanent = true;

    protected $flag_Binary = false;

}
