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
 * Company Initialization Model
 */
final class CompanyInitializationModel
{
    /**
     * @var String Company Name
     */
    public $name;

    /**
     * @var String Company Code - used to distinguish between companies within your accounting system
     */
    public $companyCode;

    /**
     * @var String Vat Registration Id - leave blank if not known.
     */
    public $vatRegistrationId;

    /**
     * @var String United States Taxpayer ID number, usually your Employer Identification Number if you are a business or your Social Security Number if you are an individual.
     */
    public $taxpayerIdNumber;

    /**
     * @var String Address Line1
     */
    public $line1;

    /**
     * @var String Line2
     */
    public $line2;

    /**
     * @var String Line3
     */
    public $line3;

    /**
     * @var String City
     */
    public $city;

    /**
     * @var String Two character ISO 3166 Region code for this company's primary business location.
     */
    public $region;

    /**
     * @var String Postal Code
     */
    public $postalCode;

    /**
     * @var String Two character ISO 3166 Country code for this company's primary business location.
     */
    public $country;

    /**
     * @var String First Name
     */
    public $firstName;

    /**
     * @var String Last Name
     */
    public $lastName;

    /**
     * @var String Title
     */
    public $title;

    /**
     * @var String Email
     */
    public $email;

    /**
     * @var String Phone Number
     */
    public $phoneNumber;

    /**
     * @var String Mobile Number
     */
    public $mobileNumber;

    /**
     * @var String Fax Number
     */
    public $faxNumber;

}
