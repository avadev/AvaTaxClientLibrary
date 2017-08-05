using Avalara.AvaTax.RestClient;
using System;
using System.Collections.Generic;
using System.Configuration;
using System.Data;
using System.Linq;
using System.Threading.Tasks;
using System.Windows;

namespace AvaTaxDesktop
{
    /// <summary>
    /// Interaction logic for App.xaml
    /// </summary>
    public partial class App : Application
    {
        /// <summary>
        /// Instance of the AvaTax client for this app
        /// </summary>
        public static AvaTaxClient Client { get; set; }
    }
}
