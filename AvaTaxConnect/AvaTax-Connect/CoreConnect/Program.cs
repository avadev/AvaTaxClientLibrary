using Avalara.AvaTax.RestClient;
using CommandLine;
using CommandLine.Text;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Threading;
using System.Threading.Tasks;

namespace CoreConnect
{
    class Program
    {
        static void Main(string[] args)
        {
            CommandLine.Parser.Default.ParseArguments<Options>(args)
                .WithParsed<Options>(opts => RunOptionsAndReturnExitCode(opts))
                .WithNotParsed<Options>((errs) => HandleParseError(errs));
        }

        public static void HandleParseError(IEnumerable<Error> errs)
        {
        }

        public static void RunOptionsAndReturnExitCode(Options opt)
        {
            g_Options = opt;

            // This syntax is kinda humorous
            Run().Wait();
        }

        public static async Task Run()
        {
            
            // Parse server URI
            if (String.IsNullOrEmpty(g_Options.Environment) || !g_Options.Environment.StartsWith("http"))
            {
                Console.WriteLine($"Invalid URI: {g_Options.Environment}");
                return;
            }

            // Set up AvaTax
            g_Client = new AvaTaxClient("AvaTax-Connect", "1.0", Environment.MachineName, new Uri(g_Options.Environment))
                .WithSecurity(g_Options.Username, g_Options.Password);

            // Attempt to connect
            PingResultModel ping = null;
            try
            {
                ping = g_Client.Ping();
            }
            catch (Exception ex)
            {
                Console.WriteLine("Unable to contact AvaTax:");
                HandleException(ex);
                return;
            }

            // Fetch the company
            FetchResult<CompanyModel> companies = null;
            try
            {
                companies = await g_Client.QueryCompaniesAsync(null, $"companyCode eq '{g_Options.CompanyCode}'", null, null, null);
            }
            catch (Exception ex)
            {
                Console.WriteLine("Exception fetching companies");
                HandleException(ex);
                return;
            }

            // Check if the company exists
            if (companies == null || companies.count != 1)
            {
                Console.WriteLine($"Company with code '{g_Options.CompanyCode}' not found.\r\nPlease provide a valid companyCode using the '-c' parameter.");
                return;
            }

            // Check if the company is flagged as a test
            if ((companies.value[0].isTest != true) && IsPermanent(g_Options.DocType))
            {
                Console.WriteLine($"Company with code '{g_Options.CompanyCode}' is not flagged as a test company.\r\nYour test is configured to use document type '{g_Options.DocType}'.\r\nThis is a permanent document type.\r\nWhen testing with permanent document types, AvaTax-Connect can only be run against a test company.");
                return;
            }

            // Did we authenticate?
            if (ping.authenticated != true)
            {
                Console.WriteLine("Authentication did not succeed.  Please check your credentials and try again.");
                Console.WriteLine($"       Username: {g_Options.Username}");
                Console.WriteLine($"       Password: REDACTED - PLEASE CHECK COMMAND LINE");
                Console.WriteLine($"    Environment: {g_Options.Environment}");
                return;
            }

            // Print out information about our configuration
            Console.WriteLine($"AvaTax-Connect Performance Testing Tool");
            Console.WriteLine($"=======================================");
            Console.WriteLine($"         User: {g_Options.Username}");
            Console.WriteLine($"      Account: {ping.authenticatedAccountId}");
            Console.WriteLine($"       UserId: {ping.authenticatedUserId}");
            Console.WriteLine($"  CompanyCode: {g_Options.CompanyCode}");
            Console.WriteLine($"          SDK: {AvaTaxClient.API_VERSION}");
            Console.WriteLine($"  Environment: {g_Options.Environment}");
            Console.WriteLine($"    Tax Lines: {g_Options.Lines}");
            Console.WriteLine($"         Type: {g_Options.DocType}");
            Console.WriteLine($"      Threads: {g_Options.Threads}");
            Console.WriteLine();
            Console.WriteLine("  Call   Server   DB       Svc      Net      Client    Total");

            // Use transaction builder
            var tb = new TransactionBuilder(g_Client, g_Options.CompanyCode, g_Options.DocType, "ABC");

            // Add lines
            for (int i = 0; i < g_Options.Lines; i++)
            {
                tb.WithLine(100.0m)
                    .WithLineAddress(TransactionAddressType.PointOfOrderAcceptance, "123 Main Street", null, null, "Irvine", "CA", "92615", "US")
                    .WithLineAddress(TransactionAddressType.PointOfOrderOrigin, "123 Main Street", null, null, "Irvine", "CA", "92615", "US")
                    .WithLineAddress(TransactionAddressType.ShipFrom, "123 Main Street", null, null, "Irvine", "CA", "92615", "US")
                    .WithLineAddress(TransactionAddressType.ShipTo, "123 Main Street", null, null, "Irvine", "CA", "92615", "US");
            }
            g_Model = tb.GetCreateTransactionModel();

            // Discard the first call?
            try
            {
                if (g_Options.DiscardFirstCall.HasValue && g_Options.DiscardFirstCall.Value)
                {
                    var t = g_Client.CreateTransaction(null, g_Model);
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine("Cannot connect to AvaTax.");
                HandleException(ex);
                return;
            }

            // Connect to AvaTax and print debug information
            g_TotalDuration = new CallDuration();
            g_TotalMs = 0;
            List<Task> threads = new List<Task>();
            for (int i = 0; i < g_Options.Threads; i++)
            {
                var task = Task.Run(ConnectThread);
                threads.Add(task);
            }
            await Task.WhenAll(threads);

            // Compute some averages
            double avg = g_TotalMs * 1.0 / g_Count;
            double total_overhead = (g_TotalDuration.SetupDuration.TotalMilliseconds + g_TotalDuration.ParseDuration.TotalMilliseconds);
            double total_transit = g_TotalDuration.TransitDuration.TotalMilliseconds;
            double total_server = g_TotalDuration.ServerDuration.TotalMilliseconds;
            double avg_overhead = total_overhead / g_Count;
            double avg_transit = total_transit / g_Count;
            double avg_server = total_server / g_Count;
            double pct_overhead = total_overhead / g_TotalMs;
            double pct_transit = total_transit / g_TotalMs;
            double pct_server = total_server / g_TotalMs;

            // Print out the totals
            Console.WriteLine();
            Console.WriteLine($"Finished {g_Count} calls in {g_TotalMs} milliseconds.");
            Console.WriteLine($"    Average: {avg.ToString("0.00")}ms; {avg_overhead.ToString("0.00")}ms overhead, {avg_transit.ToString("0.00")}ms transit, {avg_server.ToString("0.00")}ms server.");
            Console.WriteLine($"    Percentage: {pct_overhead.ToString("P")} overhead, {pct_transit.ToString("P")} transit, {pct_server.ToString("P")} server.");
            Console.WriteLine($"    Total: {total_overhead} overhead, {total_transit} transit, {total_server} server.");
        }

        private static bool IsPermanent(DocumentType docType)
        {
            switch (docType)
            {
                case DocumentType.InventoryTransferInvoice:
                case DocumentType.PurchaseInvoice:
                case DocumentType.ReturnInvoice:
                case DocumentType.ReverseChargeInvoice:
                case DocumentType.SalesInvoice:
                    return true;
            }
            return false;
        }

        public static int g_Count = 0;
        public static AvaTaxClient g_Client = null;
        public static Options g_Options = null;
        public static CreateTransactionModel g_Model = null;
        public static long g_TotalMs = 0;
        public static CallDuration g_TotalDuration = null;

        private static async Task ConnectThread()
        {
            while (!Console.KeyAvailable)
            {
                g_Count++;

                // Allow calls to end after a fixed length
                if (g_Options.Calls.HasValue && g_Count > g_Options.Calls.Value)
                {
                    Console.WriteLine("Done.");
                    return;
                }

                // Make one tax transaction
                try
                {
                    DateTime start = DateTime.UtcNow;
                    var t = await g_Client.CreateTransactionAsync(null, g_Model);
                    TimeSpan ts = DateTime.UtcNow - start;
                    g_TotalDuration.Combine(g_Client.LastCallTime);
                    g_TotalMs += (long)ts.TotalMilliseconds;

                    // Write some information
                    var cd = g_Client.LastCallTime;

                    // Check if user wants us to log everything, or only exceptional delays
                    if ((!g_Options.LogExceptionalDelays) || (ts.TotalMilliseconds > 1000))
                    {
                        Console.WriteLine($"  {g_Count.ToString("0000")}   {cd.ServerDuration.TotalMilliseconds.ToString("0000")}ms   {cd.DataDuration.TotalMilliseconds.ToString("0000")}ms   {cd.ServiceDuration.TotalMilliseconds.ToString("0000")}ms   {cd.TransitDuration.TotalMilliseconds.ToString("0000")}ms   {(cd.SetupDuration.TotalMilliseconds + cd.ParseDuration.TotalMilliseconds).ToString("0000")}ms    {ts.TotalMilliseconds.ToString("0000")}ms");
                    }

                    // Always log exceptions
                }
                catch (Exception ex)
                {
                    HandleException(ex);
                    Console.WriteLine($"  {g_Count.ToString("0000")}    EXCEPTION: {ex.Message}");
                    Console.WriteLine(ex.ToString());
                }

                // Sleep in between calls, if desired
                if (g_Options.SleepBetweenCalls != 0)
                {
                    await Task.Delay(g_Options.SleepBetweenCalls);
                }
            }
        }

        private static void HandleException(Exception ex)
        {
            // Did AvaTax report an error?
            if (ex is AvaTaxError)
            {
                var ata = ex as AvaTaxError;
                Console.WriteLine($"  {g_Count.ToString("0000")}    [ERROR {ata.error.error.code}] {ata.error.error.message}");
                foreach (var detail in ata.error.error.details)
                {
                    Console.WriteLine($"Help: {detail.helpLink}");
                }

                // Is this an aggregate / async / await exception?
            }
            else if (ex is AggregateException)
            {
                var agg = ex as AggregateException;
                foreach (var inner in agg.InnerExceptions)
                {
                    HandleException(inner);
                }

                // Is this an aggregate / async / await exception?
            }
            else
            {
                Console.WriteLine($"Unrecognized exception: {ex.Message}");
            }
        }
    }
}
