<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\ASF;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class BannerImage extends AbstractTag
{

    protected $Id = 1;

    protected $Name = 'BannerImage';

    protected $FullName = 'ASF::ContentBranding';

    protected $GroupName = 'ASF';

    protected $g0 = 'ASF';

    protected $g1 = 'ASF';

    protected $g2 = 'Author';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'Banner Image';

    protected $local_g2 = 'Preview';

    protected $flag_Binary = true;

}
