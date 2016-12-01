<?php
/*
 * AvaTax Model
 *
 * (c) 2004-2016 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Avalara.AvaTax;

/**
 * @author Ted Spence <ted.spence@avalara.com>
 * @author Bob Maidens <bob.maidens@avalara.com
 */
final class ParameterModel extends AbstractEntity
{
    /**
     * @var Int32?
     */
    public $id;

    /**
     * @var String
     */
    public $category;

    /**
     * @var String
     */
    public $name;

    /**
     * @var ParameterBagDataType?
     */
    public $dataType;

    /**
     * @var String
     */
    public $description;

}