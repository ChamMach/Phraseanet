<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\XMPXmpDM;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class ResampleParamsQuality extends AbstractTag
{

    protected $Id = 'resampleParamsQuality';

    protected $Name = 'ResampleParamsQuality';

    protected $FullName = 'XMP::xmpDM';

    protected $GroupName = 'XMP-xmpDM';

    protected $g0 = 'XMP';

    protected $g1 = 'XMP-xmpDM';

    protected $g2 = 'Image';

    protected $Type = 'string';

    protected $Writable = true;

    protected $Description = 'Resample Params Quality';

    protected $Values = array(
        'High' => array(
            'Id' => 'High',
            'Label' => 'High',
        ),
        'Low' => array(
            'Id' => 'Low',
            'Label' => 'Low',
        ),
        'Medium' => array(
            'Id' => 'Medium',
            'Label' => 'Medium',
        ),
    );

}
