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
 * An extra property that can change the behavior of tax transactions.
 */
final class ParameterModel
{
    /**
     * @var Int32? The unique ID number of this property.
     */
    public $id;

    /**
     * @var String The service category of this property.  Some properties may require that you subscribe to certain features of avatax before they can be used.
     */
    public $category;

    /**
     * @var String The name of the property.  To use this property, add a field on the "properties" object of a /api/v2/companies/(code)/transactions/create call.
     */
    public $name;

    /**
     * @var ParameterBagDataType? The data type of the property.
     */
    public $dataType;

    /**
     * @var String A full description of this property.
     */
    public $description;

}
