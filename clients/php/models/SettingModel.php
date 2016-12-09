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
 * This object is used to keep track of custom information about a company.
            A setting can refer to any type of data you need to remember about this company object.
            When creating this object, you may define your own "set", "name", and "value" parameters.
            To define your own values, please choose a "set" name that begins with "X-" to indicate an extension.
 */
final class SettingModel
{
    /**
     * @var Int32 The unique ID number of this setting.
     */
    public $id;

    /**
     * @var Int32 The unique ID number of the company this setting refers to.
     */
    public $companyId;

    /**
     * @var String A user-defined "set" containing this name-value pair.
     */
    public $set;

    /**
     * @var String A user-defined "name" for this name-value pair.
     */
    public $name;

    /**
     * @var String The value of this name-value pair.
     */
    public $value;

}
