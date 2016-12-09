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
 * Address Resolution Model
 */
final class AddressResolutionModel
{
    /**
     * @var AddressInfo The original address
     */
    public $address;

    /**
     * @var List<AddressInfo> The validated address or addresses
     */
    public $validatedAddresses;

    /**
     * @var CoordinateInfo The geospatial coordinates of this address
     */
    public $coordinates;

    /**
     * @var ResolutionQuality? The resolution quality of the geospatial coordinates
     */
    public $resolutionQuality;

    /**
     * @var List<TaxAuthorityInfo> List of informational and warning messages regarding this address
     */
    public $taxAuthorities;

    /**
     * @var List<AvaTaxMessage> List of informational and warning messages regarding this address
     */
    public $messages;

}
