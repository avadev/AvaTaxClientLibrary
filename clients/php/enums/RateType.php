<?php
/*
 * AvaTax Enum Class
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
 */class RateType extends AvaTaxEnum 
{

    const ReducedA = "ReducedA";
    const ReducedB = "ReducedB";
    const Food = "Food";
    const General = "General";
    const IncreasedStandard = "IncreasedStandard";
    const LinenRental = "LinenRental";
    const Medical = "Medical";
    const Parking = "Parking";
    const SuperReduced = "SuperReduced";
    const ReducedR = "ReducedR";
    const Standard = "Standard";
    const Zero = "Zero";
}
