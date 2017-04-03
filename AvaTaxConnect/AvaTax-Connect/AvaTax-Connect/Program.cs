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

            // Set up AvaTax
            var client = new AvaTaxClient("AvaTax-Connect", "1.0", Environment.MachineName, o.Environment)
                .WithSecurity(o.Username, o.Password);

            // Use transaction builder
            var tb = new TransactionBuilder(client, "DEFAULT", DocumentType.SalesOrder, "ABC")
                .WithAddress(TransactionAddressType.SingleLocation, "123 Main Street", null, null, "Irvine", "CA", "92615", "US");

            // Add lines
            for (int i = 0; i < o.Lines; i++) {
                tb.WithLine(100.0m);
            }
            var ctm = tb.GetCreateTransactionModel();

            // Connect to AvaTax and print debug information
            int count = 0;
            while (true) {
                count++;
                if (o.Calls.HasValue && count > o.Calls.Value) {
                    Console.WriteLine("Done.");
                    return;
                } else {
                    Console.Write($"Call #{count}: ");
                }

                // Make one tax transaction
                DateTime start = DateTime.UtcNow;
                var t = client.CreateTransaction(null, ctm);
                TimeSpan ts = DateTime.UtcNow - start;

                // Write some information
                Console.WriteLine($"Completed in {ts.TotalMilliseconds}ms; tax is {t.totalTax}.");
            }
        }
    }
}
