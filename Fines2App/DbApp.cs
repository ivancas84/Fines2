using Microsoft.Extensions.Caching.Memory;
using SqlOrganize;
using SqlOrganizeMy;

namespace Fines2App
{
    internal class DbApp : DbMy
    {
        public DbApp(Config config, Schema sch, MemoryCache cache) : base(config, sch, cache)
        {
        }

        
    }
}
