<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\Nikon;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class ExposureTuning extends AbstractTag
{

    protected $Id = 28;

    protected $Name = 'ExposureTuning';

    protected $FullName = 'Nikon::Main';

    protected $GroupName = 'Nikon';

    protected $g0 = 'MakerNotes';

    protected $g1 = 'Nikon';

    protected $g2 = 'Camera';

    protected $Type = 'undef';

    protected $Writable = true;

    protected $Description = 'Exposure Tuning';

    protected $flag_Permanent = true;

    protected $MaxLength = 3;

}
