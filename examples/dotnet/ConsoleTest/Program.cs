using Avalara.AvaTax.RestClient;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ConsoleTest
{
    class Program
    {
        /// <summary>
        /// To debug this application, call app must be called with args[0] as username and args[1] as password
        /// </summary>
        /// <param name="args"></param>
        public static void Main(string[] args)
        {
            MainAsync(args).Wait();
        }

        public async static Task MainAsync(string[] args)
        {
            // Connect to the server
            var client = new AvaTaxClient("ConsoleTest", "1.0", Environment.MachineName, AvaTaxEnvironment.Sandbox);
            client.WithSecurity(args[0], args[1]);

            // Call Ping
            var pingResult = await client.Ping();
            Console.WriteLine(pingResult.version);

            // Call fetch
            try {
                var companies = await client.QueryCompanies(null, null, 0, 0, null);
                Console.WriteLine(companies.ToString());

                // Initialize a company and fetch it back
                var init = await client.CompanyInitialize(new CompanyInitializationModel()
                {
                    city = "Bainbridge Island",
                    companyCode = Guid.NewGuid().ToString("N"),
                    country = "US",
                    email = "bob@example.org",
                    faxNumber = null,
                    firstName = "Bob",
                    lastName = "Example",
                    line1 = "100 Ravine Lane",
                    line2 = null,
                    line3 = null,
                    mobileNumber = null,
                    name = "Bob Example",
                    phoneNumber = "206 555 1212",
                    postalCode = "98110",
                    region = "WA",
                    taxpayerIdNumber = "123456789",
                    title = "Owner",
                    vatRegistrationId = null
                });
                Console.WriteLine(init.ToString());

                // Fetch it back
                var fetchBack = await client.GetCompany(init.id, "Locations");
                Console.WriteLine(fetchBack.ToString());

            } catch (AvaTaxError ex) {
                Console.WriteLine(ex.error.ToString());
            }
        }
    }
}
