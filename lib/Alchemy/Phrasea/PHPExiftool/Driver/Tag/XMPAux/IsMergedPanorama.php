<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\XMPAux;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class IsMergedPanorama extends AbstractTag
{

    protected $Id = 'IsMergedPanorama';

    protected $Name = 'IsMergedPanorama';

    protected $FullName = 'XMP::aux';

    protected $GroupName = 'XMP-aux';

    protected $g0 = 'XMP';

    protected $g1 = 'XMP-aux';

    protected $g2 = 'Camera';

    protected $Type = 'boolean';

    protected $Writable = true;

    protected $Description = 'Is Merged Panorama';

}
