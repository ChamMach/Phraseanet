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
class UnknownSerial1 extends AbstractTag
{

    protected $Id = 12;

    protected $Name = 'UnknownSerial1';

    protected $FullName = 'FLIR::SerialNums';

    protected $GroupName = 'FLIR';

    protected $g0 = 'MakerNotes';

    protected $g1 = 'FLIR';

    protected $g2 = 'Camera';

    protected $Type = 'string';

    protected $Writable = false;

    protected $Description = 'Unknown Serial 1';

    protected $flag_Permanent = true;

    protected $MaxLength = 33;

}
