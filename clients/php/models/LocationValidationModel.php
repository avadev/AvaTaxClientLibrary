<?php 
namespace Avalara;
/*
 * AvaTax API Client Library
 *
 * (c) 2004-2016 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   AvaTax client libraries
 * @package    Avalara.AvaTaxClient
 * @author     Ted Spence <ted.spence@avalara.com>
 * @author     Bob Maidens <bob.maidens@avalara.com>
 * @copyright  2004-2016 Avalara, Inc.
 * @license    https://www.apache.org/licenses/LICENSE-2.0
 * @version    2.16.12-30
 * @link       https://github.com/avadev/AvaTaxClientLibrary
 */


/**
 * Tells you whether this location object has been correctly set up to the local jurisdiction's standards
 */
final class LocationValidationModel
{
    /**
     * @var Boolean? True if the location has a value for each jurisdiction-required setting.
     *             The user is required to ensure that the values are correct according to the jurisdiction; this flag
     *             does not indicate whether the taxing jurisdiction has accepted the data you have provided.
     */
    public $settingsValidated;

    /**
     * @var List<LocationQuestionModel> A list of settings that must be defined for this location
     */
    public $requiredSettings;

}
