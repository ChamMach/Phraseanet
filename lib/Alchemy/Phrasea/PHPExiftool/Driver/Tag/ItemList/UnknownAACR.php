<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\ItemList;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class UnknownAACR extends AbstractTag
{

    protected $Id = 'AACR';

    protected $Name = 'Unknown_AACR';

    protected $FullName = 'QuickTime::ItemList';

    protected $GroupName = 'ItemList';

    protected $g0 = 'QuickTime';

    protected $g1 = 'ItemList';

    protected $g2 = 'Audio';

    protected $Type = 'string';

    protected $Writable = true;

    protected $Description = 'Unknown AACR';

}
