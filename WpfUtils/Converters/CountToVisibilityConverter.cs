using SqlOrganize.ValueTypesUtils;
using System;
using System.Globalization;
using System.Windows;
using System.Windows.Data;

namespace WpfUtils.Converters
{
    /// <summary> Visibilidad en base a la cantidad de elementos de una coleccion </summary>
    /***
     * <example> Setear public ObservableCollection<Item> Items { get; set; }
     *   
     *  xmlns:conv1="clr-namespace:WpfUtils.Converters;assembly=WpfUtils"
     *   
     *  <Window.Resources>
     *      <conv1:CountToVisibilityConverter x:Key="ctvConv" />
     *  </Window.Resources>
     *  <DataGrid x:Name="DataGridItems"
     *      ItemsSource="{Binding Items}" 
     *      Visibility="{Binding Items.Count, Converter={StaticResource ctvConv}}">    
     *   <TextBlock  Text="No items available"                    
     *      Visibility="{Binding tems.Count, Converter={StaticResource ctvConv}, ConverterParameter=Invert}" />
     
     *  </example>
     */
    public class CountToVisibilityConverter : IValueConverter
    {
        public object Convert(object value, Type targetType, object parameter, CultureInfo culture)
        {
            if (value is int count)
            {
                bool invert = parameter?.ToString() == "Invert";

                return (count > 0) ^ invert ? Visibility.Visible : Visibility.Collapsed;
            }

            return Visibility.Collapsed;
        }

        public object ConvertBack(object value, Type targetType, object parameter, CultureInfo culture)
        {
            throw new NotSupportedException();
        }
    }
}