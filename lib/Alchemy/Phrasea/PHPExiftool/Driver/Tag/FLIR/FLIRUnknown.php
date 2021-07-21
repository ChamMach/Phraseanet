<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\FLIR;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class FLIRUnknown extends AbstractTag
{

    protected $Id = 'uuid';

    protected $Name = 'FLIR_Unknown';

    protected $FullName = 'FLIR::UserData';

    protected $GroupName = 'FLIR';

    protected $g0 = 'FLIR';

    protected $g1 = 'FLIR';

    protected $g2 = 'Camera';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'FLIR Unknown';

    protected $Index = 7;

}
