<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\XMPMwgRs;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class RegionAreaX extends AbstractTag
{

    protected $Id = 'RegionsRegionListAreaX';

    protected $Name = 'RegionAreaX';

    protected $FullName = 'MWG::Regions';

    protected $GroupName = 'XMP-mwg-rs';

    protected $g0 = 'XMP';

    protected $g1 = 'XMP-mwg-rs';

    protected $g2 = 'Image';

    protected $Type = 'real';

    protected $Writable = true;

    protected $Description = 'Region Area X';

    protected $flag_List = true;

}
