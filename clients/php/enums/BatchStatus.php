<?php
class BatchStatus extends AvaTaxEnum 
{

    const Waiting = "Waiting";
    const SystemErrors = "SystemErrors";
    const Cancelled = "Cancelled";
    const Completed = "Completed";
    const Creating = "Creating";
    const Deleted = "Deleted";
    const Errors = "Errors";
    const Paused = "Paused";
    const Processing = "Processing";
}
?>
