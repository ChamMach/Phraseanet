<?php

/*
 * This file is part of the PHPExifTool package.
 *
 * (c) Alchemy <support@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\PHPExiftool\Driver\Tag\Qualcomm;

use Alchemy\Phrasea\PHPExiftool\Driver\AbstractTag;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ExclusionPolicy("all")
 */
class AECCurrentExpIndex extends AbstractTag
{

    protected $Id = 'aec_current_exp_index';

    protected $Name = 'AECCurrentExpIndex';

    protected $FullName = 'Qualcomm::Main';

    protected $GroupName = 'Qualcomm';

    protected $g0 = 'MakerNotes';

    protected $g1 = 'Qualcomm';

    protected $g2 = 'Camera';

    protected $Type = '?';

    protected $Writable = false;

    protected $Description = 'AEC Current Exp Index';

    protected $flag_Permanent = true;

}
