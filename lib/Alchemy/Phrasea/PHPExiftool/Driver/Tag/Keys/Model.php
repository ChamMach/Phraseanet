<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\Keys;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Model extends AbstractTag
{

    protected $Id = 'model';

    protected $Name = 'Model';

    protected $FullName = 'QuickTime::Keys';

    protected $GroupName = 'Keys';

    protected $g0 = 'QuickTime';

    protected $g1 = 'Keys';

    protected $g2 = 'Other';

    protected $Type = 'string';

    protected $Writable = true;

    protected $Description = 'Model';

    protected $local_g2 = 'Camera';

}
