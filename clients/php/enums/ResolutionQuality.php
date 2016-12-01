<?php
class ResolutionQuality extends AvaTaxEnum 
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
?>
