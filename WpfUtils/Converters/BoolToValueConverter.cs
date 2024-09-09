using System;
using System.Windows;
using System.Windows.Data;

namespace WpfUtils.Converters
{
    public class BoolToValueConverter<T> : IValueConverter
    {
        public T FalseValue { get; set; }
        public T TrueValue { get; set; }

        public object Convert(object value, Type targetType, object parameter, System.Globalization.CultureInfo culture)
        {
            if (value == null)
                return FalseValue;
            else
                return (bool)value ? TrueValue : FalseValue;
        }

        public object ConvertBack(object value, Type targetType, object parameter, System.Globalization.CultureInfo culture)
        {
            return value != null ? value.Equals(TrueValue) : false;
        }
    }

    public class BoolToStringConverter : BoolToValueConverter<string> { }

    /// <example>
    /// xmlns:conv1="clr-namespace:WpfUtils.Converters;assembly=WpfUtils"
    /// <Page.Resources>
    ///     <conv1:BoolToVisibilityConverter TrueValue = "Collapsed" FalseValue="Visible" x:Key="iBtvConv"/>
    ///     <conv1:BoolToVisibilityConverter TrueValue = "Visible" FalseValue="Collapsed" x:Key="btvConv"/>
    /// <Page.Resources>
    /// ...
    /// <StackPanel Visibility="{Binding someBooleanVar, Converter={StaticResource btvConv},Mode=TwoWay,NotifyOnSourceUpdated=True,UpdateSourceTrigger=PropertyChanged}"/>
    /// <StackPanel Visibility="{Binding someBooleanVar, Converter={StaticResource iBtvConv},Mode=TwoWay,NotifyOnSourceUpdated=True,UpdateSourceTrigger=PropertyChanged}"/>
    /// ...
    /// <DataGridTextColumn Header="Status" Binding="{Binding IsActive, Converter={StaticResource BoolToYesNoConverter}}" />
    /// </example>
    public class BoolToVisibilityConverter : BoolToValueConverter<Visibility> { }
    public class BoolToObjectConverter : BoolToValueConverter<object> { }

    
}
