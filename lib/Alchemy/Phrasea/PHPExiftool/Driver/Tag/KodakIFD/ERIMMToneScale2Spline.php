<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\KodakIFD;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class ERIMMToneScale2Spline extends AbstractTag
{

    protected $Id = 3972;

    protected $Name = 'ERIMMToneScale2Spline';

    protected $FullName = 'Kodak::IFD';

    protected $GroupName = 'KodakIFD';

    protected $g0 = 'MakerNotes';

    protected $g1 = 'KodakIFD';

    protected $g2 = 'Image';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'ERIMM Tone Scale 2 Spline';

    protected $flag_Permanent = true;

}
