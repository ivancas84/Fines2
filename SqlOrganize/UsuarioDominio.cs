using System.DirectoryServices;
using System.DirectoryServices.AccountManagement;
using System.Security.Principal;

namespace SqlOrganize
{
    public class UsuarioDominio
    {
        public Guid Guid;
        public string DisplayName = "";
        public string Username = "";
        public bool IsLoggedIn = false;
        public byte[]? ProfilePhoto;
        public string? EmailAddress;

        public UsuarioDominio(string Domain)
        {
            using (PrincipalContext context = new PrincipalContext(ContextType.Domain, Domain))
            {
                Username = WindowsIdentity.GetCurrent().Name;
                UserPrincipal userPrincipal = UserPrincipal.FindByIdentity(context, IdentityType.SamAccountName, Username);
                if (userPrincipal == null)
                    return;

                Guid = (Guid)userPrincipal.Guid;
                DirectoryEntry de = userPrincipal.GetUnderlyingObject() as DirectoryEntry;
                ProfilePhoto = (de.Properties.Contains("thumbnailPhoto") && !!de.Properties["tumbnailPhoto"].IsNoE()) ?
                    de.Properties["thumbnailPhoto"].Value as byte[] : null;

                DisplayName = userPrincipal.DisplayName;
                EmailAddress = userPrincipal.EmailAddress;
                IsLoggedIn = true;
                
                //PrincipalSearchResult<Principal> groups = userPrincipal.GetAuthorizationGroups();
            }
        }
    }
}
