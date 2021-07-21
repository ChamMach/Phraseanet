<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\ID3v1;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class Artist extends AbstractTag
{

    protected $Id = 33;

    protected $Name = 'Artist';

    protected $FullName = 'ID3::v1';

    protected $GroupName = 'ID3v1';

    protected $g0 = 'ID3';

    protected $g1 = 'ID3v1';

    protected $g2 = 'Audio';

    protected $Type = 'string';

    protected $Writable = false;

    protected $Description = 'Artist';

    protected $local_g2 = 'Author';

    protected $MaxLength = 30;

}
