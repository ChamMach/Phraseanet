<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\XMPMwgKw;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class HierarchicalKeywords extends AbstractTag
{

    protected $Id = 'KeywordsHierarchy';

    protected $Name = 'HierarchicalKeywords';

    protected $FullName = 'MWG::Keywords';

    protected $GroupName = 'XMP-mwg-kw';

    protected $g0 = 'XMP';

    protected $g1 = 'XMP-mwg-kw';

    protected $g2 = 'Image';

    protected $Type = 'struct';

    protected $Writable = true;

    protected $Description = 'Hierarchical Keywords';

    protected $flag_List = true;

    protected $flag_Bag = true;

}
