using System.DirectoryServices;
using System.DirectoryServices.AccountManagement;
using System.Security.Principal;

namespace SqlOrganize
{
    public class UsuarioDominio
    {
        public Guid Guid { get; set; }
        public string DisplayName { get; set; } = "";
        public string Username { get; set; } = "";
        public bool IsLoggedIn { get; set; } = false;
        public byte[]? ProfilePhoto { get; set; }
        public string? EmailAddress { get; set; }


        public UsuarioDominio(string Domain)
        {
            using (PrincipalContext context = new PrincipalContext(ContextType.Domain, Domain))
            {
                Username = WindowsIdentity.GetCurrent().Name.Replace("DO\\","");
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
