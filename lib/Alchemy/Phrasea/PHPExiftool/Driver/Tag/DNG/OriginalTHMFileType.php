<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\DNG;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class OriginalTHMFileType extends AbstractTag
{

    protected $Id = 6;

    protected $Name = 'OriginalTHMFileType';

    protected $FullName = 'DNG::OriginalRaw';

    protected $GroupName = 'DNG';

    protected $g0 = 'DNG';

    protected $g1 = 'DNG';

    protected $g2 = 'Image';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'Original THM File Type';

}
