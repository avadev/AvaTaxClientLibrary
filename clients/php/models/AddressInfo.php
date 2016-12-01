<?php
/*
 * AvaTax Entity Model Class
 *
 * (c) 2004-2016 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Avalara.AvaTax;

/**
 * @author Ted Spence <ted.spence@avalara.com>
 * @author Bob Maidens <bob.maidens@avalara.com>
 */
final class AddressInfo extends AbstractEntity
{
    /**
     * @var String
     */
    public $line1;

    /**
     * @var String
     */
    public $line2;

    /**
     * @var String
     */
    public $line3;

    /**
     * @var String
     */
    public $city;

    /**
     * @var String
     */
    public $region;

    /**
     * @var String
     */
    public $country;

    /**
     * @var String
     */
    public $postalCode;

    /**
     * @var Decimal?
     */
    public $latitude;

    /**
     * @var Decimal?
     */
    public $longitude;

}
