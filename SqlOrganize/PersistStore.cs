using System;
using System.Collections;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Transactions;

namespace SqlOrganize
{
    internal class PersistStore 
    {
        public Db Db { get; }
        public List<EntityPersist> persists = new();

        public PersistStore(Db db)
        {
            Db = db;
        }

        public PersistStore Add(EntityPersist persist)
        {
            persists.Add(persist);
            return this;
        }


        public PersistStore Exec()
        {
            var q = Db.Query();
            q.connection = q.NewConnection();
            //q.transaction = transaction;
            //q.sql = sql;
            //q.parameters = parameters;
            //q.Exec();*/
            return this;
        }

        public PersistStore Transaction()
        {
            var q = Db.Query();
            q.connection = q.NewConnection();
            q.transaction = q.connection.BeginTransaction();
            return this;
        }

    }
}
