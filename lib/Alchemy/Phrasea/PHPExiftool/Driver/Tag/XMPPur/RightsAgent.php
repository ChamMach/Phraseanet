<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\XMPPur;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class RightsAgent extends AbstractTag
{

    protected $Id = 'rightsAgent';

    protected $Name = 'RightsAgent';

    protected $FullName = 'XMP::pur';

    protected $GroupName = 'XMP-pur';

    protected $g0 = 'XMP';

    protected $g1 = 'XMP-pur';

    protected $g2 = 'Document';

    protected $Type = 'string';

    protected $Writable = true;

    protected $Description = 'Rights Agent';

    protected $flag_Avoid = true;

}
