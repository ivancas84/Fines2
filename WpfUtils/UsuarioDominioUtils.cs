using SqlOrganize;
using System.IO;
using System.Windows.Media.Imaging;

namespace WpfUtils
{
    public static class UsuarioDominioUtils
    {

        public static BitmapImage LoadBitmapImageFromProfilePhoto(this UsuarioDominio usuarioDominio)
        {
            // Convertir bytes a BitmapImage
            BitmapImage bitmapImage = new BitmapImage();
            if (!usuarioDominio.ProfilePhoto.IsNoE())
            {
                using (MemoryStream stream = new MemoryStream(usuarioDominio.ProfilePhoto!))
                {
                 
                    stream.Position = 0;
                    bitmapImage.BeginInit();
                    bitmapImage.StreamSource = stream;
                    bitmapImage.CacheOption = BitmapCacheOption.OnLoad;
                    bitmapImage.EndInit();

                }
            }

            return bitmapImage;
        }
    }
}
