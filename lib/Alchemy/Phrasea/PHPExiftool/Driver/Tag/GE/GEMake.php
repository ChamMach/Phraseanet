<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\GE;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class GEMake extends AbstractTag
{

    protected $Id = 768;

    protected $Name = 'GEMake';

    protected $FullName = 'GE::Main';

    protected $GroupName = 'GE';

    protected $g0 = 'MakerNotes';

    protected $g1 = 'GE';

    protected $g2 = 'Camera';

    protected $Type = 'string';

    protected $Writable = true;

    protected $Description = 'GE Make';

    protected $flag_Permanent = true;

}
