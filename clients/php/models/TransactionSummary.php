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
 * Summary information about an overall transaction.
 */
final class TransactionSummary
{
    /**
     * @var String Two character ISO-3166 country code.
     */
    public $country;

    /**
     * @var String Two or three character ISO region, state or province code, if applicable.
     */
    public $region;

    /**
     * @var JurisdictionType? The type of jurisdiction that collects this tax.
     */
    public $jurisType;

    /**
     * @var String Jurisdiction Code for the taxing jurisdiction
     */
    public $jurisCode;

    /**
     * @var String The name of the jurisdiction that collects this tax.
     */
    public $jurisName;

    /**
     * @var Int32? The unique ID of the Tax Authority Type that collects this tax.
     */
    public $taxAuthorityType;

    /**
     * @var String The state assigned number of the jurisdiction that collects this tax.
     */
    public $stateAssignedNo;

    /**
     * @var TaxType? The tax type of this tax.
     */
    public $taxType;

    /**
     * @var String The name of the tax.
     */
    public $taxName;

    /**
     * @var String Group code when special grouping is enabled.
     */
    public $taxGroup;

    /**
     * @var RateType? Indicates the tax rate type.
     */
    public $rateType;

    /**
     * @var Decimal? Tax Base - The adjusted taxable amount.
     */
    public $taxable;

    /**
     * @var Decimal? Tax Rate - The rate of taxation, as a fraction of the amount.
     */
    public $rate;

    /**
     * @var Decimal? Tax amount - The calculated tax (Base * Rate).
     */
    public $tax;

    /**
     * @var Decimal? Tax Calculated by Avalara AvaTax.  This may be overriden by a TaxOverride.TaxAmount.
     */
    public $taxCalculated;

    /**
     * @var Decimal? The amount of the transaction that was non-taxable.
     */
    public $nonTaxable;

    /**
     * @var Decimal? The amount of the transaction that was exempt.
     */
    public $exemption;

}
