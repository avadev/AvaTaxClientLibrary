using Avalara.AvaTax.RestClient;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;

namespace AvaTaxDesktop.Pages
{
    /// <summary>
    /// Interaction logic for InvoicePage.xaml
    /// </summary>
    public partial class InvoicePage : Page
    {
        public CreateTransactionModel Model { get; set; }
        public InvoicePage()
        {
            InitializeComponent();
            Model = new CreateTransactionModel();
            Model.lines = new List<LineItemModel>();
            Model.lines.Add(new LineItemModel()
            {
                taxCode = "P0000000",
                amount = 100.0m,
                quantity = 1
            });
            grdLines.ItemsSource = Model.lines;
        }
    }
}
