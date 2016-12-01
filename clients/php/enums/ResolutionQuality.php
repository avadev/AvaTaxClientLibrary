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
 */class ResolutionQuality extends AvaTaxEnum 
{

    const NotCoded = "NotCoded";
    const External = "External";
    const CountryCentroid = "CountryCentroid";
    const RegionCentroid = "RegionCentroid";
    const PartialCentroid = "PartialCentroid";
    const PostalCentroidGood = "PostalCentroidGood";
    const PostalCentroidBetter = "PostalCentroidBetter";
    const PostalCentroidBest = "PostalCentroidBest";
    const Intersection = "Intersection";
    const Interpolated = "Interpolated";
    const Rooftop = "Rooftop";
    const Constant = "Constant";
}
