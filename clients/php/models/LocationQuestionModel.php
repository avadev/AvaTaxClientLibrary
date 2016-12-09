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
 * Information about questions that the local jurisdictions require for each location
 */
final class LocationQuestionModel
{
    /**
     * @var Int32 The unique ID number of this location setting type
     */
    public $id;

    /**
     * @var String This is the prompt for this question
     */
    public $question;

    /**
     * @var String If additional information is available about the location setting, this contains descriptive text to help
     *             you identify the correct value to provide in this setting.
     */
    public $description;

    /**
     * @var String If available, this regular expression will verify that the input from the user is in the expected format.
     */
    public $regularExpression;

    /**
     * @var String If available, this is an example value that you can demonstrate to the user to show what is expected.
     */
    public $exampleValue;

    /**
     * @var String Indicates which jurisdiction requires this question
     */
    public $jurisdictionName;

    /**
     * @var JurisdictionType? Indicates which type of jurisdiction requires this question
     */
    public $jurisdictionType;

    /**
     * @var String Indicates the country that this jurisdiction belongs to
     */
    public $jurisdictionCountry;

    /**
     * @var String Indicates the state, region, or province that this jurisdiction belongs to
     */
    public $jurisdictionRegion;

}
