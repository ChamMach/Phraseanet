<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\Kodak;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class DateTimeOriginal extends AbstractTag
{

    protected $Id = 20;

    protected $Name = 'DateTimeOriginal';

    protected $FullName = 'Kodak::Type9';

    protected $GroupName = 'Kodak';

    protected $g0 = 'MakerNotes';

    protected $g1 = 'Kodak';

    protected $g2 = 'Camera';

    protected $Type = 'string';

    protected $Writable = true;

    protected $Description = 'Date/Time Original';

    protected $local_g2 = 'Time';

    protected $flag_Permanent = true;

    protected $MaxLength = 20;

}
