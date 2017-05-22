using Avalara.AvaTax.RestClient;
using CommandLine;
using CommandLine.Text;
using System;

namespace AvaTax_Connect
{
    class Program
    {
        static void Main(string[] args)
        {
            // Parse options and show helptext if insufficient
            Options o = new Options();
            Parser.Default.ParseArguments(args, o);
            if (!o.IsValid()) {
                var help = HelpText.AutoBuild(o);
                Console.WriteLine(help.ToString());
                return;
            }

            // Parse server URI
            if (String.IsNullOrEmpty(o.Environment) || !o.Environment.StartsWith("http")) {
                Console.WriteLine($"Invalid URI: {o.Environment}");
                return;
            }

            // Set up AvaTax
            var client = new AvaTaxClient("AvaTax-Connect", "1.0", Environment.MachineName, new Uri(o.Environment))
                .WithSecurity(o.Username, o.Password);

            // Print out information about our configuration
            Console.WriteLine($"AvaTax-Connect Performance Testing Tool");
            Console.WriteLine($"=======================================");
            Console.WriteLine($"          SDK: {AvaTaxClient.API_VERSION}");
            Console.WriteLine($"  Environment: {o.Environment}");
            Console.WriteLine($"         User: {o.Username}");
            Console.WriteLine($"    Tax Lines: {o.Lines}");
            Console.WriteLine($"         Type: {o.DocType}");
            Console.WriteLine();
            Console.WriteLine("    Call    Server         Network        Client         Total");

            // Use transaction builder
            var tb = new TransactionBuilder(client, "DEFAULT", o.DocType, "ABC")
                .WithAddress(TransactionAddressType.SingleLocation, "123 Main Street", null, null, "Irvine", "CA", "92615", "US");

            // Add lines
            for (int i = 0; i < o.Lines; i++) {
                tb.WithLine(100.0m);
            }
            var ctm = tb.GetCreateTransactionModel();

            // Discard the first call?
            if (o.DiscardFirstCall.HasValue && o.DiscardFirstCall.Value) {
                var t = client.CreateTransaction(null, ctm);
            }

            // Connect to AvaTax and print debug information
            int count = 0;
            CallDuration total = new CallDuration();
            long totalms = 0;
            while (!Console.KeyAvailable) {
                count++;
                if (o.Calls.HasValue && count > o.Calls.Value) {
                    Console.WriteLine("Done.");
                    return;
                }

                // Make one tax transaction
                try {
                    DateTime start = DateTime.UtcNow;
                    var t = client.CreateTransaction(null, ctm);
                    TimeSpan ts = DateTime.UtcNow - start;
                    total.Combine(client.LastCallTime);
                    totalms += ts.Milliseconds;

                    // Write some information
                    var cd = client.LastCallTime;
                    Console.WriteLine($"    {count.ToString("0000")}    {cd.ServerDuration.TotalMilliseconds.ToString("0000.0000")}ms    {cd.TransitDuration.TotalMilliseconds.ToString("0000.0000")}ms    {(cd.SetupDuration.TotalMilliseconds + cd.ParseDuration.TotalMilliseconds).ToString("0000.0000")}ms    {ts.TotalMilliseconds.ToString("0000.0000")}ms");
                } catch (Exception ex) {
                    Console.WriteLine($"    {count.ToString("0000")}    FAILED: {ex.Message}");
                }
            }

            // Compute some averages
            double avg = totalms * 1.0 / count;
            double total_overhead = (total.SetupDuration.TotalMilliseconds + total.ParseDuration.TotalMilliseconds);
            double total_transit = total.TransitDuration.TotalMilliseconds;
            double total_server = total.ServerDuration.TotalMilliseconds;
            double avg_overhead = total_overhead / count;
            double avg_transit = total_transit / count;
            double avg_server = total_server / count;
            double pct_overhead = total_overhead / totalms;
            double pct_transit = total_transit / totalms;
            double pct_server = total_server / totalms;

            // Print out the totals
            Console.WriteLine();
            Console.WriteLine($"Finished {count} calls in {totalms} milliseconds.");
            Console.WriteLine($"    Average: {avg.ToString("0.00")}ms; {avg_overhead.ToString("0.00")}ms overhead, {avg_transit.ToString("0.00")}ms transit, {avg_server.ToString("0.00")}ms server.");
            Console.WriteLine($"    Percentage: {pct_overhead.ToString("P")} overhead, {pct_transit.ToString("P")} transit, {pct_server.ToString("P")} server.");
            Console.WriteLine($"    Total: {total_overhead} overhead, {total_transit} transit, {total_server} server.");
         }
    }
}
